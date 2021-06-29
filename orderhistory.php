<?php
include('header.php');
?>
<div class="container" style="color:#6c8b8e;">
<h2>Order History</h2>
<br>
<table class="table" style="color:#6c8b8e;">
				<thead>
					<tr>
						<th>Order No.</th>
						<th>Order Date&Time</th>
						<th>Order Status</th>
						<th>Total</th>
						<th>Order Details</th>
					</tr>
				</thead>
			
			<tbody>

    <?php
    include('connection.php');
	$total = 0;
    $cusID = $_SESSION['id'];
    $receipt = "SELECT DISTINCT first_name, last_name, address, email, receipt_id, order_status, order_time, SUM(total_price), quantity, product_price FROM receipt INNER JOIN order_detail USING(receipt_id) INNER JOIN product USING (product_id) INNER JOIN customer USING (customer_id) WHERE customer_id = '$cusID' GROUP BY receipt_id ORDER BY order_time DESC;";
	$receipt_order = mysqli_query($link, $receipt);
	$history = "SELECT DISTINCT product_id,product_name,total_price,quantity, product_image,customer_id,product_price,order_time,order_status, receipt_id FROM product p INNER JOIN order_detail o USING(product_id) INNER JOIN receipt r USING(receipt_id) WHERE customer_id = $cusID GROUP BY receipt_id;";
    $link_history = mysqli_query($link,$history);
    while ($row = mysqli_fetch_array($receipt_order)){
        $sum = $row['SUM(total_price)'];
    	?>
			<tr style="color:#6c8b8e;">						
				<td><span style="font-weight:bold;"><?php echo $row['receipt_id'];?></span></td>
				<td><?php echo  $row['order_time'] ?></td>
				<td><span style="color:green; border:1px green; border-radius:10px;padding:5px;background:#c3ffc3;"><?php echo  $row['order_status'] ?>&#10004;</span></td>
				<td style="font-weight:bold;"><?php echo "$" . number_format($sum, 2); ?></td>
				<td><a href="vieworder.php?action=view-order&id=<?php echo $row['receipt_id'];?>&dof=<?php echo  $row['order_time'];?>&total=<?php echo "$" . number_format($sum, 2);?>&fname=<?php echo $row['first_name'];?>&lname=<?php echo $row['last_name'];?>&email=<?php echo $row['email'];?>&address=<?php echo $row['address'];?>">View Details</a><td>
			</tr>
    	<?php
	
    }
    	?>
</table>
</div>

<?php
include('footer.php');
?>
