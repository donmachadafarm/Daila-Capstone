<?php

include 'includes/sections/header.php';



/*
 *    T E S T I N G
 *
 *    A R E A
 *
 *    B E L O W
 *
 */
    // secondsdatetime -> time diff of two times turned into seconds
// echo seconds_datetime(60*check_time_diff_mins("10:28:00","12:23:00"));

// print_p(get_productprocess($conn,10));
// $m = get_machine($conn,1);
// $random_keys=array_rand($m,2);
// echo $m[$random_keys]."<br>";
//
// $dates = array
// (
//     '0'=> "2013-02-18 05:14:54",
//     '1'=> "2013-02-12 01:44:03",
//     '2'=> "2013-02-05 16:25:07",
//     '3'=> "2013-01-29 02:00:15",
//     '4'=> "2013-01-27 18:33:45"
// );
// //
// echo find_closest_today($dates,date('Y-m-d'));
//
// $cont = get_productprocess($conn,3);
// // checker for counter ng mga may used machines sa production
// $checker = 0;
// // per product process iteration
// for ($i=0; $i < count($cont); $i++) {
//   // if process has no available machine increment checker
//     if (count(get_machine($conn,$cont[$i]))==0) {
//       $checker++;
//     }
// }
// echo $checker;


// get due dates ng mga may same process id at machine ids
// $sql = mysqli_query($conn,"SELECT JobOrder.dueDate
//                             FROM JobOrder
//                             JOIN ProductionProcess ON JobOrder.orderID = ProductionProcess.orderID
//                             WHERE ProductionProcess.processTypeID = 1 AND ProductionProcess.machineID = 4");
// // store sa array
// $deadlines = array();
// while ($row = mysqli_fetch_array($sql)) {
//   array_push($deadlines,$row[0]);
// }
//
// // print_p($deadlines);
// // dummy data
// $arr = array('2018-11-21', '2018-11-25', '2018-12-01', '2018-11-22', '2018-11-24');
//
// // sortsort
// function date_sort($a, $b) {
//     return strtotime($a) - strtotime($b);
// }
// usort($arr, "date_sort");
// // print_p($arr);
// // finding the closest date sa array ng deadlines
// // echo find_closest_today($arr,date('Y-m-d'));
// // lumabas 2018-11-22
// // next gagawin mo dito is lahat ng mga may deadline na starting sa returned deadline sa taas imomove mo ung queue nila
//
//
// // get the cuurent queue muna nung nag result ng query from the closest deadline tapos from there don kana mag increment
// $sql = mysqli_query($conn,"SELECT machineQueue
//                             From ProductionProcess
//                             WHERE ProductionProcess.processTypeID = 1 AND ProductionProcess.machineID = 4
//                             ORDER BY machineQueue DESC LIMIT 1");
//
//   $row = mysqli_fetch_array($sql);
//
//   $curqueue = $row[0];

// if (check_complete_proc($conn,151,10)) {
//   echo "false";
// }else {
//   echo "true";
// }
// print_p(get_need_inventory($conn,176));
// $inv = get_need_inventory($conn,174);

//print_p($inv);

// for ($i=0; $i < count($inv); $i++) {
   //print_p($inv[$i]);
  // echo count($inv[$i]);
  // for ($j=0; $j < count($inv[$i]); $j++) {
     // print_p($inv[$i]);
  // }
// }
// print_p($inv[count($j)-1][count($i)-1]);
// print_p(get_need_inventory($conn,179));
// print_p(check_for_inventory_match($conn,174));
// echo check_for_out($conn,159);
// $query = "SELECT * FROM JobOrder WHERE orderID = 159";
//
// $sql = mysqli_query($conn,$query);
//
// $row = mysqli_fetch_array($sql);
//
// if (check_for_out($conn,159)) {
//   if($row['type'] == 'Made to Order'){
//     $query = "UPDATE JobOrder SET status = 'For Out' WHERE orderID = 159";
//
//     mysqli_query($conn,$query);
//   }else {
//     $query = "UPDATE ProductionProcess SET status = 'Added' WHERE orderID = 159 AND productID = 3";
//
//     mysqli_query($conn,$query);
//   }
// }else {
//   $query = "UPDATE ProductionProcess SET status = 'Added' WHERE orderID = 159 AND productID = 3";
//
//   mysqli_query($conn,$query);
// }

