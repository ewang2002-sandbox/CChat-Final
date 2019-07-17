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
<div id=1>

</div>

    <script>  
// array
let dms = (<?php
// Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
   // if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_SESSION["id"];
        $result = mysqli_query($link,"SELECT u1,u2 FROM dmlist where u1 = ".$id." or u2 = ".$id);
        //$rows = [];
        //echo "SELECT u1,u2 FROM dmlist where u1 = ".$id." or u2 = ".$id;
        $res = [];
        $unames = [];
        $unread = [];
 //       $tempid = 0;
        while($row = mysqli_fetch_assoc($result)) {
            //$rows[] = $row;
            if ($row["u1"] != $id){
                $res[] = $row["u1"];
                $result_ = mysqli_query($link,"SELECT lastid FROM dmreadtimes where u1 = ".$id." and u2 = ".$row["u1"]);
                if (mysqli_num_rows($result_) == 0){
                    $tempid = 0;
                    mysqli_query($link, "INSERT INTO `dmreadtimes` (
                        `id` ,
                        `u1` ,
                        `u2` ,
                        `lastid`
                        )
                        VALUES (
                        NULL , '".$id."', '".$row["u1"]."', '0');");
                        //echo 123;
                } else {
                    $tempid = mysqli_fetch_row($result_)[0];
                }

                $unames[] = mysqli_fetch_row(mysqli_query($link, "SELECT username FROM users WHERE id =".$row["u1"]))[0];
            } else if ($row["u2"] != $id) {
                $res[] = $row["u2"];
                $result_ = mysqli_query($link,"SELECT lastid FROM dmreadtimes where u1 = ".$id." and u2 = ".$row["u2"]);
                if (mysqli_num_rows($result_) == 0){
                   // echo 124;
                    $tempid = 0;
                    mysqli_query($link, "INSERT INTO `dmreadtimes` (
                        `id` ,
                        `u1` ,
                        `u2` ,
                        `lastid`
                        )
                        VALUES (
                        NULL , '".$id."', '".$row["u2"]."', '0');");
                } else {
                    $tempid = mysqli_fetch_row($result_)[0];
                }
                $unames[] = mysqli_fetch_row(mysqli_query($link, "SELECT username FROM users WHERE id =".$row["u2"]))[0];
            }
            if ($tempid != mysqli_fetch_row(mysqli_query($link,"SELECT id FROM dmreadtimes ORDER BY ID DESC LIMIT 1"))[0]){
                $unread[] = 1;
            } else{
                $unread[] = 0;
            }
        }
        echo json_encode($res);
        //echo $rows[0]["u2"];
        //echo mysqli_fetch_row($rows[0]  );
    //}
}?>);// <a href = "chat.php?u=[num]">text</a>
let unames = <?php echo json_encode($unames)?>;
let unreads = <?php echo json_encode($unread)?>;
console.log(unames);
console.log(unreads);
document.write('<div class="sidebar">');
for (let i = 0; i < dms.length; i++) {
    var unread = unreads[i] == 1?" NEW":"";
    var unread_ = unreads[i] == 1?"#f00":"ccc";
    document.write(' <a href = "chat.php?u=' + dms[i] + '" style=background-color:'+unread_+'>'+ unames[i] + '</a>')
}
document.write('</div>');
</script>

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
$(document).ready(function () {
    var sidebarNumber = document.getElementsByClassName('sidebar a');
    for (var i = 0; i <= sidebarNumber.length; i++) {
    $('.sidebar a').on('mouseover',function() {
        this.style['background-color'] = '#' + color_sess;
    });
    $('.sidebar a').on('mouseout',function() {
        this.style['background-color'] = '#f1f1f1';
    });
    }
});
   </script>
</body>
</html>
