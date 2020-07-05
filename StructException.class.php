<?php
	namespace bucorel\webe\struct;
	
	class StructException extends \Exception{
	
		protected $fieldName = "";
		
		function __construct( $message ){
			$ps = explode( "\n", $message );
			if( isset($ps[1]) ){
				$this->fieldName = $ps[1];
			}
			
			parent::__construct( $ps[0] );
		}
		
		function getFieldName(){
			return $this->fieldName;
		}
	}
?>
