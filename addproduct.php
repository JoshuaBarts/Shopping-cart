	<?php
	include('header.php');
	?>        
        <!-- Section add product-->
        <div class="container">
            <div class="jumbotron" style="background:url('img/bg.png'); background-size:contain;">
            
            <!-- Form for adding product -->
            
                <form method="POST" enctype="multipart/form-data" id="form-box">
                <h1>Add Product</h1><br><br>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Product Name: </span>
                                </div>
                                    <input size="50" name="product-name" type="text" class="form-control" style="width:25%;" aria-describedby="basic-addon3" required>
                            </div>
                           

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Product Description: </span>
                                </div>
                                <textarea name="product-description" class="form-control" aria-label="Product Description: " required></textarea>
                            </div> 

                            <div class="input-group mb-3" style="margin-top:10px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Price: $</span>
                                </div>
                                    <input size="20" name="product-price" type="text" class="form-control" style="width:25%;" aria-describedby="basic-addon3" pattern="^[0-9]*.[0-9][0-9]" title="0.00" required>
                            </div>
                                
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">Select Category: </span>
                                </div>
                                <select class="form-control" name="category" style="width: 250px; margin-left: 14px;" required>
                                    <option value="">Category By</option>
                                    <?php
                                        include('connection.php');
                                        $sql = 'SELECT category_name FROM category;';
                                        $result = mysqli_query($link, $sql);
                                        if (!$result)   {
                                            print "<h3>Select Failed from test_table ==> " . $sql . "<BR></h3>";
                                        }
                                        else {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $selected = '';
                                                    // Display the choosen category to the user
                                                    if ($_POST['category'] == $row['category_name']){
                                                        $selected = 'selected';
                                                    }
                                                    print "<option value='" . $row['category_name'] . "'" . $selected . ">" . $row['category_name'] ."</option>";
                                                    $selected = '';
                                                }
                                        } 
                                    ?>
                                </select> <br><br>
                            </div>
                                <!-- Image upload -->
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input id="getfile" type="file" class="custom-file-input" name="image-file" id="image-file" onchange="readURL(this);" title="" required></p>
                                        <label class="custom-file-label" for="getfile" id="chosen-file">Choose File: </label>
                                    </div>  
                                </div>
                                <img id="upload-image" src="img/download.png" alt="img" class="img-thumbnail" style="margin-top:10px;">
                                <input type="hidden" name="MAX_FILE_SIZE" value="100000"><br><br>
                                <!-- image file handling -->
                                <?php
                                    if (isset($_FILES['image-file']['tmp_name'])) {
                                        if ($_FILES['image-file']['error'] > 0) {
                                            $Valid_Input = FALSE;
                                            ?>
                                            <script> window.alert("Image not uploaded to server"); </script>
                                            <?php
                                        }
                                        else {
                                            if (is_uploaded_file($_FILES['image-file']['tmp_name'])) {
                                                $filename = $_FILES['image-file']['name'];
                                                $upfile = "img//" . $filename;
                                                $filename = "'" . $filename . "'";
                                                
                                                if (!move_uploaded_file($_FILES['image-file']['tmp_name'], $upfile)) {
                                                    $Valid_Input = FALSE;
                                                ?><script>window.alert("Image file uploaded to server");</script>
                                                <?php
                                                }
                                                //echo '<img src="'.$upfile.'" id="img-upload">';
                                            }
                                            else {
                                                $Valid_Input = FALSE;
                                                ?> <script>window.alert("Image file not uploaded to temp file server");</script><?php
                                            }
                                        }
                                    }
                                ?>
                                
                                <!-- INSERT TO DATABASE -->
                                <?php
                                    include('connection.php');
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                        $productName = $_POST['product-name'];
                                        $productDescription = $_POST['product-description'];
                                        $productPrice = $_POST['product-price'];
                                        $productCategory = '';

                            //get the category id from selecting category name
                            switch ($_POST['category']) {
                                case 'Chair' : $productCategory = 1; break;
                                case 'Controller' : $productCategory = 2; break;
                                case 'Boardgames' : $productCategory = 3; break;
                                case 'Headphone' : $productCategory = 4; break;
                                case 'Monitor' : $productCategory = 5; break;
                                case 'Video Games' : $productCategory = 6; break;
                                default : 
                                break;
                            }
                        
                            //add product to database
                            $addProduct = "INSERT into product (product_name, product_description, product_price, product_image, category_id)
                            VALUES ('$productName', '$productDescription', '$productPrice', '$upfile', '$productCategory')";
                            $checkProduct = "SELECT product_name FROM product WHERE product_name = '$productName'";
                            $product_result = mysqli_query($link, $checkProduct);
                            /********  Please alter table product and make product_name Unique ************/

                            //query to database with validation
                            if ($product_result){
                                $row = mysqli_num_rows($product_result);

                                if ($row >= 1) {
                                    echo "<script>alert('Product already Exists.');</script>";
                                }
                                else {
                                    mysqli_query($link, $addProduct);
                                    echo "<script>alert('Product has been successfully added.');</script>";
                                }
                               
                            }
                            
                        }    
                    ?>	
                        <div class="wrap">
                            <input type="reset" class="btn btn-danger" value="CLEAR" style="float:left;" id="clear-btn">
                        </div>
                        <div class="wrap">
                            <input type="submit" class="slide-hover-left-1" value="ADD PRODUCT" style="float:right;" id="add-btn">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
        </div><!-- End Section add product-->
	<?php
	include('footer.php');
	?>
<script>

//Show image on upload (live preview)
    function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#upload-image')
                            .attr('src', e.target.result)
                            .width(300)
                            .height(200);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
    //function to show filename
    $("#getfile").change(function() {
        $("#chosen-file").text(this.files[0].name);
    });

    //function to clear form fields
    $("#clear-btn").click(function() {
        $("#chosen-file").text("Choose File: ");
        $("#upload-image").attr('src', 'img/download.png');
    });

</script>

