<?php
	include 'connection.php';
	$option = $_POST['answer'];
	$cusid = $_POST['session'];
	$change_policy = "UPDATE customer SET privacy = $option WHERE customer_id = $cusid;";
	if (mysqli_query($link, $change_policy)) {
		echo json_encode(array("statusCode"=>200));
		
	} 
	else {
		echo json_encode(array("statusCode"=>201));
	}
	mysqli_close($link);
?>