<?php
// Initialize the session
session_start();
// echo "qwerty";
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
   //     echo var_dump($_POST);
        $id_ = $_POST["id"];
		$dbspec = "";
		if (isset($_POST["u1"]) && isset($_POST["u2"])){
			$u1 = intval($_POST["u1"]);
			$u2 = intval($_POST["u2"]);
			if(($_SESSION["id"] == $u1 || $_SESSION["id"] == $u2) && mysqli_num_rows(mysqli_query($link,"SELECT id FROM users where id = ".$u1." OR id = ".$u2)) == 2){
				$dbspec = "_".min($u1,$u2)."_".max($u1,$u2);
			}
		}
    //    echo $id_;
        $result = mysqli_query($link,"SELECT username,message,sent_at,edited FROM messages".$dbspec." where id = ".$id_);
        $row = mysqli_fetch_row($result);
        $out = new \stdClass();
		$out->username = $row[0]; // out.username = ...
		$out->message = $row[1];
        $out->sent_at = $row[2];
        $out->edited = $row[3];
   //     echo "SELECT message_color FROM users where username = '".$row[0]."'";
        $result_ = mysqli_query($link,"SELECT message_color,id FROM users where username = '".$row[0]."'");
        $row_ = mysqli_fetch_row($result_);
        $out->color = $row_[0];
        $out->id = $row_[1];
//echo $username.$message.$sent_at.$edited;
$fin = json_encode($out);
echo $fin;
    }
}
