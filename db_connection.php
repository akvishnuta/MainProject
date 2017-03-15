<?php	

		function db_connection(){
			$server='localhost';
			$user='root';
			$password='';
			$database='AudioPay_db';
			//$conn='';
			$conn=mysqli_connect($server,$user,$password,$database);
			return $conn;
		}

?>