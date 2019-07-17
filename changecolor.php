<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $color = $_POST["color"];
        if (strlen($color) == 7){
            $color = substr($color, 1, 7);
        }
        //echo 'UPDATE users SET message_color = "'.$color.'" where id=1';
        $result = mysqli_query($link,'UPDATE users SET message_color = "'.$color.'" where id='.$_SESSION["id"]);
       // $row = mysqli_fetch_row($result);
        //echo $color;
        header("location: chat.php");
    }
}
