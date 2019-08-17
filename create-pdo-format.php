<?php
// Include config file
require_once "config-pdo-format.php";
 
// Define variables and initialize with empty values
$name = $lastname = $date_of_birth = $category = $product = "";
$name_err = $lastname_err = $date_of_birth_err = $category_err = $product_err  = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Assign date
    $date_of_birth = trim($_POST["date_of_birth"]);

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    //Validate last name
    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter last name.";
    } else{
        $lastname = $input_lastname;
    }

    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate category
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please enter the category.";     
    } else{
        $category = $input_category;
    }

    //Validate product
    $input_product = trim($_POST["product"]);
    if(empty($input_category)){
        $product_err = "Please enter the product.";     
    } else{
        $product = $input_product;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($lastname_err) && empty($date_of_birth_err) && empty($product_err) && empty($category_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO dtleads (firstname, lastname, date_of_birth, category, product) VALUES (:name, :lastname, :date_of_birth, :category, :product)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":lastname", $param_lastname);
            $stmt->bindParam(":date_of_birth", $param_DOB);
            $stmt->bindParam(":category", $param_category);
            $stmt->bindParam(":product", $param_product);
            
            // Set parameters
            $param_name = $name;
            $param_lastname = $lastname;
            $param_DOB = $date_of_birth;
            $param_category = $category;
            $param_product = $product;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index-pdo-format.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <textarea name="lastname" class="form-control"><?php echo $lastname; ?></textarea>
                            <span class="help-block"><?php echo $lastname_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($date_of_birth)) ? 'has-error' : ''; ?>">
                            <label>Date of birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="<?php echo $date_of_birth; ?>">
                            <span class="help-block"><?php echo $date_of_birth_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($category)) ? 'has-error' : ''; ?>">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
                            <span class="help-block"><?php echo $category_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($product)) ? 'has-error' : ''; ?>">
                            <label>Product</label>
                            <input type="text" name="product" class="form-control" value="<?php echo $product; ?>">
                            <span class="help-block"><?php echo $product_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index-pdo-format.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>