<?php
require_once 'config.php';

$username = $password = "";
$username_err = $password_err = "";

$sql = "SELECT username, password FROM users WHERE username = ?";
$stmt = $mysqli->prepare($sql);

do {
    // Handle only POST method
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        exit;
    }

    $username = $mysqli->real_escape_string(trim($_POST["username"]));
    $password = $mysqli->real_escape_string(trim($_POST["password"]));

    // Check if username is empty
    if(empty($username)) {
        $username_err = 'Please enter username.';
        exit;
    }

    // Check if password is empty
    if(empty($password)) {
        $password_err = 'Please enter your password.';
        exit;
    }

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $param_username);

    // Set parameters
    $param_username = $username;

    // Attempt to execute the prepared statement
    if(!$stmt->execute()) {
        echo "Oops! Something went wrong. Please try again later.";
        exit;
    }
        
    // Store result
    $stmt->store_result();
    
    // Check if username exists, if yes then verify password
    if($stmt->num_rows !== 1) {
        $username_err = 'No account found with that username.';
        exit;    
    }
        
    // Bind result variables
    $stmt->bind_result($username, $hashed_password);
    if($stmt->fetch()) {
        if(password_verify($password, $hashed_password)) {
            /* Password is correct, so start a new session and
            save the username to the session */
            session_start();
            $_SESSION['username'] = $username;      
            header("location: welcome.php");
        } else {
            // Display an error message if password is not valid
            $password_err = 'The password you entered was not valid.';
        }
    }
} while(false);

$stmt->close();
$mysqli->close();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username:<sup>*</sup></label>
                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password:<sup>*</sup></label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>
