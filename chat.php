<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="loginstuff.css">
 <link rel="stylesheet" href="welcomestyle.css">
 
<?php session_start();
if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
	header("location: login.php");
}?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>CChat</title>

	
</head>

<body>
	
<nav >
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
<h2>
    <font color=#fff>Chat Messages</font>
</h2>
<link rel="stylesheet" href="chatstyle.css">
<input type="text" name="txt" id="txt" class="form-control">
<span class="help-block"></span>
<div id="msgs">
</div>
<button class="btn " background-color: "'+ color_+'" onclick="sendMsg()">Send
    <i class="material-icons right"></i>
</button>

<button class="btn " onclick="loadMore()">Load More
    <i class="material-icons right"></i>
</button>

<?php 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
if( $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["u"]) && mysqli_num_rows(mysqli_query($link,"SELECT id FROM users where id = ".$_SESSION["id"]." OR id = ".$_GET["u"])) == 2){
	$result = mysqli_query($link,"SHOW TABLES FROM `main1` LIKE 'messages_".min($_SESSION["id"],$_GET["u"])."_".max($_SESSION["id"],$_GET["u"])."';");
	if (mysqli_num_rows($result) == 0){mysqli_query($link," CREATE TABLE `main1`.`messages_".min($_SESSION["id"],$_GET["u"])."_".max($_SESSION["id"],$_GET["u"])."` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`username` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
		`message` VARCHAR( 512 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
		`sent_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
		`edited` TINYINT( 1 ) NOT NULL DEFAULT '0',
		PRIMARY KEY ( `id` )
		) ENGINE = MYISAM");
		mysqli_query($link, "INSERT INTO `dmlist` (
			`id` ,
			`u1` ,
			`u2`
			)
			VALUES (
			NULL , '".$_SESSION["id"]."', '".$_GET["u"]."');");
	}else {echo "ok";}
//}else{
//echo 'document.getElementById("txt").value = HACKER NO HACKING'	;
}}?>



<script>

const dm = <?php if( $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["u"])){echo 1;}else{echo 0;}?>;
const other_user = <?php if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["u"])) {echo $_GET["u"];}else{echo 0;}?>;

if(dm){
		$.ajax({
			type: 'POST',
			url: "updatedms.php",
			data: {"u":other_user}
		});	
	}




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
const node = document.getElementById("txt");
node.addEventListener("keyup", function(event) {
    if (event.key === "Enter") {
		var red = parseInt(color_sess.substring(0,2), 16);
		var red_ = Math.max(0,red-16);
		var grn = parseInt(color_sess.substring(2,4), 16);
		var grn_ = Math.max(0,grn-16);
		var blu = parseInt(color_sess.substring(4,6), 16);
		var color_ = "ffffff"
		var blu_ = Math.max(0,blu-16);
		const d = new Date();
		const dStr = `${isToday(d) ? "Today" : `${addZero(d.getMonth() + 1)}/${addZero(d.getDate())}/${addZero(d.getFullYear())}`} at ${addZero(d.getHours())}:${addZero(d.getMinutes())}:${addZero(d.getSeconds())}`
		var dark = " blackBorder"
		var c_by = 0;
		if (Math.max(red,grn,blu)<144){
			c_by = 32;
			dark = " whitetext"
		}
		color_ = changecolor(red_,c_by)+changecolor(grn_,c_by)+changecolor(blu_,c_by);
		const parent = document.getElementById('msgs');
		var newChild =
				'<div class="container" id="temp" style="background-color:#fff"><div class="user'+dark+'" style="background-color:#'+color_sess+';width:30%;text-align:center;"><font size="-2">' + dStr + '</font><br>' + <?php echo '"'.$_SESSION["username"].'"';?> + '</div>   ' +
				document.getElementById("txt").value + '</div>';
			parent.insertAdjacentHTML('afterbegin', newChild);
			/*var newChild =
				  '<ul class="container"> <li class="container">  < class="circle" style="background-color:#'+color_sess+'"> <p> +'document.getElementById("txt").value'+</p>  </li>';
			parent.insertAdjacentHTML('afterbegin', newChild);*/

		sendMsg();
    }
});
let cooldown = false;
async function sendMsg() {
	if (cooldown) {
		alert("Please wait before sending messages.");
		document.getElementById("txt").value = ""
		return;
	}

	let response = "";
	const t = document.getElementById("txt").value;
	if (t.length > 512) {
		alert("Message too long, try again")
	} else if (t.length === 0) {
		alert("Message too short, try again")
	} else {
		cooldown = true;
		await pSendMsg();
		document.getElementById("txt").value = "";
		setTimeout(() => {
			cooldown = false;
		}, 3000);
	}
}

function pSendMsg() {
	return new Promise((resolve, reject) => {
		let data_ = {};
		if (dm){
			data_ = {"msg":document.getElementById("txt").value,"u1":other_user,"u2":<?php echo $_SESSION["id"];?>};
		} else {
			data_ = {"msg":document.getElementById("txt").value};
		}
		$.ajax({
			type: 'POST',
			url: "sendmsg.php",
			data: data_,
			success: function(text) {
				resolve("Done");
			}
		});
	});
}

//
//
//
	function getmsg(id_) {
    return new Promise((resolve, reject) => {
		if (dm){
			var r = $.post("getmsg.php",{"id":id_,"u1":other_user,"u2":<?php echo $_SESSION["id"];?>});
			return resolve(r);
		} else {
			var r = $.post("getmsg.php",{"id":id_});
			return resolve(r);
		}
    });
}



let isloading = false;
window.onscroll = function(ev) {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
		if (!isloading){
			loadMore();
			isloading = true;
			setTimeout(() => {
				isloading=false;
			}, 1000);
		}
    }
};

function loadMore(){
	last = lastmsg;
	first = Math.max(0,last-10);
	lastmsg = first;
	var initload = setInterval(async () => {
	if (last > first){
		const parent = document.getElementById('msgs');
		last--;
		var msg = JSON.parse(await getmsg(last));
		var color = msg.color;
		var red = parseInt(color.substring(0,2), 16);
		var red_ = Math.max(0,red-16);
		var grn = parseInt(color.substring(2,4), 16);
		var grn_ = Math.max(0,grn-16);
		var blu = parseInt(color.substring(4,6), 16);
		var color_ = "ffffff"
		var blu_ = Math.max(0,blu-16);
		const d = new Date(msg.sent_at);
		const dStr = `${isToday(d) ? "Today" : `${addZero(d.getMonth() + 1)}/${addZero(d.getDate())}/${addZero(d.getFullYear())}`} at ${addZero(d.getHours())}:${addZero(d.getMinutes())}:${addZero(d.getSeconds())}`
		var dark = " blackBorder"
		var c_by = 0;
		let tcolor = "black";
		if (Math.max(red,grn,blu)<144){
			c_by = 32;
			dark = " whitetext"
			tcolor = "white";
		}
		color_ = changecolor(red_,c_by)+changecolor(grn_,c_by)+changecolor(blu_,c_by);
		m_uname = msg.username;
		if (msg.id != <?php echo $_SESSION["id"];?> && <?php if(isset($_GET["u"])){echo 0;}else{echo 1;}?>){
			m_uname = '<a href="chat.php?u='+msg.id+'" style="color: '+tcolor+';text-decoration: none;">'+m_uname+'</a>';
		}
		var newChild =
				'<div class="container'+''+'" style="background-color:#fff"><div class="user'+dark+'" style="background-color:#'+color+';width:30%;text-align:center;"><font size="-2">' + dStr + '</font><br>' + m_uname + '</div>   ' +
				msg.message + '</div>';
			parent.insertAdjacentHTML('beforeend', newChild);
	} else { clearInterval(initload);}
}, 75);

}

//refresh messages
//var i = '<?php  /*
	$last_id = 0;
	if((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
		require_once "config.php";
		$result_ = mysqli_query($link,"SELECT id FROM messages ORDER BY ID DESC LIMIT 1");
		$row_ = mysqli_fetch_row($result_);
		$last_id = $row_[0];
	}
	
	echo $last_id;*/?>';
//i_old = Math.max(0,i-15);
//let lastmsg = i_old;
function changecolor(col,by){
	return ("0"+(col+by).toString(16)).slice(-2);
}
function addZero(num) {
			return (num >= 0 && num < 10) ? "0" + num : String(num);
		}
function isToday(someDate) {
	const today = new Date()
	return someDate.getDate() == today.getDate() &&
		someDate.getMonth() == today.getMonth() &&
		someDate.getFullYear() == today.getFullYear()
}
i_old = -1
let_lastmsg = -1;
var initload = setInterval(async () => {
	let data_ = {};
	if (dm) {data_ = {"u1":other_user,"u2":<?php echo $_SESSION["id"];?>};}
	var i = await $.ajax({
  	type: 'POST',
 	url: 'checkpoint.php',
	 data: data_
	 });
	 if (i_old===-1){
	 i_old = Math.max(0,i-15);
	 lastmsg = i_old;}
 // console.log(i);
	if (i > i_old){
		const parent = document.getElementById('msgs');
		i_old++;
		var msg = JSON.parse(await getmsg(i_old));
		var color = msg.color;
		var red = parseInt(color.substring(0,2), 16);
		var red_ = Math.max(0,red-16);
		var grn = parseInt(color.substring(2,4), 16);
		var grn_ = Math.max(0,grn-16);
		var blu = parseInt(color.substring(4,6), 16);
		var color_ = "ffffff"
		var blu_ = Math.max(0,blu-16);
//		var color_ = red_.toString(16)+grn_.toString(16)+blu_.toString(16);
		const d = new Date(msg.sent_at);
		const dStr = `${isToday(d) ? "Today" : `${addZero(d.getMonth() + 1)}/${addZero(d.getDate())}/${addZero(d.getFullYear())}`} at ${addZero(d.getHours())}:${addZero(d.getMinutes())}:${addZero(d.getSeconds())}`
		var dark = " blackBorder"
		var c_by = 0;
		let tcolor = "black";
		if (Math.max(red,grn,blu)<144){
			c_by = 32;
			dark = " whitetext"
			tcolor = "white";
		}
		color_ = changecolor(red_,c_by)+changecolor(grn_,c_by)+changecolor(blu_,c_by);
		//console.log(msg.color);
		m_uname = msg.username;
		if (msg.id != <?php echo $_SESSION["id"];?> && <?php if(isset($_GET["u"])){echo 0;}else{echo 1;}?>){
			m_uname = '<a href="chat.php?u='+msg.id+'" style="color: '+tcolor+';text-decoration: none;">'+m_uname+'</a>';
		}
		var newChild =
				'<div class="container" style="background-color:#fff"><div class="user'+dark+'" style="background-color:#'+color+';width:30%;text-align:center;"><font size="-2">' + dStr + '</font><br>' + m_uname + '</div>   ' +
				msg.message + '</div>';
			parent.insertAdjacentHTML('afterbegin', newChild);
		//	console.log(newChild);

	} else { clearInterval(initload);}
console.log(2);
}, 75);




let timeout = 5000;
setInterval(async () => {
    //	console.log(i_old,<?php 
    $last_id = 0;
    if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
        require_once "config.php";

        $result_ = mysqli_query($link, "SELECT id FROM messages ORDER BY ID DESC LIMIT 1");
        $row_ = mysqli_fetch_row($result_);
        $last_id = $row_[0];
    }

    echo $last_id;?>);
	let data_ = {};
	if (dm) {data_ = {"u1":other_user,"u2":<?php echo $_SESSION["id"];?>};}
	var i = await $.ajax({
  	type: 'POST',
 	url: 'checkpoint.php',
	 data: data_
	 });
// console.log(i);
if (i > i_old) {
    /** */

    var initload = setInterval(async () => {
        //	console.log(i_old,<?php 
        $last_id = 0;
        if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
            require_once "config.php";

            $result_ = mysqli_query($link, "SELECT id FROM messages ORDER BY ID DESC LIMIT 1");
            $row_ = mysqli_fetch_row($result_);
            $last_id = $row_[0];
        }

        echo $last_id;?>);
	let data_ = {};
	if (dm) {data_ = {"u1":other_user,"u2":<?php echo $_SESSION["id"];?>};}
	var i = await $.ajax({
  	type: 'POST',
 	url: 'checkpoint.php',
	 data: data_
	 });
   // console.log(i);
    if (i > i_old) {
        while (document.getElementById('temp') !== null) {
            var element = document.getElementById("temp");
            element.parentNode.removeChild(element);
        }
        //		console.log(i,i_old)
        const parent = document.getElementById('msgs');
        i_old++;
        var msg = JSON.parse(await getmsg(i_old));
        var color = msg.color;
        var red = parseInt(color.substring(0, 2), 16);
        var red_ = Math.max(0, red - 16);
        var grn = parseInt(color.substring(2, 4), 16);
        var grn_ = Math.max(0, grn - 16);
        var blu = parseInt(color.substring(4, 6), 16);
        var color_ = "ffffff"
        var blu_ = Math.max(0, blu - 16);
        //		var color_ = red_.toString(16)+grn_.toString(16)+blu_.toString(16);
        const d = new Date(msg.sent_at);
        const dStr = `${isToday(d) ? "Today" : `${addZero(d.getMonth() + 1)}/${addZero(d.getDate())}/${addZero(d.getFullYear())}`} at ${addZero(d.getHours())}:${addZero(d.getMinutes())}:${addZero(d.getSeconds())}`
        var dark = " blackBorder"
        var c_by = 0;
		let tcolor = "black";
		if (Math.max(red,grn,blu)<144){
			c_by = 32;
			dark = " whitetext"
			tcolor = "white";
        }
        color_ = changecolor(red_, c_by) + changecolor(grn_, c_by) + changecolor(blu_, c_by);
        //console.log(msg.color);
		m_uname = msg.username;
		if (msg.id != <?php echo $_SESSION["id"];?> && <?php if(isset($_GET["u"])){echo 0;}else{echo 1;}?>){
			m_uname = '<a href="chat.php?u='+msg.id+'" style="color: '+tcolor+';text-decoration: none;">'+m_uname+'</a>';
		}
        var newChild =
            '<div class="container" style="background-color:#fff"><div class="user'+dark+'" style="background-color:#' + color + ';width:30%;text-align:center;"><font size="-2">' + dStr + '</font><br>' + m_uname + '</div>   ' +
            msg.message + '</div>';
        /*		'<div class="container" style="background-color:#'+(msg.color)+'><div class="user" style="background-color:#a0f4a0;width:30%;text-align:center;">'+i_old+'</div><p>' +
                "msg" + '</p></div>';*/
        parent.insertAdjacentHTML('afterbegin', newChild);
        //	console.log(newChild);
        // local function

    } else { clearInterval(initload); }

    console.log(3);
//	if(dm){
//		$.ajax({
//			type: 'POST',
//			url: "updatedms.php",
//			data: {"u":other_user}
//		});	
//	}
}, 150);

	}

console.log(1);
}, timeout);
</script>
<?php //echo 420 ?>

</body>

</html>
