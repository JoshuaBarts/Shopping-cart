<?php include('connection.php'); ?>

<?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //update qty

        $quantity = mysqli_real_escape_string($link, trim(strip_tags($_POST['qty'])));
        $id = mysqli_real_escape_string($link, trim(strip_tags($_POST['id'])));
        $query = "UPDATE cart SET quantity = '$quantity' WHERE product_id = '$id'";

        if (mysqli_query($link, $query)) {
            echo "<script>console.log('Success');</script>";
        }
        header('location: cart.php');
        ?>
        <script>
            $('#quantity').focus();
        </script>
        <?php
        
    }
?>