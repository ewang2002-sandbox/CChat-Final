
<?php
// Initialize the session
session_start();
 //echo "qwerty";
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && (!isset($_SESSION["lastmsg"]) || $_SESSION["lastmsg"] + 3 < time())){
  $_SESSION["lastmsg"] = time();
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
   //   if (strlen(trim($_POST["msg"]))>512){ 
  //      echo "tooLong";
  //    } else if (strlen(trim($_POST["msg"])) == 0) {
   //     echo "tooShort";
   //   } else {
		$dbspec = "";
		if (isset($_POST["u1"]) && isset($_POST["u2"])){
			$u1 = intval($_POST["u1"]);
			$u2 = intval($_POST["u2"]);
			if(($_SESSION["id"] == $u1 || $_SESSION["id"] == $u2) && mysqli_num_rows(mysqli_query($link,"SELECT id FROM users where id = ".$u1." OR id = ".$u2)) == 2){
				$dbspec = "_".min($u1,$u2)."_".max($u1,$u2);
      }
    }

          $qry = 'INSERT INTO messages'.$dbspec.'(username,message) VALUES ("'.$_SESSION["username"].'","'.trim($_POST["msg"]).'")';
  //        echo $qry;
  // no u
          $result = mysqli_query($link,$qry);
  //        $result = mysqli_query($link,'INSERT INTO messages(username,message) VALUES ('.$_SESSION["username"].'$_POST["msg"])');
    //      echo $_SESSION["username"].$_POST["msg"];
    //    echo "yay";

    //  }
 // } else {
   // echo "spam";
  }
}