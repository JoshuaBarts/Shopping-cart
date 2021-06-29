<!-- SERVER SIDE VALIDATION -->

<?php 
    include('connection.php');
    $errors = array(); //push errors here to display
    $firstName = "";
    $lastName = "";
    $address = "";
    $email = "";
    $password = "";
    $SESSION['logged-in'] = FALSE;
    //if register button is clicked
    if (isset($_POST['register'])) {
        //PROTECT FROM SQL INJECTIONS
        $firstName1 = mysqli_real_escape_string($link, $_POST['firstName']);
        $lastName1 = mysqli_real_escape_string($link, $_POST['lastName']);
        $address1 = mysqli_real_escape_string($link, $_POST['address']);
        $email1 = mysqli_real_escape_string($link, $_POST['email']);
        $password1 = mysqli_real_escape_string($link, $_POST['password']);
        $confirmPassword1 = mysqli_real_escape_string($link, $_POST['confirm-password']);
        $role_id = 2;

        //SANITIZE INPUTS BEFORE PUT IN DATABASE
        $firstName = filter_var($firstName1, FILTER_SANITIZE_STRING);
        $lastName = filter_var($lastName1, FILTER_SANITIZE_STRING);
        $address = filter_var($address1, FILTER_SANITIZE_STRING);
        $email = filter_var($email1, FILTER_SANITIZE_EMAIL);
        $password = filter_var($password1, FILTER_SANITIZE_STRING);
        $confirmPassword = filter_var($confirmPassword1, FILTER_SANITIZE_STRING);
    //error validations
        if (empty($firstName)) {
            array_push($errors, "Please put your First Name");
        }
        else if (!preg_match("/^[a-zA-Z]*$/", $firstName)) { //check if only contains letters
            array_push($errors, "Invalid First Name!");
        }
        if (empty($lastName)) {
            array_push($errors, "Please put your Last Name");
        }
        else if (!preg_match("/^[a-zA-Z]*$/", $lastName)) {
            array_push($errors, "Invalid Last Name!");
        }
        if (empty($address)) {
            array_push($errors, "Please put your Address"); //lots of possible address in the whole world 
        }
        if (empty($email)) {
            array_push($errors, "Please put your Email");
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //from php email validation
            array_push($errors, "Invalid Email!");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
        else if (strlen($password) < 4) {
            array_push($errors, "Password should be minimum of 4 characters"); //password should be min 4 characters
        }
        if ($password !== $confirmPassword) {
            array_push($errors, "Passwords did not match.");
        }

        $emailcheck = "SELECT email FROM customer WHERE email='$email'"; //check if email already exists
        $email_result = mysqli_query($link, $emailcheck);
        if ($email_result) {
            $row = mysqli_num_rows($email_result);

            if ($row >= 1) {
                array_push($errors, "Email already exist!");
            }
        }

        if (count($errors) == 0) {
            //hash the password before inserted into database
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customer (first_name, last_name, address, email, client_password, create_date, recent_login, role_id, privacy)
            VALUES ('$firstName', '$lastName', '$address', '$email', '$passwordHash', now(), now(), $role_id, 1)";

            //perform insert into customer table
            mysqli_query($link, $sql);

            //create sessions
            
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['address'] = $address;
            $_SESSION['email'] = $email;
            $_SESSION['success'] = 'You are logged in as'. $email;
            $_SESSION['logged-in'] = TRUE;
            $_SESSION['role'] = 'Customer';
            //redirect to index.php if successfull
            header('Location: login.php');
            
        }
    }
    session_destroy();
    
        //destroy session when logged out
        if (isset($_GET['logout'])) {
            session_destroy();
            unset($_SESSION['email']);
            header('location: index.php');
        }
        
?>