<?php
header('Content-type : bitmap; charset=utf-8');

if(isset($_POST["encoded_string"])){

	$encoded_string = $_POST["encoded_string"];
	$image_name=$_POST["image_name"];
	$decoded_string=base64_decode($encoded_string);
	
	$path='audios/'.$image_name;

	$file = fopen($path, 'wb');

	$is_written=fwrite($file, $decoded_string);
	fclose($file);
	if($is_written>0){
		$connection = mysqli_connect('localhost','root','','AudioPay_db');
		$query="insert into photos(name,path) values('$image_name','$path');";
		$result=mysqli_query($connection,$query);
	}

	if($result){
		echo 'Success';
	}else{
		echo 'Failed';
	}

	mysqli_close($connection);
}
?>