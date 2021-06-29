<?php
include ('header.php');
?>

<div id="container-category">
	<div id="form-filter">
		<!--------------------------------------CATEGORY FORM---------------------------------------------------->
		<div id="category-div">
		<form id="s" method="post" class="box" action="index.php">
			<select name="category"  onchange='this.form.submit()'>
				<option value="Category" selected disabled hidden>Category</option>
				<!--this PHP to show all categories name in Database to dropdown-option-->
				<?php
					include ('connection.php');
					$sql = 'SELECT category_name FROM category;';
					$result = mysqli_query($link,$sql);
					if (!$result)   {
						print "<h3>Select Failed from test_table ==> " . $sql . "<BR></h3>";
									}
					else {
						//this is going to print all categories to SELECT tag
						if(isset($_POST['category'])) {
							print "<option value='All'>All</option>";
							while ($row = mysqli_fetch_array($result)) {
								$selected = '';
								// Display the choosen category to the user
								if ( $_POST['category'] == $row['category_name']){
									$selected = 'selected';
								}
								print "<option value='" . $row['category_name'] . "'" . $selected . ">" . $row['category_name'] ."</option>"; //All 'selected' atribute to choosen option tag
								$selected = '';
							}
						} else { // if there is no choosen tag then display the default option
							print "<option value='All'>All</option>";
							while ($row = mysqli_fetch_array($result)) {
								print "<option value=" . $row['category_name'] . ">" . $row['category_name'] ."</option>";
							}
						}
					} 
				?>
			</select> 
		</form>
		</div>
		
		<!-----------------------------------SEARCH FORM--------------------------------------------->
		<div id="search-div" style="width:50%; float:right;">
			<form action="index.php" method="get" id="search-form">
				<button id="search-button"class="btn btn-outline-danger" type="submit">
				<i class="fa fa-search"></i>
				</button>
				<input type="text" id="search-input" name="search-item" placeholder="Search here">						
			</form>
		</div>
	</div>

	<!-----------------------------------------------------DIV THAT DISPLAY PRODUCT----------------------------------------------------->
	<div class="product-list">
		<ul class="list">
			<!---------------------------------------------------------------PHP FOR PRODUCT VIEW--------------------------------------------------------->
			<?php
				include ('connection.php');
				
				$sql = 'select product_id, product_name, product_description, product_price, product_image, category_name from product p INNER JOIN category c USING (category_id);';
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
						<form method="POST" action="preview.php?id=<?php echo $row['product_id'];?>" class="col-md-3" style="display:inline-block;">
							<div class="container-fluid" style="border:1px solid black; padding:0; position:relative; width:100%; border-radius:10px;">
								<div class="img-fluid" style="text-align:center;"><a href="preview.php?id=<?php echo $row['product_id']; ?>"><img src="<?php echo $row['product_image']; ?>" class="img-responsive" width='200' height='225'></a><br></div>
								<?php if(isset($_SESSION['id'])): ?>
								<div style="position:absolute; width:100%; text-align:center; bottom:0px;"><input type="submit" class="btn btn-danger" style="width:100%; color:white; text-align:center;" value="BUY NOW           $<?php echo $row['product_price']; ?>" <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") echo "disabled";?>></div>
								<?php else: ?>
								<script>function showAlert() {alert('You need to login to continue');}</script>
								<div style="position:absolute; width:100%; text-align:center; bottom:0px;"><input type="button" class="btn btn-danger" style="width:100%; color:white; text-align:center;" value="BUY NOW           $<?php echo $row['product_price']; ?>" <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") echo "disabled";?> onclick="showAlert();"></div>
								<?php endif ?>
								<div style="position:absolute; width:100%; background: rgba(0, 0, 17, 0.5); text-align:center;font-size:17px; bottom:37px;"><span style="color:white;"><?php echo $row['product_name']?></span></div>
							</div>
						</form>
						
					<?php
					$searchResult++; //increment search result if found
				}
				if ($searchResult == 0) {
					echo "<h3>Hmmm, we're not getting any results. Please try again.</h3>"; //no result found
				}
				?>	
		</ul>
	</div>
</div>

<?php
include ('footer.php');
?> 
	
