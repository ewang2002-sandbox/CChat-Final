<?php 
  $last_id = 0;
  session_start();
	if((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
		require_once "config.php";
		$dbspec = "";
		if (isset($_POST["u1"]) && isset($_POST["u2"])){
			$u1 = intval($_POST["u1"]);
			$u2 = intval($_POST["u2"]);
			if(($_SESSION["id"] == $u1 || $_SESSION["id"] == $u2) && mysqli_num_rows(mysqli_query($link,"SELECT id FROM users where id = ".$u1." OR id = ".$u2)) == 2){
				$dbspec = "_".min($u1,$u2)."_".max($u1,$u2);
			}
		}
		
		$result_ = mysqli_query($link,"SELECT id FROM messages".$dbspec." ORDER BY ID DESC LIMIT 1");
		$row_ = mysqli_fetch_row($result_);
    $last_id = $row_[0];
  //  echo 69;
	}

	echo $last_id;
	//echo isset($_POST["u1"]) && isset($_POST["u2"]);
	?>