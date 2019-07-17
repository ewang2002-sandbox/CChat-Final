<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <meta charset="UTF-8">
    <title>Welcome</title>
	<link rel="stylesheet" href=welcomestyle.css>
    <style type="text/css">
        body{ 
            font: 14px sans-serif; text-align: center; 
        }
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
<body>
<script src="jscolor.js"></script>
<h2><p style="text-align:center;">Change Color</p></h2>




<form name="color" action="/changecolor.php" method="post">

<input class="jscolor" name="color" value="#<?php session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
    $qry = 'SELECT message_color FROM users WHERE username = "'.$_SESSION["username"].'"';
    $result = mysqli_query($link,$qry);
    $row = mysqli_fetch_row($result);
    echo $row[0];
}else{echo "eeffaa";}?>">
  <input type="submit" value="Save Change">
</form> 

<script>
    let color_sess = '<?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	require_once "config.php"; 
	$result_ = mysqli_query($link,"SELECT message_color FROM users where username = '".$_SESSION["username"]."'");
		$row_ = mysqli_fetch_row($result_);
		//echo "SELECT message_color FROM users where username = '".$_SESSION["username"]."'";
		echo $row_[0];}?>';

console.log(color_sess);
var hoverNumber = document.getElementsByClassName('hover');
for (var i = 0; i <= hoverNumber.length; i++) {
$('.hover').on('mouseover',function() {
	this.style['background-color'] = '#' + color_sess;
});
$('.hover').on('mouseout',function() {
	this.style['background-color'] = 'rgb(200,200,200)'
});
}
</script>


</body>
</html>