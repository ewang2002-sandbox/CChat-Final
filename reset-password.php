<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="loginstuff.css">
    <link rel="stylesheet" href="welcomestyle.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<nav>
	<p>CChat</p>
	<ul>
		<li><a href="welcomev2.php">Home</a></li>
        <li><a href="chat.php"> Chat</a>
             <ul>
				<li><a href="getdms.php" class="hover">Direct Messages</a></li>
			</ul>
            </li>
		<li><a href="#">Settings</a> 
			<ul>
				<li><a href="missioncontrol.php" class="hover">Change Color</a></li>
				<li><a href="reset-password.php" class="hover">Reset</a></li>
			</ul>
		</li>
		<li><a href="logout.php"> Logout</a></li>
	</ul>
</nav>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
    <script>
        let color_sess = '<?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	require_once "config.php"; 
	$result_ = mysqli_query($link,"SELECT message_color FROM users where username = '".$_SESSION["username"]."'");
		$row_ = mysqli_fetch_row($result_);
		//echo "SELECT message_color FROM users where username = '".$_SESSION["username"]."'";
		echo $row_[0];}?>';
    $(document).ready(function () {
    var hoverNumber = document.getElementsByClassName('hover');
    for (var i = 0; i <= hoverNumber.length; i++) {
    $('.hover').on('mouseover',function() {
        this.style['background-color'] = '#' + color_sess;
    });
    $('.hover').on('mouseout',function() {
        this.style['background-color'] = 'rgb(200,200,200)'
    });
    }
});
        </script>
</body>
</html>