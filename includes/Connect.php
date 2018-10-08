<?php
// $mysql_host = "localhost";
// $mysql_database = "burzillian";
// $mysql_user = "root";
// $mysql_password = "";
//
// $con = mysql_connect("$mysql_host","$mysqli_database","$mysql_user","$mysql_password");
// $status = 0;
//
// mysql_select_db("$mysql_database", $con);
//
// if (!$con)
//   {
//
//   die('Could not connect: ' . mysql_error());
//   $status = 1;
//   }
//Create connection to database
$conn = mysqli_connect('localhost', 'root', '', 'capstone_daila');

//Check connection
if(mysqli_connect_errno()){
  //Connection failed
  echo 'Failed to connect to the database'.mysqli_connect_errno();
}
?>
