<?php include('header.php'); ?>
<div>
<?php 
    include('connection.php');
    if (isset($_GET['id'])) {
        $item_id = $_GET['id'];
		if(isset($_SESSION['id'])){
			$cus_id = $_SESSION['id'];
		} else {
			$cus_id = "NA";
		}
        $sql = "SELECT * FROM product WHERE product_id = $item_id";
        $result = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_array($result)) {
            ?>
        <div class="container">
		<form id="fupForm" name="form1" method="post">
			<div class="jumbotron bg-dark" style="position:relative; background:url('img/game.jpg');background-size:contain;">
			<div style="margin:0 -32px;background-color: rgba(128, 22, 0, 0.5);">
			<div style="margin-bottom:50px;">
				<h1 style="color:white;margin-left:30px;"><?php echo $row['product_name']; ?></h1>
				<input type="hidden" name="name" value="<?php echo $row['product_name']; ?>">
				<input type="hidden" name="price" value="<?php echo $row['product_price']; ?>">
				<input type="hidden" name="quantity" value="1">
			<div style="position:absolute; right:40px; top:62px;"><h1 style="text-align:right;color:white;">$<?php echo $row['product_price']; ?></h1></div>
		
			</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
				<div id="demo" style="position:relative;">
					 
					<input type="button" name="save" style="right:-106%;; color:white; position:absolute; width:100%;" class="btn btn-danger" value="ADD TO CART" id="butsave">

					
				</div>
				<div><image class="img-responsive" width='500' height='500' src="<?php echo $row['product_image']; ?>"></div>
				</div>
				<div class="col-sm-6" style="margin-top:110px;">
					<div><p style="color:white;"><?php echo $row['product_description']; ?></p></div>
				</div>
			</div>
			</div>
		</form>
        </div>
            <?php
        }
        
    }
    ?>
			
</div>  
<?php if (isset($_SESSION['privacy']) && $_SESSION['privacy'] == 1): ?>
<script>
$(document).ready(function() {
	$('#butsave').on('click', function() {
		$('#butsave').attr('disabled', 'disabled');
		var NA = 'NA';
		var idpro = <?php echo $_GET['id'] ?>;
		var idcus = <?php echo $cus_id?>;
		if(idpro!=' ' && idcus!='NA'){
			$.ajax({
				url: 'insert.php',
				type: 'POST',
				data: {
					idpro: idpro,
					idcus: idcus				
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#butsave').removeAttr('disabled');
						alert('NEW ITEM HAS BEEN ADDED TO YOUR CART!');						
					}
					else if(dataResult.statusCode==201){
					   alert('Error occured !');
					}
					
				}
			});
		}
		else{
			alert('You have to login to add item to cart!');
		}
	});
});
</script>
<?php elseif (isset($_SESSION['privacy']) && $_SESSION['privacy'] == 0): ?>
<script>
$(document).ready(function() {
	$('#butsave').on('click', function() {
		
			alert('YOU HAVE TO ACCEPT PRIVACY POLICY TO ADD ITEM TO CART!');
	});
});
</script>
<?php else: ?>
<script>
$(document).ready(function() {
	$('#butsave').on('click', function() {
		
			alert('YOU HAVE TO LOGIN TO ADD ITEM TO CART!');
	});
});
</script>
<?php endif ?>
<?php include('footer.php'); ?>
