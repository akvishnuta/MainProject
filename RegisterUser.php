<?php
header('Content-type : bitmap; charset=utf-8');
//echo "hello";
//include('db_connection.php');
include('generateRandom.php');


if (isset($_POST['mEmail'])){
		
		$mName = $_POST["mName"];
		$mPhone=$_POST["mPhone"];
		$mEmail = $_POST["mEmail"];
		$mPassword=$_POST["mPassword"];
		$mAccNo = $_POST["mAccNo"];
		$mBankPassword = $_POST["mBankPassword"];
		
	if(checkMail($mEmail)){
		$randomNo=generateRandom();
		if($randomNo!="Failed"){
			$connection=db_connection();
			$query="insert into admin(name,phone,email,password,AccNo,bankPassword,unique_key)  values('$mName','$mPhone','$mEmail','$mPassword','$mAccNo','$mBankPassword','$randomNo')";
			$result=mysqli_query($connection,$query);
			if($result){
					echo 'Success:'.'Successfully Registered '.$mName;
				}else{
					echo 'Failed:Failed Registration';
				}
			mysqli_close($connection);
		}
	}else{
		echo 'Failed:Email Already Registered';
	}
}

function checkMail($mEmail){
	$connection=db_connection();
	$query="select * from admin";
	$result=mysqli_query($connection,$query);
	$randomNumber="";
	$found=False;
		if($result){
			while($row = $result->fetch_array()){
				$email = $row['email'];
				if($email==$mEmail){
					$found=True;
					break;
				}else{
					$found=False;
				}
			}
			if($found==True){
				return False;//mail exists
			}
		}else{
			echo 'Failed';
		}	
	
	mysqli_close($connection);
	return True; //mail doesn;t exist
	}
?>