<?php
include('connection.php');
include('header.php');
$cusID = $_SESSION['id'];
$receipt_id = mysqli_real_escape_string($link, trim(strip_tags($_GET['id'])));
$dof = mysqli_real_escape_string($link, trim(strip_tags($_GET['dof'])));
$total = mysqli_real_escape_string($link, trim(strip_tags($_GET['total'])));
$fname = mysqli_real_escape_string($link, trim(strip_tags($_GET['fname'])));
$lname = mysqli_real_escape_string($link, trim(strip_tags($_GET['lname'])));
$address = mysqli_real_escape_string($link, trim(strip_tags($_GET['address'])));
$email = mysqli_real_escape_string($link, trim(strip_tags($_GET['email'])));
$history = "SELECT o.product_id,p.product_name,o.total_price,o.quantity, p.product_image,r.customer_id,p.product_price,r.order_time,r.order_status, r.receipt_id FROM product p INNER JOIN order_detail o USING(product_id) INNER JOIN receipt r USING(receipt_id) WHERE customer_id = '$cusID' AND receipt_id = '$receipt_id';";
$link_history = mysqli_query($link,$history);

?>

<div class="container" style="color:#6c8b8e; letter-spacing: 1.2px;">
<!--This page will display items according to the receipt No. -->
<h2 class="name">JMB</h2>
<p class="greeting"> Thank you for your order! </p>
<h3>Order Details</h3>
<hr>
<h6>Receipt No : <?php echo $receipt_id; ?></h6>
<h6 >Shipped To: <?php echo $fname . ' ' . $lname; ?></h6>
<h6 style="margin-left: 102px;"><?php echo $address; ?></h6>
<h6 style="margin-left: 102px;"><?php echo $email; ?></h6>
<h6>Order date: <?php echo $dof ?></h6>
<br>

<h3 >Products In Your Order</h3>
    <table class="table" style="color:#6c8b8e;">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                
            </tr>
        </thead>
    
    <tbody>
<?php
	if (isset($_GET['action']) && $_GET['action'] == 'view-order') {
		while ($row = mysqli_fetch_array($link_history)) {
			?>
                <tr style="font-size: 18px; letter-spacing: 1.2px">
                    
                    <td><img src="<?php echo $row['product_image'];?>" alt="<?php echo $row['product_name'];?>" width="128" height="128"></td>
                    <td ><?php echo $row['product_name'];?></td>
                    <td><?php echo "$". $row['product_price'];?></td>
                    <td><?php echo $row['quantity'];?></td>
                    <td><?php echo "$". $row['total_price']?></td>
                    
                </tr>
			<?php
		}
		
	}

?>
    </tbody>
    </table>
	<hr>
	<h5 style="margin-bottom: 50px;margin-top: 50px;">Total Amount: <span style="color:red; margin-bottom: 20px; font-weight: bold;"><?php echo $total; ?></span></h5>
</div>
<?php include('footer.php');?>
