<?php
include('db_connection.php');
function generateRandom(){
	$connection=db_connection();
	$query="select * from admin";
	$result=mysqli_query($connection,$query);
	$randomNumber="";
	$found=False;
	do{	
		$randomNumber="AP".generate();
		if($result){
			while($row = $result->fetch_array()){
				$unique_key = $row['unique_key'];
				if($unique_key==$randomNumber){
					$found=True;
					break;
				}else{
					$found=False;
				}
			}
		}else{
			echo 'Failed';
		}	
	}while($found==True);
	mysqli_close($connection);
	return $randomNumber;
}

function generate(){
	$randomNumber=(string)(rand(1000,1000000));
		if(strlen($randomNumber)<6){
			$pad=6-strlen($randomNumber);
			while($pad>0){
				$randomNumber="0".$randomNumber;
				$pad--;
			}
		}
	return $randomNumber;
}
?>