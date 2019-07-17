<?php
// Initialize the session
session_start();
 //echo "qwerty";
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (strlen(trim($_POST["msg"]))>512){ echo 2;} else {
        $qry = 'INSERT INTO messages(username,message) VALUES ("'.$_SESSION["username"].'","'.trim($_POST["msg"]).'")';
//        echo $qry;
        $result = mysqli_query($link,$qry);
//        $result = mysqli_query($link,'INSERT INTO messages(username,message) VALUES ('.$_SESSION["username"].'$_POST["msg"])');
  //      echo $_SESSION["username"].$_POST["msg"];
    }}
}