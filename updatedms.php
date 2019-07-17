
<?php
// Initialize the session
session_start();
 //echo "qwerty";
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
		if (isset($_POST["u"])){
			$u = intval($_POST["u"]);
    }
            $tid = mysqli_fetch_row(mysqli_query($link,"SELECT id FROM dmreadtimes ORDER BY ID DESC LIMIT 1"))[0];
          $qry = 'UPDATE dmreadtimes SET lastid='.$tid.' WHERE u1 = '.$_SESSION["id"].' and u2 = '.$u.';';
  //        echo $qry;
  // no u
          $disposeofmeimuseless = mysqli_query($link,$qry);
  //        $result = mysqli_query($link,'INSERT INTO messages(username,message) VALUES ('.$_SESSION["username"].'$_POST["msg"])');
    //      echo $_SESSION["username"].$_POST["msg"];
    //    echo "yay";

    //  }
 // } else {
   // echo "spam";
  }
}
?>
