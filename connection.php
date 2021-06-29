<?php


$server = 'localhost';
$user = 'root';
$pswd = '';
$db='jmb';

$link = mysqli_connect($server,$user,$pswd,$db);

$admin_pass = 'admin';
$admin_hash = password_hash($admin_pass, PASSWORD_DEFAULT);
$admin_account = "INSERT INTO customer VALUES (first_name, last_name, address, email, client_password, create_date, recent_login, role_id)
VALUES ('admin', 'admin', 'Victoria Road', 'admin123@gmail.com', '$admin_hash', now(), now(), 1)";


if (!$link) {
    die ('MySQL Error:' . mysqli_connect_error());
}

?>
