<?php
require_once 'config/db.php';
require_once 'session.php';

$username = $password = "";
$username_err = $password_err = "";

$sql = 'SELECT id, username, password, is_admin FROM user WHERE username = ?';
$stmt = $mysqli->prepare($sql);

do {  
    // Handle only POST method
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        break;
    }

    if (!$stmt) {
        break;
    }

    $username = $mysqli->real_escape_string(trim($_POST["username"]));
    $password = $mysqli->real_escape_string(trim($_POST["password"]));

    // Check if username is empty
    if (empty($username)) {
        $username_err = 'Please enter your username.';
        break;
    }

    // Check if password is empty
    if (empty($password)) {
        $password_err = 'Please enter your password.';
        break;
    }

    $stmt->bind_param("s", $param_username);

    // Set the statement parameters
    $param_username = $username;

    // Attempt to execute the prepared statement
    if (!$stmt->execute()) {
        echo 'Oops! Something went wrong. Please try again later.';
        break;
    }

    $stmt->store_result();
    
    // Check if username exists, if yes then verify password
    if ($stmt->num_rows !== 1) {
        $username_err = 'Username not found.';
        break;    
    }
        
    // Bind result variables
    $stmt->bind_result($userID, $username, $hashed_password, $is_admin);

    if (!$stmt->fetch()) {
        break;
    }

    if (password_verify($password, $hashed_password)) {
        session_start();
        store_in_session('userID', $userID);
        store_in_session('username', $username);
        store_in_session('is_admin', $is_admin);
        header('location: dashboard.php');
    } else {
        $password_err = 'The password you entered was not valid.';
    }

    $stmt->close();
} while (false);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials.</p>
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
        </form>
    </div>    
</body>
</html>
