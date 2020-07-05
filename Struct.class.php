<?php
	/*
	Struct Class - Defines data structure of a record
	(C) Business Computing Research Laboratory <www.bucorel.com>
	Written by - Pushpendra Singh Thakur <thakur@bucorel.com>
	*/
	
	namespace bucorel\struct;
	
	use bucorel\datatype\DataType;
	use bucorel\reader\BasicValidator;
	use bucorel\reader\ValidatorException;
	
	class Struct{
		
		protected $fields = array();
		protected $data = array();
		
		function defineField( $name, $dataType, $multiValue=false, $defaultValue=null ){
			if( array_key_exists( $name, $this->fields ) ){
				throw new StructException( "FIELD_EXISTS\n".$name );
			}
			
			$this->fields[ $name ] = array( $dataType, $multiValue );
			
			if( $multiValue ){
				$this->data[ $name ] = array();
				return;
			}

			switch( $dataType ){
				case DataType::PDT_INTEGER:
				case DataType::PDT_FLOAT:
					$this->data[ $name ] = 0;
					break;
				case DataType::PDT_TUPLE:
					$this->data[ $name ] = array();
					break;
				case DataType::PDT_TEXT:
				case DataType::PDT_BOOLEAN:
				case DataType::PDT_UUID:
					$this->data[ $name ] = "";
					break;
				default:
					throw new StructException( "UNKNOWN_DATA_TYPE\n".$name );
			}
		}
		
		function set( $name, $value ){
			if( !array_key_exists( $name, $this->fields ) ){
				throw new StructException( "NO_SUCH_KEY\n$name" );
			}
			
			if( $this->fields[ $name ][1] ){
				$this->setMultiValue( $name, $value );
			}else{
				$this->setSingleValue( $name, $value );
			}
		}
		
		function get( $name ){
			if( !array_key_exists( $name, $this->fields ) ){
				throw new StructException( "NO_SUCH_KEY\n$name" );
			}
			
			return $this->data[ $name ];
		}
		
		function load( $data ){
			foreach( $this->fields as $k=>$v ){
				if( array_key_exists( $k, $data )){
					$this->set( $k, $data[$k] );
				}
			}
		}
		
		function dump(){
			return $this->data;
		}
		
		function dumpJson(){
			return json_encode( $this->data );
		}
		
		private function setSingleValue( $name, $value ){
			try{
				switch( $this->fields[ $name ][0] ){
					case DataType::PDT_INTEGER:
						BasicValidator::validateInteger( $value );
						break;
					case DataType::PDT_FLOAT:
						BasicValidator::validateFloat( $value );
						break;
					case DataType::PDT_BOOLEAN:
						BasicValidator::validateBoolean( $value );
						break;
					case DataType::PDT_TUPLE:
						BasicValidator::validateTuple( $value );
						break;
				}
				
				$this->data[ $name ] = $value;
			}catch( ValidatorException $ve ){
				throw new StructException( $ve->getMessage()." - \n$name");
			}
		}
		
		private function setMultiValue( $name, $values ){
			try{
				foreach( $values as $value ){
					switch( $this->fields[ $name ][0] ){
						case DataType::PDT_INTEGER:
							BasicValidator::validateInteger( $value );
							break;
						case DataType::PDT_FLOAT:
							BasicValidator::validateFloat( $value );
							break;
						case DataType::PDT_BOOLEAN:
							BasicValidator::validateBoolean( $value );
							break;
						case DataType::PDT_TUPLE:
							BasicValidator::validateTuple( $value );
							break;
					}
				}
				
				$this->data[ $name ] = $values;
				
			}catch( ValidatorException $ve ){
				throw new StructException( $ve->getMessage()." - \n$name");
			}
		}
	}
?>