//
// $sql = mysqli_query($conn,"");

// if( strtotime("2018-11-20") > strtotime('now') ) {
//   echo ">";
// }else {
//   echo "<";
// }

// echo get_curr_queue($conn,1,4)+1;

// $query = "SELECT Recipe.productID AS ProductID,
//                   Recipe.ingredientID AS Ingredientid,
//                   Ingredient.quantity AS CurrentInventoryQuantity,
//                   Recipe.quantity AS IndivNeedINGQTY
//             FROM `Recipe`
//             INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
//             WHERE Recipe.productID = 1";
//
// $sql = mysqli_query($conn,$query);
//
// while ($row = mysqli_fetch_array($sql)) {
//   print_p($row);
// }
//
// $inv = get_need_inventory3($conn,1,200);
// // $inv = get_need_inv($conn,3,200);
// $count = count($inv);
//
// print_p($inv);
// $d = date("Y-m-d",strtotime("+5 days"));
// echo calcTime($d);

// echo date("Y-m-d",$_SERVER['REQUEST_TIME']);
// for ($i=0; $i < $count; $i++) {
//   for ($j=0; $j < count($inv[$i]); $j++) {
//     $ing = $inv[$i][$j]['ingredientid'];
//     $nid = $inv[$i][$j]['needquantityforPO'];
//     $pro = $inv[$i][$j]['productid'];
//
//     $sql = mysqli_query($conn,"SELECT * FROM Product WHERE productID = $pro");
//     $row = mysqli_fetch_array($sql);
//     $name = $row['name'];
//     $sql1 = mysqli_query($conn,"SELECT * FROM Ingredient WHERE ingredientID = $ing");
//     $rowe = mysqli_fetch_array($sql1);
//     $ingname = $rowe['name'];
//     echo "<div class='row'>";
//       echo "<div class='col'>";
//         echo "$name";
//       echo "</div>";
//       echo "<div class='col'>";
//         echo "$ingname";
//       echo "</div>";
//       echo "<div class='col text-center'>";
//         echo number_format($nid, 2, '.', ',');
//         // echo $nid;
//       echo "</div>";
//     echo "</div>";
//   }
//
//}

// echo check_for_inventory_match($conn,28);
// $datestr = date("Y-m-d",strtotime("+10 days"));
// echo $datestr."<Br />";
// get_timebeforedeadline($conn,$datestr);
// if (isset($_POST['go'])) {
//   $now = new DateTime("now");
//   $t = $_POST['time'];
//   $d = $_POST['date'];
//   // echo $_POST['time']."<br />";
//   // echo $_POST['date']."<br />";
//   $com = date("Y-m-d H:i:s",strtotime("$d $t"));
//   $after = new DateTime($com);
//
//   $diff = $after->diff($now);
//
//   print $diff->format("%H %I %S");
//
//   $new = $diff->format("%H %I %S");
//
//   echo "<br>".datetime_seconds($new);
//
//
// }
// // $dead = date("Y-m-d H:i",strtotime("+5 days +5 hours"));
//
// // echo $dead;
// ?>
<!-- // <form class="" method="post">
//   <input type="date" name="date" max="<?php echo date('Y-m-d'); ?>">
//     <div class="input-group bootstrap-timepicker timepicker">
//         <input name="time" id="timepicker1" type="text">
//     </div>
//
//   <input type="submit" name="go" value="yea">
// </div>
//
// </form> -->


 <?php //echo get_timebeforedeadline($conn,$t); ?>
<!-- // <script type="text/javascript">
//   $('#timepicker1').timepicker({
//       minuteStep: 1,
//       showSeconds: true,
//       showMeridian: false,
//       defaultTime: 'current'
//   });
// </script> -->

<?php
  // print_p(get_productprocess($conn,12));

  // echo count(get_productprocess($conn,12));

//   array (
//         '0'=>'1',
//         '1'=>'3',
//         '2'=>'5',
//         '3'=>'2'
// )
  // print_p(get_machine($conn,2));
  // $i = array();
  //
  // if (empty($i)) {
  //   echo "empty";
  // }else {
  //   echo "pwet";
  // }
  // print_p(get_machine_for_queue($conn,3));
  // $now  = date('Y-m-d');

 // echo $now;
// if (check_emergency($conn,10)) {
//   echo "true";
// }
 ?>
