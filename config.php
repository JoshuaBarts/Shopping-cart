<?php
require_once('vendor/autoload.php');

$stripe = [
  "secret_key"      => "sk_test_51J2gisHDlBea6Mgvc0NegPgkRvHTeWZZ0Nhk19TW6o6UVTf0PIK0hj5wdlulRddnPXv0JTsyCc7j7tGA1A5TMIOy00JyY6YJgZ",
  "publishable_key" => "pk_test_51J2gisHDlBea6MgvZcUSSkUzY2x1FahQsuq4edc7IqSDCLmkHfx5w3hxEDYsOSrV4TEtwEAY3H3DJ0K2L6YHwsWq00qnsrhane",
];

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>