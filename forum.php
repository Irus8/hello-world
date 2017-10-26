<?php
/******************************************************************************************
 * @author Ireneusz Czerwinski
 * @email Irusjestswietny@gmail.com
 * --------------------------------------------------
 * All rights reserved! | Wszystkie Prawa Zastrzeżone!
 * If you like - I love you.
 * If you dont like - dog's dig suck.
 ******************************************************************************************/
session_start();

if (isset($_SESSION["user_name"])) 
	{ $user_name = $_SESSION["user_name"]; }
else 
	{ $user_name = "unknown"; }

echo "<link rel='Stylesheet'  href='style.css' />";

echo "<div class='title'>Forum</div>";

$date = date('d-m-Y');
$time = date('G:i');

include ("forum_connect.php");
$ask = "SELECT * from categories ORDER BY cat_id";
$ans = $connect -> query($ask);
$result = $ans -> fetch_all();
//Check for amount of categories - One category is one row.
$num_rows = $ans -> num_rows;

echo "<center>";
// I use a loop "for" to display a table with names of category.
for ($i=0; $i<($num_rows); $i++) {
	// in this variable I keep primary key of EACH category every itearation of loop.
	$cat_id = $result[$i][0];
	// In this variable I keep name of EACH category category every itearation of loop.
	$cat_name = $result[$i][1];
	if (!isset($_GET[$cat_id])) {
		echo "
		<a id='closed$cat_id' href='forum.php?$cat_id=$cat_name#opened'>
			<div class='forum_style cat_name_closed shad1'>
				<div style='margin: 0px 0px 0px 30px;'>
					$cat_name
				</div>
			</div>
		</a>
		";
	}
	elseif (isset($_GET[$cat_id]))	 {
		if ($_GET[$cat_id] == $cat_name) {			
			$ask_2 = "SELECT * FROM threads WHERE cat_id='$cat_id' ORDER BY thread_id";
			$ans_2 = $connect -> query($ask_2);
			$result_2 = $ans_2 -> fetch_all();
			$num_rows_2 = $ans_2 -> num_rows;
			echo "
			<div id='opened' style='padding-top: 30px'>
				<div class='forum_style cat_content shad2'>
					<a href='forum.php'>
						<div class='forum_style cat_name_opened shad3'>
							$cat_name
						</div>
					</a>	
				<center>
					<table>
						<tr class='tr_01'>
							<td class='td_01'>
								<a href='forum.php?$cat_id=Add thread#opened'>
									<div>
										Add thread
									</div>
								</a>
							</td>
							<td class='td_02_03'>";
								if ($num_rows_2 != 0) {
									echo "Autor:";
								}
							echo "
							</td>
							<td class='td_02_03'>";
								if ($num_rows_2 != 0) {
									echo "Last talk:";
								}
							echo "
							</td>
						</tr>";
						for ($i_2=0; $i_2<$num_rows_2; $i_2++) {
							$thread_name = $result_2[$i_2][2];
							$thread_id = $result_2[$i_2][0];
							echo "
						<tr class='tr_02'>
							<td class='td_01 td_thread'>
								<a href='thread.php?thread_id=$thread_id'>
									<div>
										$thread_name
									</div>
								</a>
							</td>
							<td class='td_02_03'>";
							$ask_7 = "SELECT author FROM threads WHERE thread_id=$thread_id";
							$ans_7 = $connect -> query($ask_7);
							$result_7 = $ans_7 -> fetch_assoc();
							$thread_author = $result_7["author"];
							echo $thread_author;
							echo "	
							</td>
							<td class='td_02_03'>";
							$ask_8 = "SELECT MAX(talk_id) FROM talks WHERE thread_id=$thread_id";
							$ans_8 = $connect -> query($ask_8);
							$rows_amount = $ans -> num_rows;
							$result_8 = $ans_8 -> fetch_assoc();
							$last_talk_id = $result_8["MAX(talk_id)"];
							if ($last_talk_id <> NULL) {
								$ask_9 = "SELECT author FROM talks WHERE talk_id=$last_talk_id";
								$ans_9 = $connect -> query($ask_9);
								$result_9 = $ans_9 -> fetch_assoc();
								$last_talk_author = $result_9["author"];
								echo $last_talk_author;
							}
							echo "
							</td>				
						</tr>";
						}
					echo "	
					</table>
				</center>";
			echo "</div></div>";		
		}
		elseif ($_GET[$cat_id] == "Add thread" || $_GET[$cat_id] == "Add") {
			echo "
			<div id='opened' style='padding-top: 30px'>
				<div class='forum_style cat_content shad2'>
				<a href='forum.php?$cat_id=$cat_name#opened'>
					<div class='forum_style cat_name_opened'>
						$cat_name
					</div>
				</a>";
				$talk_value = "";
				$thread_value = "";
				if (isset($_GET["talk"])) {
					$talk_value = $_GET["talk"];
				}
				if (isset($_GET["thread_name"])) {
					$thread_value = $_GET["thread_name"];
				}		
				echo "
				<form method='GET'>
					<label>Thread name:</label><br />
						<input type='text' name='thread_name' value='$thread_value'><br />
					<label>Talk:</label><br />
						<input class='text' type='text' name='talk' value='$talk_value'><br />
					<input class='add' type='submit' name='$cat_id' value='Add'>
				</form>";
			if ($_GET[$cat_id] == "Add") {
				if (empty($_GET["thread_name"])) {
					echo "<tr><td><hr>Wpisz temat kochanie!</td></tr>";
				}
				else {
					$ask_3 = "INSERT INTO `threads` VALUES (NULL, '$cat_id', '$_GET[thread_name]', '$user_name')";
					$ans_3 = $connect -> query($ask_3);
					$ask_4 = "SELECT `thread_id` FROM `threads` WHERE thread_name='$_GET[thread_name]'";
					$ans_4 = $connect -> query($ask_4);
					$result_4 = $ans_4 -> fetch_assoc();
					$thread_id = $result_4["thread_id"];
					if (empty($_GET["talk"])) {
						header ("Location: thread.php?thread_id=$thread_id");
					}
					else {
						$ask_4 = "SELECT `thread_id` FROM `threads` WHERE thread_name='$_GET[thread_name]'";
						$ans_4 = $connect -> query($ask_4);
						$result_4 = $ans_4 -> fetch_assoc();
						$thread_id = $result_4["thread_id"];
						$ask_5 = "INSERT INTO `talks` VALUES (NULL, '$thread_id', '$_GET[talk]', '$user_name', '$date', '$time')";
						$ans_5 = $connect -> query($ask_5);
						header ("Location: thread.php?thread_id=$thread_id");
					}
				}
			}			
			echo "	
			</div>";
		}
	}
}
echo "</center>";

echo "<br><br>";
echo "<form method='GET'><input type='text' name='cat_name'>";
echo "<input type='submit' name='save_cat' value='Add category'></form>";

if (isset($_GET["save_cat"])) {
	$ask_6 = "INSERT INTO `categories` VALUES (NULL, '$_GET[cat_name]')";
	$ans_6 = $connect -> query($ask_6);
	// header function has been modified in this version of forum -> forum_akcja_2 
	header ("Location: $_SERVER[PHP_SELF]");
}

?>