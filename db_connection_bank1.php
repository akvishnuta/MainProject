<?php	

		function db_connection_bank1(){
			$server='localhost';
			$user='root';
			$password='';
			$database='Bank1_db';
			//$conn='';
			$conn=mysqli_connect($server,$user,$password,$database);
			return $conn;
		}

?>