<?php

	class db_connect{
		var $connection;
		var $server='localhost';
		var $user='root';
		var $password='';
		var $database='AudioPay_db';
		public function __construct(){

		}
		public static function withoutParams(){
			return $this->connection = mysqli_connect($this->server,$this->user,$this->password,$this->database);
		}
		public static function withParans($server,$user,$password,$database){
			return $this->connection = mysqli_connect($server,$user,$password,$database);
		}
		
	}
	
}
?>