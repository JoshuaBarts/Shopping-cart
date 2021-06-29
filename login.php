<?php
include('header.php');
include('connection.php');
mysqli_query($link, $admin_account);
//ADDED PASSWORD HASHING
if (isset($_POST['LOGIN'])) {
	
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$password = $_POST['password'];
	if (empty($_POST['email']) || empty($_POST['password'])) {
		$emailError = 'Email and Password should be filled';
	} else {
		$user_query = "SELECT customer_id, email, client_password, privacy, role_name FROM customer c INNER JOIN role r USING (role_id) WHERE email='$email';";
			$login_result = mysqli_query($link,$user_query);
			if (!$login_result)   {
					$emailInvalid = '<p style="color: red">We could not find this account.</p>';
			} else {
				
				if (isset($email) && isset($password)) {
					while ($row_user = mysqli_fetch_array($login_result)){
						//verify if password matches the hash
						$pwdHash = "SELECT client_password FROM customer WHERE email='$email'";
						$hash = mysqli_query($link, $pwdHash);
						$passrow = mysqli_fetch_array($hash);
						$salt = $passrow['client_password'];

						//ADMIN LOGIN
						if ($row_user['role_name'] == 'Admin' && password_verify($password, $admin_hash)) {
							
							$_SESSION['email'] = $_POST['email'];
							$_SESSION['role'] = $row_user['role_name'];
							$_SESSION['privacy'] = $row_user['privacy'];
							header('Location:index.php');
							exit();
						}

						//CUSTOMER LOGIN
						if ($email == $row_user['email'] && password_verify($password, $salt)){
							$_SESSION['email'] = $_POST['email'];
							$_SESSION['role'] = $row_user['role_name'];
							$_SESSION['id'] = $row_user['customer_id'];
							$_SESSION['privacy'] = $row_user['privacy'];
							$userID = $row_user['customer_id'];
							header('Location:index.php');
							exit();
							
						}
						else {
							$emailInvalid = '<p style="color: red">Your email or password is invalid. Please Try Again!</p>';
						}
												
					}
				}
				
			}
			$emailInvalid = '<p style="color: red">Your email or password is invalid. Please Try Again!</p>';
			//echo $pwdHash . "<br>";	 
			//echo $salt ."<br>";
			//echo $passrow;
	}

	

}
?>
<div class="container" style="margin-bottom:5%">
	<div class="row justify-content-center">
		<div class="col-md-5 text-center" style="display:inline-block;">
			<img src="img/login-banner2.jpg" class="img-responsive" width='100%' height='350px'>
			<h5 style="margin: 5% 0 5% 0; text-align: left;">Connect with us to get the latest technology, video games 
	stuffs, and more!</h5>
			<button type="button" class="btn btn-outline-secondary" style="width: 100%; height: 15%; font-size:1.3em">WELCOME TO JMB WEBSITE</button>
		</div>
		<div class="col-md-7 text-center" style="display:inline-block; padding: 2%; border: 2px solid gray; background-color: #e6f2ff;">
			<div id="login-div" style="padding-bottom: 5%;">
			<p>Welcome back!</p>
			<h3>Returning Customers</h3>
			<form method="POST" style="boder-bottom: 2px solid black;" action="">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon3">Email </span>
					</div>
					<input size="50" name="email" type="email" class="form-control" style="width:25%;" aria-describedby="basic-addon3" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>
					<?php if (isset($emailError)) echo $emailError ?>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon3">Password </span>
					</div>
					<input size="50" type="password" name="password" class="form-control" style="width:25%;" aria-describedby="basic-addon3" value="" required>
					<?php if (isset($emailError)) echo $emailError ?>
				</div>
				<input type="submit" name="LOGIN" value="LOGIN" class="btn btn-primary" style="width: 100%; height:10%;"><br>
				<?php if (isset($emailInvalid)) echo $emailInvalid ?>
				<P>
			</form>
			</div>
			<div style="border-top: 2px solid black; padding-top: 5%; padding-bottom: 12.5%">
				<p>Are you newcomer? Would you like to become a part of the gaming community?</p>
				<button onclick="location.href='register.php';" class="btn btn-success" style="width: 60%; height:15%; font-size: 1.3em" type="button">REGISTER NOW!</button>
			</div>
		</div>
	</div>
</div>
<?php
include('footer.php');
?>
</body>
</html>