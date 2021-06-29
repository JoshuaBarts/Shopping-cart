<?php
include ('header.php');
?>
<div class="container" style="color:#6c8b8e; letter-spacing: 1.2px;border: 1px solid;
  padding: 10px;
  box-shadow: 10px 15px #888888; margin-bottom: 3%">
    <!-- STRIPE FORM CHECKOUT -->
			<?php require_once('./config.php'); 
			
			$total = $_POST['total'];
			?>


            <?php 
                /** GET Customer Details for Receipt */
                include('connection.php');
                $customer_id = $_POST['customer_id'];
                $sql = "SELECT first_name, last_name, address, email FROM customer where customer_id = '$customer_id'";
                $result = mysqli_query($link, $sql);
                $sum = 0;
                while ($row = mysqli_fetch_array($result)) {
                    ?>
					<h1 style="text-align: center;">Check Our Information</h1>
                    <h2>Your Details</h2>
                    <hr>
                    <h6>Shipping To:</h6>
                    <h6 style="color: #6f8d90;"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></h6>
                    <h6 style="color: #6f8d90;"><?php echo $row['address']; ?></h6>
                    <h6 style="color: #6f8d90;"><?php echo $row['email'];?></h6>
					<br>
                    <h2>Your Order Sumary</h2>
                    <table class="table" style="color:#6c8b8e; letter-spacing: 1.2px;">
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
                            $order = "SELECT customer_id, product_name, product_description, product_price, product_id, quantity, product_image FROM product p INNER JOIN cart c USING(product_id) INNER JOIN customer g USING (customer_id) WHERE c.customer_id = '$customer_id'";
                            $order_result = mysqli_query($link, $order);
                            while ($row = mysqli_fetch_array($order_result)) {
                                
                                ?>
                                <tr>
                                    <td><img src="<?php echo $row['product_image'];?>" alt="<?php echo $row['product_name'];?>" width="128" height="128"></td>
                                    <td><?php echo $row['product_name'];?></td>
                                    <td><?php echo "$". $row['product_price'];?></td>
                                    <td><?php echo $row['quantity'];?></td>
                                    <td style="font-weight: bold;"><?php echo "$" . number_format($row['product_price'] * $row['quantity'], 2); ?></td>
                                </tr>
                                
                                <?php
                                $sum = $sum + ($row['product_price'] * $row['quantity']);
                            }
                        ?>
                        
                    </tbody>
                    </table>
                    <hr>
                    <div class="container">
                        <div class="jumbotron">
                            <h4 style="font-weight: bold;">Total Amount: $<?php echo $sum;?></h4>
                            <div>
                            <!-- CAN REDIRECT TO RECEIIPT PAGE AFTER CHECKOUT! -->
                            <form action="charge.php" method="post">
                                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                            data-key="<?php echo $stripe['publishable_key']; ?>"
                                            data-description="Checkout JMB Shopping Cart"
                                            data-amount="<?php echo $total; ?>"
                                            data-locale="auto"
                                            data-currency="cad">
                                            </script>
                                    <input type="hidden" name="totalamt" value="<?php echo $total; ?>"/> 
                                    <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>"/> 
                                </form>
                            <!-- END OF STRIPE CHECKOUT -->
                            </div>
                        </div>
                    </div>

                    <?php
                }
            ?>
</div>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:66%">
    2. Make A Payment
  </div>
</div>
<?php
include ('footer.php');
?>
