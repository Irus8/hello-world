<?php
/******************************************************************************************
 * @author Ireneusz Czerwinski
 * @email Irusjestswietny@gmail.com
 * --------------------------------------------------
 * All rights reserved! | Wszystkie Prawa Zastrzeżone!
 ******************************************************************************************/
ob_start();
session_start();

echo "<link rel='Stylesheet'  href='style.css' />";

if (isset($_SESSION["user_name"])) {
	$user_name = $_SESSION["user_name"];
}
else {
	$user_name = "unknown";
}

$date = date('d-m-Y');
$time = date('G:i');

if (isset($_GET["thread_id"])) {
	$_SESSION["thread_id"] = $_GET["thread_id"]; 
}

$thread_id = $_SESSION["thread_id"];
include ("forum_connect.php");

$ask = "SELECT * FROM talks WHERE thread_id=$thread_id ORDER BY talk_id";
$ans = $connect -> query($ask);
$result = $ans -> fetch_all();
$num_rows = $ans -> num_rows;

$ask_2 = "SELECT * FROM threads WHERE thread_id=$thread_id";
$ans_2 = $connect -> query($ask_2);
$result_2 = $ans_2 -> fetch_assoc();
$thread_name = $result_2["thread_name"];
$cat_id = $result_2["cat_id"];

$ask_4 = "SELECT * FROM categories WHERE cat_id=$cat_id";
$ans_4 = $connect -> query($ask_4);
$result_4 = $ans_4 -> fetch_assoc();
$cat_name = $result_4["cat_name"]; 
 
echo "
	<center>
		<div class='forum_style'>
			<a href='forum.php?$cat_id=$cat_name#opened'>
				<div class='forum_style cat_name_thread shad3'>
					$thread_name
				</div>
			</a>
			<center>";
			
			$_SESSION["class"] = "talk_left";
			
			for ($i=0; $i<($num_rows); $i++) {
				$talk_id = $result[$i][0];
				$talk = $result[$i][2];
				$author = $result[$i][3];
				$date_2 = $result[$i][4];
				$time_2 = $result[$i][5];

				if ($i > 0) {	
					$i_2 = ($i-1);
				}
				else {
					$i_2 = 0;
				}
		
				$talk_id_before = $result[$i_2][0];
				$ask_6 = "SELECT author FROM talks WHERE talk_id=$talk_id_before";
				$ans_6 =  $connect -> query($ask_6);
				$result_6 = $ans_6 -> fetch_assoc();
				$author_before = $result_6["author"];
				
				if ($author_before != $author) {
						if ($_SESSION["class"] == "talk_left") {
							$_SESSION["class"] = "talk_right";
						}
						else {
							$_SESSION["class"] = "talk_left";
						}
					}
			
			echo "	
			<div id='talk_container$talk_id' class='container shad4'>
				<div class='$_SESSION[class]'>
					<center><img src='serduszko.JPG'></img></center><hr>
					$author<hr>
					$date_2<hr>
					$time_2
				</div>
					$talk
			</div>
			";
			}
			echo "
			</center>";
			
		if (!isset($_GET["new_talk"])) {
			echo "<a class='answer' href='$_SERVER[PHP_SELF]?new_talk=Answer#new_talk'><div class='answer' >Answer</div></a>";
		}
		elseif (isset($_GET["new_talk"])) {
			echo "<form id='new_talk' method='GET'>";
			echo "<input class='text' type='text' name='talk'>";
			echo "<input type='submit' name='new_talk' value='Save'>";
			echo "</form>";
			if ($_GET["new_talk"] == "Save") {
				if (empty($_GET["talk"])) {
				echo "Napisz cos kochanie.";
				}
				else {
					$ask_3 = "INSERT INTO `talks` VALUES (NULL, '$thread_id', '$_GET[talk]', '$user_name', '$date', '$time')";
					$ans_3 = $connect -> query($ask_3);
					$ask_5 = "SELECT MAX(talk_id) FROM talks WHERE thread_id=$thread_id";
					$ans_5 = $connect -> query($ask_5);
					$result_5 = $ans_5 -> fetch_assoc();
					$max_talk_id = $result_5["MAX(talk_id)"];
					// header function has been modified in this version of forum -> forum_akcja_2 
					header ("Location: $_SERVER[PHP_SELF]#talk_container$max_talk_id");
				}
			}
		}		
					
		echo "
		</div>
	</center>";	
ob_end_flush();
?>



