<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <title>Welcome</title>
	<link rel="stylesheet" href=welcomestyle.css>
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
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
<nav>
	<p>CChat</p>
	<ul>
		<li><a href="welcomev2.php">Home</a></li>
        <li><a href="chat.php"> Chat</a>
             <ul>
				<li><a href="getdms.php"class="hover">Direct Messages</a></li>
			</ul>
            </li>
        
		<li><a href="#">Settings</a> 
			<ul>
				<li><a href="missioncontrol.php"class="hover">Change Color</a></li>
				<li><a href="reset-password.php"class="hover">Reset</a></li>
			</ul>
		</li>
		<li><a href="logout.php"> Logout</a></li>
	</ul>
</nav>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <p>
        

    </p>
</body>
</html>