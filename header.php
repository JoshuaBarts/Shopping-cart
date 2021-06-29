<?php session_start() ?>
<DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title> JMB Website </title>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="icon" type="image/png" sizes="32x32" href="css/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="css/favicon-16x16.png">
		<link rel="manifest" href="css/site.webmanifest">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<?php if (isset($_SESSION['id'])): ?>
		<script>
$(document).ready(function() {
	$('#policy-div').hide();
	
});
function GetAccept(answer){
		var answer = document.getElementById("AcceptP").value;
		changePolicy(answer);
		$("#policy-div").fadeOut(2000);
	};	
function GetNAccept(){
		var answer = document.getElementById("NAcceptP").value;
		changePolicy(answer);
		$("#policy-div").fadeOut(2000);
	};
function changePolicy(answer){
		var session = <?php echo $_SESSION['id'] ?>;
			$.ajax({
				url: 'changepolicy.php',
				type: 'POST',
				data: {
					answer: answer,
					session: session
				},
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						alert('YOUR PRIVACY POLICY AGREEMENT HAS BEEN CHANGED! YOU HAVE TO LOGIN AGAIN TO APPLY NEW CHANGES');
						window.location.href = "logout.php";
					}
					else if(dataResult.statusCode==201){
					   alert('Error occured !');
					}
					
				}
			});
	};
function showPolicy(){
	$('#policy-div').show();
}
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
	</script>
<?php endif ?>
	</head>
	<body>
	<?php if (isset($_SESSION['id'])): ?>
	<div id="policy-div" class="fixed text-center" style="z-index: 100; display:none;">
		<h5>CHANGE <a href="privacy-policy.php">PRIVACY POLICY</a></h5>
		<button type="button" value="1" id="AcceptP" onclick="GetAccept()" class="btn btn-success btn-sm" style="margin:5px;">Accept The Policy</button><br>
		<button type="button" value="0" id="NAcceptP"onclick="GetNAccept()" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Declining the Policy will not allow you to add items in your cart.">Decline The Policy</button>
	</div>
	
	<?php endif ?>
		<header id="header-up">
			<div class="container">
				<div id="logo" class="pull-left">
					<a href="index.php"><img src="css/logo.png" alt="" height="50"></a>
				</div>
			</div>
		<section id=navbar>
			<div class="topnav">
				<a class="active" href="index.php">Home</a>
				<?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin'): ?>
				<a href="cart.php"><i class="fa fa-shopping-cart"></i> Cart</a>
				<a href="orderhistory.php"><i class="bi bi-bag-check-fill"></i>Order History</a>
				<?php else: ?> <!---Show alert when customer is not logging in yet--->
				
				<script> function show_alert() {
					alert('YOU NEED TO LOGIN AS CUSTOMER IN ORDER TO SEE YOUR CART');
				}
				</script>
				<!---Move Customer to Login page--->
				<a href="login.php" onclick="show_alert()"><i class="fa fa-shopping-cart"></i> Cart</a>
				
				<?php endif ?>
				<!-- ONly show add product if user is ADMIN -->
				<?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin'):  ?>

					<a href="addproduct.php">Add New Product</a>
				<?php endif ?>
				
				<div class="topnav-right">
				<!-- Display whether user is logged in or logged out -->
					<?php if (isset($_SESSION['email'])): ?>
					<a id="login-user" onclick="showPolicy()" style="cursor: pointer;">Logged in as <?php echo $_SESSION['email'] ?>(<?php echo $_SESSION['role']; ?>)(<?php if ($_SESSION['privacy'] == 1){ echo "POLICY";} else{ echo "NO POLICY";} ?>)</a>
					<a href="logout.php">Logout</a>
					<?php else: ?>
						<a href="login.php">Log In</a>
						<a href="register.php">Sign Up</a>
					<?php endif ?>
				</div>
			</div>
		</section>
		
		<section id="slogan">
			<div id="header-image">
				<p class="text">Enhance Your Play</p>
			</div>
		</section>
		</header><!-- End Header-up -->
