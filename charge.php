<?php
include('header.php');

?>
<?php
  require_once('./config.php');
  
  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $totalamt = $_POST['totalamt'];
  $customer_id = $_POST['customer_id'];

  $customer = \Stripe\Customer::create([
      'email' => $email,
      'source'  => $token,
  ]);

  $charge = \Stripe\Charge::create([
      'customer' => $customer->id,
      'amount'   => $totalamt,
      'currency' => 'cad',
  ]);

  ?>
<!------------------------- THIS IS THANK YOU MESSAGE ------------------------------------------------->
  <div class="container" style="border: 1px solid;
  padding: 10px;
  box-shadow: 5px 10px #888888; margin-bottom: 5%;border-radius: 25px;">
    <img src="css/order-success.gif" alt="success" style="position:relative;left:40%;">
    <div  style="position:relative;text-align:center;">
      <h1>Thank you for purchasing with JMB!</h1><br>
      <h3>An Order receipt will be processed and your order will be on the way soon!</h3><br>
      <h4>You can view your Order history <a href="orderhistory.php">here</a>.</h4><br><br>


  <?php
  include('connection.php');
  $countOrders= "SELECT count(*) FROM receipt;";
  $ordersLink = mysqli_query($link, $countOrders);
  $count=0;
  $row = mysqli_fetch_row($ordersLink);
  $count = $row[0]; /**Get count(*) result */
  $count++;
  $insert_receipt="INSERT INTO receipt VALUES($count,$customer_id,now(),'Succeeded');";
  $insert_receiptLink = mysqli_query($link, $insert_receipt);
  $info = "SELECT customer_id, first_name, last_name, address, email, product_name, product_description, product_price, product_id, quantity, product_image, order_time FROM product p INNER JOIN cart c USING(product_id) INNER JOIN customer g USING (customer_id) INNER JOIN receipt USING(customer_id) WHERE c.customer_id = '$customer_id';";
  $result = mysqli_query($link, $info);
  $filename = 'receipt\\receipt_id' . $count . '.txt';
  while ($row = mysqli_fetch_array($result)) {
		
      $name = $row['first_name'] . ' ' . $row['last_name'];
      $address = $row['address'];
      $email = $row['email'];
      $products = $row['product_name'];
      $time = $row['order_time'];

      /** WRITE TO TXT FILE */
      
      $txt = "Thank you for Purchasing with JMB!
      
Here is your Order Receipt:
Date of Purchase: $time
----------------------------------
Cust_ID: $customer_id
Customer Name: $name
Shipping Address: $address
Email Address: $email
----------------------------------
Items:
";

      

  }
  file_put_contents($filename, $txt, FILE_APPEND | LOCK_EX);
  $sql = "SELECT product_name, product_price, quantity FROM product INNER JOIN cart USING(product_ID) INNER JOIN customer USING(customer_id) WHERE customer_id = '$customer_id'";
  $sqlResult = mysqli_query($link, $sql);
  $total = 0;

  while ($row = mysqli_fetch_array($sqlResult)) {
    
    $itemData = '
' . $row['product_name'] . '
Price: ' . $row['product_price'] . '
' . 'Quantity: '. $row['quantity'] . '
total: $'. number_format($row['product_price']*$row['quantity'],2) . '

';
    file_put_contents($filename, $itemData, FILE_APPEND | LOCK_EX);

    $total = $total + ($row['product_price']*$row['quantity']);
    
  }
  $grandTotal = 'Grand Total: $' . number_format($total,2) . '-------------------------------' . 
  '';

  
  file_put_contents($filename, $grandTotal, FILE_APPEND | LOCK_EX);
  
  $newline = '

  
  ';
  file_put_contents($filename, $newline, FILE_APPEND | LOCK_EX);
  
  
  /** CLEAR CART AFTER PURCHASE */
  
  
  


  /**INSERT INFORMATION TO HISTORY ORDER */
  $check_customer_cart = "SELECT c.customer_id, c.product_id, c.quantity, p.product_price FROM cart c INNER JOIN product p USING (product_id) WHERE customer_id = $customer_id;";
  $link_check = mysqli_query($link,$check_customer_cart);
  while ($row = mysqli_fetch_array($link_check)) {
    $total = $row['product_price']*$row['quantity'];
    $proid = $row['product_id'];
    $quantity = $row['quantity'];
    $insert_detail = "INSERT INTO order_detail VALUES ($count,$proid,$total,$quantity);";
    $insert_detailLink = mysqli_query($link,$insert_detail);
  }
  $clearCart = "DELETE FROM cart WHERE customer_id = '$customer_id'";
  mysqli_query($link, $clearCart);
    
?>
	  <h4>Or you can download your receipt here <a href="<?php echo $filename ?>" download>Receipt</a></h3>
      <hr>
      <h4>While you are waiting, check out more of the cool stuffs by clicking down below!</h4><br><br>
      <a href="index.php"><button class="btn btn-outline-info">Return Home</button></a><br><br><br>
    </div>
  </div>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:100%">
    3. Completed
  </div>
</div>
<?php
include('footer.php')
?>
