<?php
	include 'connection.php';
	$idpro = $_POST['idpro'];
	$idcus = $_POST['idcus'];
	$sql_insert = "INSERT INTO cart VALUES ($idcus,$idpro,1);";
	$checkP = "SELECT * FROM CART";
	$checkP_result = mysqli_query($link,$checkP);
	if (!$checkP_result)   {
		print "<h3>Select Failed from test_table ==> " . $checkP . "<BR></h3>";
	} else {
		while ($row_item = mysqli_fetch_array($checkP_result)) { /*---------CHECK IF ITEM IS ALREADY IN CUSTOMER CART------------*/
				if ($row_item['customer_id'] == $idcus && $row_item['product_id'] == $idpro){ /*---------IF YES------------*/
				$new_quantity = ++$row_item['quantity'];
				$update_quantity = "UPDATE cart SET quantity = ".$new_quantity." WHERE customer_id = $idcus AND product_id = $idpro;";
				/*---------UPDATE QUANTITY------------*/
				if (mysqli_query($link, $update_quantity)) {
					echo json_encode(array("statusCode"=>200));
					exit();
				} 
				else {
					echo json_encode(array("statusCode"=>201));
					exit();
				}
										
			}
		}
	if (mysqli_query($link, $sql_insert)) {
		echo json_encode(array("statusCode"=>200));
		
	} 
	else {
		echo json_encode(array("statusCode"=>201));
	}
	mysqli_close($link);
	}
?>