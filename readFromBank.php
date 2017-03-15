<?php
header('Content-type : bitmap; charset=utf-8');
include('db_connection_bank1.php');


if (isset($_POST['AccNo'])){
	$AccNo = $_POST["AccNo"];
	$bankPassword=$_POST["bankPassword"];
	$connection=db_connection_bank1();
	$query="select * from accounts where AccNo='$AccNo';";
	$result=mysqli_query($connection,$query);
	$password='';
	$name='';
	$unique_key='';
	if($result){
		while($row = $result->fetch_array()){
			$bankPasswordFromDB = $row['bankPassword'];
			$balance = $row['balance'];
		}
		if($bankPassword==$bankPasswordFromDB){
			echo 'Success:'.$name.':'.$balance;
		}else{
			echo 'Failed:Sorry, username or password does not match';
		}
	}else{
		echo 'Failed:Unable to Connect to Database';
	}

	mysqli_close($connection);
}
?>