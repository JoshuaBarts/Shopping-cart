<?php
include('header.php');
include('connection.php');
?>

<style>
/******************************************
#Quantity style
*****************************************/
#quantity {
  width: 23%;
  border: 1px solid black;
  border-radius: 12px;
  padding: 9px;
  text-align: center;
}
</style>

<div style="width:100%, display: table; margin: 2%;">
	<div style="display: table-row;">
		<div style="width:80%; display: table-cell; border: 1px solid;
  padding: 0 5% 0 5%;
  box-shadow: 10px 10px;">

			<h1 style="margin-bottom: 50px;">Shopping Cart Details</h1>
			<table class="table">
				<thead>
					<tr>
						
						<th id="delete-all"><a href="cart.php?action=delete-all"><button class="btn btn-danger">CLEAR ALL</button></a></th>
						<th>Product Image</th>
						<th>Product Name</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Total</th>
					</tr>
				</thead>
			
			<tbody>
				<?php
				
					$customer = $_SESSION['id'];
					$show_cart = "SELECT customer_id, product_name, product_description, product_price, product_id, quantity, product_image FROM product p INNER JOIN cart c USING(product_id) INNER JOIN customer g USING (customer_id) WHERE c.customer_id = '$customer'";
					$insert_result = mysqli_query($link,$show_cart);
					$cart = "SELECT quantity FROM cart WHERE customer_id = '$customer'";
					$cart_quantity = mysqli_query($link, $cart);
					$quantity = mysqli_fetch_array($cart_quantity);
					
					$sum = 0;
					$count = 0;
					if (!$insert_result)   {
						print "<h3>Select Failed from test_table ==> " . $show_cart . "<BR></h3>";
					}
					else {
						while($row = mysqli_fetch_array($insert_result)) {
							
							?>
							<tr>
								<td><a href="cart.php?action=delete&prod-id=<?php echo $row['product_id']; ?>"><button class="btn btn-danger">X</button></a></td>
								
								<td><img src="<?php echo $row['product_image'];?>" alt="<?php echo $row['product_name'];?>" width="128" height="128"></td>
								<td><?php echo $row['product_name'];?></td>
								<td><?php echo "$". $row['product_price'];?></td>
								<td>
								<form method="POST" id="form" action="edit_cart.php">		
									<input type="number" id="quantity" name="qty" value="<?php echo $row['quantity']?>" min="1">
									<input type="hidden" id="pid" name="id" value="<?php echo $row['product_id'];?>">
									<input type="submit" id="save" class="btn btn-success" value="Save Item">
									<input type="hidden" id="price" name="price" value="<?php echo $row['product_price'];?>">
								</form>	
								</td>
								<td><?php echo "$" . number_format($row['product_price'] * $row['quantity'], 2); ?></td>
							</tr>
							
							<?php
							$sum = $sum + ($row['product_price'] * $row['quantity']);
							$count++;
							?>
							
							<!-- shows CLEAR if something is in the cart ---->
							<script>$('#delete-all').show();</script>
							<?php

						}
						if ($count == 0){
							?>
							<!----- hides clear if cart is empty ---->
							<script>$('#delete-all').hide();</script>
							<?php
							echo "<tfoot>
							<tr><td></td><td><h5>Your Cart is empty. Go shopping now!</h5></td></tr></tfoot>";
			
							
						}

						
						
					}

					/**** CART DELETE INDIVIDUALLY *****/
					if (isset($_GET['action']) && $_GET['action'] == 'delete') {
						//delete record for product id
						$id = mysqli_real_escape_string($link, trim(strip_tags($_GET['prod-id'])));

						//query deletion
						$deleteSQL = "DELETE FROM cart WHERE product_id = '$id'";
						$result = mysqli_query($link, $deleteSQL);

						//tell user product has been removed and update page
						if ($result) {
							echo "<script>window.alert('Product has been removed.');
							window.location.href='cart.php';
							</script>";
						}
						
					}

					/*****DELETE ALL ITEMS IN CART *********/
					if (isset($_GET['action']) && $_GET['action'] == 'delete-all') {
						//delete all records
						$deleteAll = "DELETE FROM cart WHERE customer_id = '$customer'";

						if (mysqli_query($link, $deleteAll)) {
							echo "<script>window.alert('All products have been removed.');
							window.location.href='cart.php';
							</script>";
						}
						
					}
			?>
			</tbody>
			
			</table>
				<!-- Display Subtotal & checkout button -->
				<hr>
			<?php
				echo "<p style='float: right; margin-right: 20px;'><b>SUBTOTAL: $$sum</b></p>";
			?>
			<br>
			<br>
			<form action="checkout.php" method="POST">
				<input type="submit" class="slide-hover-left-1" value="CHECK OUT" style="float:right;" id="add-btn" <?php if ($count ==0) echo "disabled"?>>
				<input type="hidden" name="total" value="<?php echo $sum*100;?>"/>
				<input type="hidden" name="customer_id" value="<?php echo $customer;?>"/>
			</form>
		</div>
		<div style="display: table-cell; padding: 0 1% 0 1%;">
		<!-- <div style="border: 1px solid;height:130px;padding-right:14%;" class="text-center">
		<?php

			//echo "<p style='float: right;'>Total of all products: $$sum</p>";
		?>
		<form action="checkout.php" method="POST">
			<input type="submit" class="slide-hover-left-1" value="CHECKOUT" style="float:right;" id="add-btn" <?php if ($count ==0) echo "disabled"?>>
			<input type="hidden" name="total" value="<?php //echo $sum*100;?>"/>
			<input type="hidden" name="customer_id" value="<?php //echo $customer;?>"/>
		</form> -->

		
			</div>
			<div>
			<h4 style="text-align: center; color: red;">Wait! Check Out These Products</h4>
			<?php
				include ('connection.php');
				$countp = 0;
				$pro1 = rand(1,10);
				$pro2 = rand(11,30);
				$pro3 = rand(31,45);
				$sql = "select product_id, product_name, product_description, product_price, product_image, category_name from product p INNER JOIN category c USING (category_id) Where product_id = $pro1 OR product_id = $pro2 OR product_id = $pro3;";
				if(isset($_POST['category'])) { // if user choose a category, add the choosen category to SQL query to search
					$category = $_POST['category'];
					if ( $category != 'Category' && $category != 'All'){ // if the choosen value of dropdown-option-- is not 'category' or 'all' , display the products by the choosen category
						$sql = 'select product_id, product_name, product_description, product_price, product_image, category_name from product p INNER JOIN category c USING (category_id) WHERE UPPER(category_name) LIKE "%' . $category . '%";';
					} else {
						$sql = 'select product_id, product_name, product_description, product_price, product_image, category_name from product p INNER JOIN category c USING (category_id);';
					}
				}
				if (isset($_GET['search-item'])){ //if user want to search for product name
					$search = $_GET['search-item']; // add user input to SQL query to search
					$sql = 'select product_id, product_name, product_description, product_price, product_image, category_name from product p INNER JOIN category c USING (category_id) WHERE UPPER(product_name) LIKE "%' . $search . '%";';
				}
				
				$result = mysqli_query($link,$sql);
				$searchResult = 0; //set to 0 for search result item
				
				if (!$result)   {
					print "<h3>Select Failed from test_table ==> " . $sql . "<BR></h3>";
				}
				while ($row = mysqli_fetch_array($result)) {
				
					?>
						<!-- display products -->
						<!--
						<form method="POST" action="index.php" class="col-md-3" style="display:inline-block">
							<div class="item-list" style="border:1px solid #333; background-color:#f1f1f1; border-radius: 5px; padding:10px; width:100%; overflow:hidden; heigth:100%">
								<div class="img-thumbnail" style="text-align:center;"><a href="preview.php?id=<?php //echo $row['product_id']; ?>"><img src="<?php //echo $row['product_image']; ?>" class="img-responsive" width='150' height='200'></a><br></div>
								<div style="padding:5px;"><h4 class="text-info" style="text-align:center; font-size:15px;"><?php //echo $row['product_name']; ?></h4></div><hr>
								<h4 class="text-danger" style="text-align:center;">$ <?php //echo $row['product_price']; ?></h4>
								<div style="text-align:center;"><button type='button'class='btn btn-outline-danger' style="text-align:center;">Add To Cart</button></div>
							</div>
						</form>
						-->
						<form method="POST" action="preview.php?id=<?php echo $row['product_id'];?>" class="col-md-12" style="display:inline-block;">
							<div class="container-fluid" style="border:1px solid black; padding:0; position:relative; width:100%; border-radius:10px;">
								<div class="img-fluid" style="text-align:center;"><a href="preview.php?id=<?php echo $row['product_id']; ?>"><img src="<?php echo $row['product_image']; ?>" class="img-responsive" width='200' height='225'></a><br></div>
								<?php if(isset($_SESSION['id'])): ?>
								<div style="position:absolute; width:100%; text-align:center; bottom:0px;"><input type="submit" class="btn btn-danger" style="width:100%; color:white; text-align:center;" value="Details           $<?php echo $row['product_price']; ?>" <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") echo "disabled";?>></div>
								<?php else: ?>
								<script>function showAlert() {alert('You need to login to continue');}</script>
								<div style="position:absolute; width:100%; text-align:center; bottom:0px;"><input type="button" class="btn btn-danger" style="width:100%; color:white; text-align:center;" value="Details           $<?php echo $row['product_price']; ?>" <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") echo "disabled";?> onclick="showAlert();"></div>
								<?php endif ?>
								<div style="position:absolute; width:100%; background: rgba(0, 0, 17, 0.5); text-align:center;font-size:17px; bottom:37px;"><span style="color:white;"><?php echo $row['product_name']?></span></div>
							</div>
						</form>
						
					<?php
					$searchResult++; //increment search result if found
				}
			?>
			<hr>
			<form action="index.php" method="POST">
			<input type="submit" class="slide-hover-left-1" value="Countinue Shopping" style="text-align:center; background-image: linear-gradient(0, #0739df, #0739df);" id="add-btn" <?php if ($count ==0) echo "disabled"?>>
		</form>
			</div>
		</div>
	</div>
</div>
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:33%">
    1. Check Out
  </div>
</div>
<script>

		$('.hidden').hide();

		$('#save').click(function(e){
			e.preventDefault();
			$('#form').submit();
			$('#submit-form').submit();
			var quantity = $(this).closest('form').find('input[name=qty]').val();
			var pid = $(this).closest('form').find('input[name=id]').val();
			console.log(quantity);
			$.ajax({
				type: 'post',
				url: 'edit_cart.php',
				data: $('#form').serialize(),
				cache: false,
				success: function() {
					console.log('Successful.');
					
				},
				error: function() {
					console.log('There is an error.');
				}
			});
		});

</script>
<?php
include('footer.php');
?>
