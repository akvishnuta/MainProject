<?php
header('Content-type : bitmap; charset=utf-8');
include('db_connection_bank1.php');
include('db_connection.php');


if (isset($_POST['AccNo'])){
	$AccNo = $_POST["AccNo"];
	$bankPassword=$_POST["bankPassword"];
	$benefeciaryUniqueKey=$_POST["UniqueKey"];
	$transferAmount=$_POST["Amount"];
	
	$connection=db_connection_bank1();
	$connection2=db_connection();

	$query="select * from accounts where AccNo='$AccNo';";
	$result=mysqli_query($connection,$query);
	$password='';
	$name='';
	$unique_key='';
	if($result){
		while($row = $result->fetch_array()){
			$bankPasswordFromDB = $row['bankPassword'];
			$balance = $row['balance'];
			if($bankPassword==$bankPasswordFromDB){
				$balance=$balance-$transferAmount;
			}
		}
		if($bankPassword==$bankPasswordFromDB){
			//echo 'Success:'.$name.':'.$balance;
		}else{
			echo 'Failed:Sorry, username or password does not match';
		}
	}else{
		echo 'Failed:Unable to Connect to Database';
	}

	$query="update accounts set balance='$balance' where  AccNo='$AccNo';";
	$result=mysqli_query($connection,$query);
	if($result){
		echo 'Success:'.$name.':'.$balance;
		//Add Amount to beneficiary here
		$query="select * from accounts where AccNo='$AccNo';";
		$result2=mysqli_query($connection2,$query);
		if($result){

		}else{
			
		}

	}else{
		echo 'Failed:Transaction Failed';
	}

	$query="update accounts set balance='$balance' where  AccNo='$AccNo';";
	$result=mysqli_query($connection,$query);
	if($result){
		echo 'Success:'.$name.':'.$balance;
		//Add Amount to beneficiary here
	}else{
		echo 'Failed:Transaction Failed';
	}


	mysqli_close($connection);
	mysqli_close($connection2);
}
?>