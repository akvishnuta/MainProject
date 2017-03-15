<?php
header('Content-type : bitmap; charset=utf-8');
include('db_connection.php');


if (isset($_POST['mEmail'])){
	$mEmail = $_POST["mEmail"];
	$mPassword=$_POST["mPassword"];
	$connection=db_connection();
	$query="select * from admin where email='$mEmail';";
	$result=mysqli_query($connection,$query);
	$password='';
	$name='';
	$unique_key='';
	$AccNo='';
	$bankPassword='';
	if($result){
		while($row = $result->fetch_array()){
			$password = $row['password'];
			$name = $row['name'];
			$unique_key=$row['unique_key'];
			$AccNo=$row['AccNo'];
			$bankPassword=$row['bankPassword'];
		}
		if($password==$mPassword){
			echo 'Success:'.$name.':'.$unique_key.':'.$AccNo.':'.$bankPassword;
		}else{
			echo 'Failed:Sorry, username or password does not match';
		}
	}else{
		echo 'Failed:Unable to Connect to Database';
	}

	mysqli_close($connection);
}
?>