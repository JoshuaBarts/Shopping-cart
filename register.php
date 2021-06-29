<?php
include('header.php');
?>
    <div class="container-re">
        <div class="register-div">
            <form method="POST" action="register.php">
            <h3 id=signup >Sign Up</h3>
            
            <?php
				include ('error.php'); 
            ?>
            
            <div class="form-group">
                <input id="re-input" class="form-control" type="text" placeholder="First Name *" name="firstName" value="<?php if(isset($_POST['firstName'])){echo $_POST['firstName'];} ?>" required>
            </div>
            <div class="form-group">
                <input id="re-input" class="form-control" type="text" placeholder="Last Name *" name="lastName" value="<?php if(isset($_POST['lastName'])){echo $_POST['lastName'];} ?>" required>
            </div>
            <div class="form-group">
                <input id="re-input" class="form-control" type="text" placeholder="Address *" name="address" value="<?php if(isset($_POST['address'])){echo $_POST['address'];} ?>" required>
            </div>
            <div class="form-group">
                <input id="re-input" class="form-control" type="text" placeholder="Email *" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>" required>
            </div>
            <div class="form-group">
                <input id="re-input" class="form-control"  type="password" placeholder="Password *" name="password" required>
            </div>
            <div class="form-group">
                <input id="re-input" class="form-control" type="password" placeholder="Confirm Password *" name="confirm-password">
            </div>
            <div class="form-group">
                <input type="checkbox" id="checkbox-privacy" required>
    			<label id="checkbox-text" for="checkbox-privacy">I agree with <a href="privacy-policy.php" style="color: white; font-weight: bold;">Terms and Conditions</a></label>
            </div>
            <div class="wrap">
                <input type="submit" class="slide-hover-left-1" value="REGISTER" name="register">
                
            </div>
            
        </form>
        </div>
        
    </div>

    <?php include('footer.php'); ?>

</body>
</html>

