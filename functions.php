<?php

// pretty print function
function print_p($value = false, $exit = false, $return=false, $recurse=false) {
    if ($return === true && $exit === true)
        $return = false;
    $tab = str_repeat("&nbsp;", 8);
    if ($recurse == false) {
        $recurse = 0;
        $output = '<div style="width:100%; border: 2px dotted red; background-color: #fbffd6; display: block; padding: 4px;">';
        $backtrace = debug_backtrace();
        $output .= '<b>Line: </b>'.$backtrace[0]['line'].'<br>';
        $output .= '<b>File: </b> '.$backtrace[0]['file'].'<br>';
        $indent = "";
    } else {
        $output = '';
        $indent = str_repeat("&nbsp;", $recurse * 8);
    }
    if (is_array($value)) {
        if ($recurse == false) {
            $output .= '<b>Type: </b> Array<br>';
            $output .= "<br>array (<br>";
        } else {
            $output .= "array (<br>";
        }
        $items = array();
        foreach ($value as $k=>$v) {
            if (is_object($v) || is_array($v))
                $items[] = $indent.$tab."'".$k."'=>".print_p($v, false, true, ($recurse+1));
            else
                $items[] = $indent.$tab."'".$k."'=>".($v===null ? "NULL" : "'".$v."'");
        }
        $output .= implode(',<br>', $items);
        if ($recurse == false)
            $output .= '<br>)';
        else
            $output .= '<br>'.$indent.')';
    } elseif (is_object($value)) {
        if ($recurse == false) {
            $output .= '<b>Type: </b> Object<br>';
            $output .= '<br>object ('.get_class($value).'){'."<br>";
        } else {
            $output .= "object (".get_class($value)."){<br>";
        }

        // needed conditional because base class function dump is protected
        $vars = get_object_vars($value);
        $vars = (is_array($vars) == true ? $vars : array());

        $items = array();
        foreach ($vars as $k=>$v) {
            if (is_object($v) || is_array($v))
                $items[] = $indent.$tab."'".$k."'=>".print_p($v, false, true, ($recurse+1));
            else
                $items[] = $indent.$tab."'".$k."'=>".($v===null ? "NULL" : "'".$v."'");
        }
        $output .= implode(',<br>', $items);
        $vars = get_class_methods($value);
        $items = array();
        foreach ($vars as $v) {
            $items[] = $indent.$tab.$tab.$v;
        }
        $output .= '<br>'.$indent.$tab.'<b>Methods</b><br>'.implode(',<br>', $items);
        if ($recurse == false)
            $output .= '<br>}';
        else
            $output .= '<br>'.$indent.'}';
    } else {
        if ($recurse == false) {
            $output .= '<b>Type: </b> '.gettype($value).'<br>';
            $output .= '<b>Value: </b> '.$value;
        } else {
            $output .= '('.gettype($value).') '.$value;
        }
    }
    if ($recurse == false)
        $output .= '</div>';
    if ($return === false)
        echo $output;
    if ($exit === true)
        die();
    return $output;
}


function array_merge_numeric_values()
{
    $arrays = func_get_args();
    $merged = array();
    foreach ($arrays as $array)
    {
        foreach ($array as $key => $value)
        {
            if ( ! is_numeric($value))
            {
                continue;
            }
            if ( ! isset($merged[$key]))
            {
                $merged[$key] = $value;
            }
            else
            {
                $merged[$key] += $value;
            }
        }
    }
    return $merged;
}

// returns the count ng lahat ng instances ng may kulang ingredients for every product
function check_for_inventory_match($conn,$orderid){
  $invgreat = 0;

  // select all from receipt with orderid
  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM receipt
            WHERE orderID = $orderid";

  $sql = mysqli_query($conn,$query);
    // iterates all in products from order
    while($row = mysqli_fetch_array($sql)){

    // assign productid and qty of product
    $id = $row['productID'];
    $recipeqty = $row['quantity'];

    //
    $query1 = "SELECT Recipe.productID AS ProductID,
                      Recipe.ingredientID AS Ingredientid,
                      Ingredient.quantity AS CurrentInventoryQuantity,
                      Recipe.quantity AS IndivNeedINGQTY,
                      round(Recipe.quantity*$recipeqty) AS NeededIngredientQuantity
                FROM `Recipe`
                INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                WHERE Recipe.productID = $id AND round(Recipe.quantity*$recipeqty) > Ingredient.quantity";

      $sql1 = mysqli_query($conn,$query1);

      // if (mysqli_num_rows($sql1)) {
      //   $invgreat++;
      // }
      while ($r = mysqli_fetch_array($sql1)) {
        if ($r['NeededIngredientQuantity'] > $r['CurrentInventoryQuantity']) {
          $invgreat++;
        }
      }


    }
    return $invgreat;
}

// returns the time difference in minutes
function check_time_diff_mins($start_time,$end_time){
  $e = new DateTime($end_time);
  $s = new DateTime($start_time);
  $interval = $s->diff($e);
  $hours   = $interval->format('%h');
  $minutes = $interval->format('%i');
  return $hours * 60 + $minutes;
}

// datetime to seconds
function datetime_seconds($time){
  $parsed = date_parse($time);
  $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
  return $seconds;
}

// seconds to date time
function seconds_datetime($seconds){
  // return gmdate("H:i:s",$seconds);
  $dt1 = new DateTime("@0");
  $dt2 = new DateTime("@$seconds");
  return $dt1->diff($dt2)->format('%a day/s, %h hours, %i minutes and %s seconds');
}

// count recursive
function getArrCount ($arr, $depth) {
      if (!is_array($arr) || !$depth) return 0;

     $res=count($arr);

      foreach ($arr as $in_ar)
         $res+=getArrCount($in_ar, $depth-1);

      return $res;
  }

  // returns an array(productid,ingredientid,needquantity)
function get_need_inventory($conn,$orderid){
  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM Receipt
            WHERE `orderID` = $orderid";
  $sql = mysqli_query($conn,$query);
  $needstock = array();
  // iterates thru all products in the receipt per joborder
    // while($row = mysqli_fetch_array($sql)){
    for ($j=0; $j < mysqli_num_rows($sql); $j++) {
      $row = mysqli_fetch_array($sql);

      $id = $row['productID'];
      $recipeqty = $row['quantity'];
      $query1 = "SELECT Recipe.productID AS ProductID,
                        Recipe.ingredientID AS Ingredientid,
                        Ingredient.quantity AS CurrentInventoryQuantity,
                        Recipe.quantity AS IndivNeedINGQTY,
                        Recipe.quantity*$recipeqty AS NeededIngredientQuantity
                  FROM `Recipe`
                  INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                  WHERE Recipe.productID = $id";
        $sql1 = mysqli_query($conn,$query1);

        for ($i=0; $i < mysqli_num_rows($sql1); $i++) {
          $rowed = mysqli_fetch_array($sql1);
          // print_p($rowed);
          $prodakid = $rowed['ProductID'];
          $ingredid = $rowed['Ingredientid'];
          $oriingid = $rowed['IndivNeedINGQTY'];
          $ingquant = $rowed['NeededIngredientQuantity'];
          $currinvq = ceil($rowed['CurrentInventoryQuantity']);

          if ($currinvq < $ingquant) {
            // echo $ingquant."<br />";
            $diff = $ingquant - $currinvq;
            $needstock[$j][$i]['productid'] = $prodakid;
            $needstock[$j][$i]['ingredientid'] = $ingredid;
            $needstock[$j][$i]['needquantityforPO'] = $diff;
          }
        }

    }

    // }
    return $needstock;
}

function get_need_inv($conn,$id,$qty){
  $needstock = array();

      $query1 = "SELECT Recipe.productID AS ProductID,
                        Recipe.ingredientID AS Ingredientid,
                        Ingredient.quantity AS CurrentInventoryQuantity,
                        round(Recipe.quantity*$qty) AS NeededIngredientQuantity
                  FROM `Recipe`
                  JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                  WHERE Recipe.productID = $id AND round(Recipe.quantity*$qty) > Ingredient.quantity";

          $sql1 = mysqli_query($conn,$query1);


            for ($i=0; $i < mysqli_num_rows($sql1); $i++) {
              $rowed = mysqli_fetch_array($sql1);
              // print_p($rowed);
              $prodakid = $rowed['ProductID'];
              $ingredid = $rowed['Ingredientid'];
              $ingquant = $rowed['NeededIngredientQuantity'];
              $currinvq = $rowed['CurrentInventoryQuantity'];

                $needstock[$i]['productid'] = $prodakid;
                $needstock[$i]['ingredientid'] = $ingredid;
                $needstock[$i]['needquantityforPO'] = $ingquant;
                $needstock[$i]['currentInventory'] = $currinvq;

            }

    return $needstock;
}

function get_need_inventory2($conn,$orderid){
  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM Receipt
            WHERE `orderID` = $orderid";
  $sql = mysqli_query($conn,$query);
  $prodarr = array();
  $needstock = array();

  // iterates thru all products in the receipt per joborder
    // while($row = mysqli_fetch_array($sql)){
    for ($j=0; $j < mysqli_num_rows($sql); $j++) {
      $row = mysqli_fetch_array($sql);

      $id = $row['productID'];
      $recipeqty = $row['quantity'];

      if (!empty(get_need_inv($conn,$id,$recipeqty))) {
        $prodarr[] = $id;
      }

  }
  // print_p($prodarr);
  for($j = 0; $j < count($prodarr);$j++) {
    $query1 = "SELECT Recipe.productID AS ProductID,
                  Recipe.ingredientID AS Ingredientid,
                  Ingredient.quantity AS CurrentInventoryQuantity,
                  Recipe.quantity AS IndivNeedINGQTY,
                  round(Recipe.quantity*$recipeqty) AS NeededIngredientQuantity
            FROM `Recipe`
            JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
            WHERE Recipe.productID = $prodarr[$j] AND Recipe.quantity*$recipeqty > Ingredient.quantity";

    $sql1 = mysqli_query($conn,$query1);

    if(mysqli_num_rows($sql1)!=0){

      for ($i=0; $i < mysqli_num_rows($sql1); $i++) {
        $rowed = mysqli_fetch_array($sql1);
        // print_p($rowed);
        $prodakid = $rowed['ProductID'];
        $ingredid = $rowed['Ingredientid'];
        $oriingid = $rowed['IndivNeedINGQTY'];
        $ingquant = $rowed['NeededIngredientQuantity'];
        $currinvq = $rowed['CurrentInventoryQuantity'];

        if ($currinvq < $ingquant) {
          // echo $ingquant."<br />";
          $diff = $ingquant - $currinvq;
          $needstock[$j][$i]['productid'] = $prodakid;
          $needstock[$j][$i]['ingredientid'] = $ingredid;
          $needstock[$j][$i]['currentInventory'] = $currinvq;
          $needstock[$j][$i]['needquantityforPO'] = $ingquant;
        }
      }
    }
  }
    return $needstock;
}

function get_need_inventory3($conn,$prodid,$qty){
      $needstock = array();

      $q = "SELECT count(Recipe.ingredientID) as count FROM Recipe WHERE Recipe.productID = '$prodid'";

        $sqlt = mysqli_query($conn,$q);

        $rowee = mysqli_fetch_array($sqlt);

        $count = $rowee['count'];

      $query1 = "SELECT Recipe.productID AS ProductID,
                        Recipe.ingredientID AS Ingredientid,
                        Ingredient.name AS ingname,
                        Ingredient.quantity AS CurrentInventoryQuantity,
                        RawMaterial.supplierID AS supid,
                        RawMaterial.unitOfMeasurement AS uom,
                        Recipe.quantity AS IndivNeedINGQTY,
                        Recipe.quantity*$qty AS NeededIngredientQuantity,
                        Supplier.company AS name,
                        Supplier.duration AS duration
                  FROM `Recipe`
                  JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                  JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                  JOIN RawMaterial ON RawMaterial.rawMaterialID = RMIngredient.rawMaterialID
                  JOIN Supplier ON RawMaterial.supplierID = Supplier.supplierID
                  WHERE Recipe.productID = $prodid AND Recipe.quantity*$qty > Ingredient.quantity";

        $sql1 = mysqli_query($conn,$query1);

          for ($i=0; $i < mysqli_num_rows($sql1); $i++) {
            $rowed = mysqli_fetch_array($sql1);
            // print_p($rowed);

            $prodakid = $rowed['ProductID'];
            $ingredid = $rowed['Ingredientid'];
            $ingrenam = $rowed['ingname'];
            $supplyid = $rowed['supid'];
            $oriingid = $rowed['IndivNeedINGQTY'];
            $ingquant = $rowed['NeededIngredientQuantity'];
            $currinvq = $rowed['CurrentInventoryQuantity'];
            $uom = $rowed['uom'];
            $lack = $ingquant - $currinvq;

            $needstock[$i]['productid'] = $prodakid;
            $needstock[$i]['ingredientid'] = $ingredid;
            $needstock[$i]['ingname'] = $ingrenam;
            $needstock[$i]['supid'] = $supplyid;
            $needstock[$i]['needqty'] = $ingquant;
            $needstock[$i]['lacking'] = $lack;
            $needstock[$i]['uom'] = $uom;

          }

    return $needstock;
}


function get_duedate($conn,$id,$qty){
  $query = "SELECT SUM(timeNeed) FROM ProductProcess WHERE productID = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  $time = $row[0];

  $days=0;

  $daysArr = array();

  $time+=get_currqueuecount($conn);

  $days+=5;

  $inv = get_need_inventory3($conn,$id,$qty);

  foreach ($inv as $key => $value) {
    $daysArr[] = get_suppdur($conn,$inv[$key]['supid']);
  }

  if (!empty($daysArr)) {
    $days+=max($daysArr);
  }

  $deadline = date("Y-m-d",strtotime("+".$days."days +".$time."seconds"));

  return $deadline;

}

function get_currqueuecount($conn){
  $query = "SELECT SUM(timeEstimate) FROM ProductionProcess
              WHERE status = 'Ongoing' OR status = 'Wait'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];



}

function get_username($conn,$id){
  $query = "SELECT givenName FROM User WHERE userID = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];

}

function get_customerName($conn,$id){
  $query = "SELECT company FROM customer WHERE customerID = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];
}

function get_suppname($conn,$id){
    $query = "SELECT * FROM Supplier WHERE supplieriD = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    return $row['company'];

}

function get_suppdur($conn,$id){
    $query = "SELECT * FROM Supplier WHERE supplieriD = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    return $row['duration'];

}

function get_ingname($conn,$id){
    $query = "SELECT * FROM Ingredient WHERE ingredientID = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    return $row['name'];

}

function get_ingunit($conn,$id){
  $query = "SELECT * FROM Ingredient WHERE ingredientID = '$id'";

  $sql = mysqli_query($conn,$query);

  $row = mysqli_fetch_array($sql);

  return $row['name'];

}


// check first if ingredients need are enuf then use This
// function reduces the ingredients table using ingredientid per product * qty in order
function reduce_inventory_rawmats_production($conn,$orderid){
  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM receipt
            WHERE orderID = $orderid";

  $sql = mysqli_query($conn,$query);
  // iterates thru all products in the receipt per joborder
    while($row = mysqli_fetch_array($sql)){

    $id = $row['productID'];
    $recipeqty = $row['quantity'];

    $query1 = "SELECT Recipe.productID AS prodid,
                      Recipe.ingredientID AS ingid,
                      Recipe.quantity*$recipeqty AS reducqty
                FROM `Recipe`
                INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                WHERE Recipe.productID = $id";

      $sql1 = mysqli_query($conn,$query1);


      while ($rowed = mysqli_fetch_array($sql1)) {
        $prodid = $rowed['prodid'];
        $ingid = $rowed['ingid'];
        $ingquant = $rowed['reducqty'];

        $qry = "UPDATE Ingredient SET quantity = quantity-$ingquant WHERE ingredientID = $ingid";

        mysqli_query($conn,$qry);
      }
    }
}

function start_production($conn,$orderid){
  $query = "SELECT Receipt.orderID,
              	   Receipt.productID,
                   Receipt.quantity,
                   JobOrder.dueDate
              FROM Receipt
              JOIN JobOrder ON Receipt.orderID = JobOrder.orderID
              WHERE JobOrder.orderID = $orderid";

  $sqld = mysqli_query($conn,$query);

  $cont = array();
  // per product Iteration on a specific orderid
  while ($row = mysqli_fetch_array($sqld)) {
    $orderid = $row['orderID']; // $row[0]
    $produid = $row['productID']; // $row[1]
    $prodqty = $row['quantity']; // $row[2]
    $datenow = date('Y-m-d H:i:s');
    $jodue = $row[3];

    // $row[1] -> product id // cont is an array ng processes for one product
    $cont = get_productprocess($conn,$row[1]);
    // checker for counter ng mga may used machines sa production
    $checker = 0;
    // per product process iteration
    for ($i=0; $i < count($cont); $i++) {
      // if process has no available machine increment checker
        if (count(get_machine($conn,$cont[$i]))==0) {
          $checker++;
        }
    }

    // if empty ung checker matic queued na ung ung next product in production na based din sa deadline sa queue
    if (empty($checker)) {
      // insert into production table  orderid, productid, status, quantity from order, 0 , 0 , 0, start time, 0
      $qry1 = "INSERT INTO Production (orderID,productID,status,quantity,totalGoods,totalYield,totalLost,startDate,endDate)
                               VALUES ('{$orderid}','{$produid}','Started','{$prodqty}',0,0,0,'{$datenow}','')";

      // run insert (if success continue insert per productprocess)
      if($sql = mysqli_query($conn,$qry1)){
        // iterate thru all process
        for ($j=0; $j < count($cont); $j++) {
          // compute time to finish(product process table time need * product quantity in receipt)
          $qry2 = "SELECT timeNeed FROM ProductProcess WHERE productID = $produid AND processTypeID = $cont[$j]";

            $sql2 = mysqli_query($conn,$qry2);

            $row2 = mysqli_fetch_array($sql2);

          $timeneed = $row2['timeNeed'];

          $totaltimeneed = $timeneed * $prodqty;

          // array of machines for the specific process
          $machidd = get_machine($conn,$cont[$j]);

          $queuedmach = $machidd[array_rand($machidd)];

          if ($j+1 == 1) {
            $stat = 'Ongoing';
          }else {
            $stat = 'Wait';
          }

          // insert into productionProcess table all deets product id process type id estimate time to finish per process
          $qry3 = "INSERT INTO ProductionProcess(orderID,productID,processTypeID,machineID,machineQueue,processSequence,timeEstimate,status)
                                          VALUES($orderid,$produid,$cont[$j],$queuedmach,1,$j+1,$totaltimeneed,'$stat')";

          // update machine unavailable using machid
          $qry4 = "UPDATE Machine SET status = 'Used' WHERE machineID = $queuedmach";

          $sql3 = mysqli_query($conn,$qry3);

          $sql4 = mysqli_query($conn,$qry4);

        }
      }
    }else{
      // ELSE PAG MAY DAPAT NAKA QUEUE NA PROCESS KADA MACHINE USED
      // insert into production table  orderid, productid, status, quantity from order, 0 , 0 , 0, start time, 0
      $qry1 = "INSERT INTO Production (orderID,productID,status,quantity,totalGoods,totalYield,totalLost,startDate,endDate)
                               VALUES ('{$orderid}','{$produid}','Started','{$prodqty}',0,0,0,'','')";

      // run insert (if success continue insert per productprocess)
      if($sql = mysqli_query($conn,$qry1)){
        // iterate thru all process $cont -> array nag hold ng process list ng kada product
        for ($j=0; $j < count($cont); $j++) {
          // compute time to finish(product process table time need * product quantity in receipt)
          $qry2 = "SELECT timeNeed FROM ProductProcess WHERE productID = $produid AND processTypeID = $cont[$j]";

              $sql2 = mysqli_query($conn,$qry2);

              $row2 = mysqli_fetch_array($sql2);

          $timeneed = $row2['timeNeed'];

          // total time need in seconds (timeneed per process * prod qty)
          $totaltimeneed = $timeneed * $prodqty;

          // get all machines with same process ($cont[$j] -> process id) regardless kung taken na siya FOR QUEUE
          $machidd = get_machine_for_queue($conn,$cont[$j]);

          // get a random machine for queue
          $queuedmach = $machidd[array_rand($machidd)];


          // insert into productionProcess table all deets product id process type id estimate time to finish per process
          $qry4 = "INSERT INTO ProductionProcess(orderID,productID,processTypeID,machineID,machineQueue,processSequence,timeEstimate,status)
                                                VALUES($orderid,$produid,$cont[$j],'$queuedmach','',$j+1,$totaltimeneed,'')";

                mysqli_query($conn,$qry4);

          $newqueue = get_curr_queue($conn,$cont[$j],$queuedmach)+1;

          if ($j+1 == 1 && $newqueue == 1) {
            $stat = 'Ongoing';
          }else {
            $stat = 'Wait';
          }

          $qry4 = "UPDATE ProductionProcess SET machineQueue = $newqueue+1,status = '$stat' WHERE orderID = $orderid AND productID = $produid";

                mysqli_query($conn,$qry4);

        }
      }
    }

  }

}

function check_for_out($conn,$orderid){
  $query = "SELECT count(*) FROM ProductionProcess WHERE orderID = $orderid";

  $sql = mysqli_query($conn,$query);

  $row = mysqli_fetch_array($sql);

  $count = $row[0];

  $query = "SELECT count(*) FROM ProductionProcess WHERE status = 'Shipping' AND orderID = $orderid";

  $sql = mysqli_query($conn,$query);

  $row = mysqli_fetch_array($sql);

  $cont = $row[0];

  if ($count == $cont) {
    return true;
  }
  else {
    return false;
  }

}

// checks if there are no more ongoing statuses in orderid
function check_complete_proc($conn,$orderid,$prodid){
  $s = "SELECT count(*) FROM ProductionProcess WHERE orderID = $orderid AND productID = $prodid";

  $q = mysqli_query($conn,$s);

  // count ng lahat ng rows sa isang order id at prodid

  $row1 = mysqli_fetch_array($q);

  $query = "SELECT count(status) FROM ProductionProcess WHERE (status = 'Done' OR status = 'Finish') AND orderID = $orderid AND productID = $prodid";

  $sql = mysqli_query($conn,$query);

  $row2 = mysqli_fetch_array($sql);

  if ($row2[0] == $row1[0]) {
    return true;
  }else {
    return false;
  }

}

// get current queue
function get_curr_queue($conn,$proc,$mach){
  $sql = mysqli_query($conn,"SELECT machineQueue
                              From ProductionProcess
                              WHERE ProductionProcess.processTypeID = $proc AND ProductionProcess.machineID = $mach
                              ORDER BY machineQueue DESC LIMIT 1");

    $row = mysqli_fetch_array($sql);

    return $row[0];
}

// first param array ng dates for deadline second param is for the actual deadline
function find_closest_today($array, $date)
{
    //$count = 0;
    foreach($array as $day)
    {
        //$interval[$count] = abs(strtotime($date) - strtotime($day));
        $interval[] = abs(strtotime($date) - strtotime($day));
        //$count++;
    }

    asort($interval);
    $closest = key($interval);

    return $array[$closest];
}

function get_process_sequence($conn,$prodid){
  $query = "SELECT processTypeID FROM ProductProcess WHERE productID = $prodid ORDER BY processSequence";

  $sql = mysqli_query($conn,$query);

  $seq = array();

  for ($i=0; $i < mysqli_num_rows($sql); $i++) {
    $row = mysqli_fetch_array($sql);

    $seq[$i] = $row['processTypeID'];
  }
  return $seq;

}

// gets machine ids regardless kung may naka used na sa kanila for queueing
function get_machine_for_queue($conn,$proc){
    $query = "SELECT machineID FROM Machine WHERE processTypeID = $proc AND (status <> 'Under Maintenance' OR status <> 'For Maintenance')";

    $sql = mysqli_query($conn,$query);

    $arrmach = array();

    for ($i=0; $i < mysqli_num_rows($sql); $i++) {
      $row = mysqli_fetch_array($sql);

      $arrmach[$i] = $row['machineID'];
    }

    return $arrmach;
}

// gets machines to check if the machines they need are in queue
function get_machine($conn,$proc){
    $query = "SELECT machineID FROM Machine WHERE status = 'Available' AND processTypeID = $proc";

    $sql = mysqli_query($conn,$query);

    $arrmach = array();

    for ($i=0; $i < mysqli_num_rows($sql); $i++) {
      $row = mysqli_fetch_array($sql);

      // check for the hours worked muna conditional ka here if lumagpas na ng 300hrs
      // change mo muna status non bago mo i SELECT ulet
      // yung mga machines para mag update ung status
      // also make an update function para sa machines pag nacheck na
      // kung lalagpas ung hours worked
      $arrmach[$i] = $row['machineID'];
    }

    return $arrmach;
}

// returns the product process in sequence for every productid inserted
function get_productprocess($conn,$prodid){
  $query = "SELECT * FROM ProductProcess WHERE productID = $prodid ORDER BY processSequence";

  $sql = mysqli_query($conn,$query);

  $container = array();

  for ($i=0; $i < mysqli_num_rows($sql); $i++) {
    $row = mysqli_fetch_row($sql);
    $container[$i] = $row[1];
  }

  return $container;
}

function get_prodqty($conn,$prod){
  $query = "SELECT quantity FROM Product WHERE productID = $prod";

  $sql = mysqli_query($conn,$query);

  $row = mysqli_fetch_array($sql);

  return $row[0];

}

function get_prodname($conn,$prod){
  $query = "SELECT name FROM Product WHERE productID = $prod";

  $sql = mysqli_query($conn,$query);

  $row = mysqli_fetch_array($sql);

  return $row[0];

}

function get_prodPrice($conn,$prod){
  $query = "SELECT productPrice FROM Product WHERE productID = $prod";

  $sql = mysqli_query($conn,$query);

  $row = mysqli_fetch_array($sql);

  return $row[0];
}

//gets the max lead time of a raw material of a product
function get_maxlead($conn, $prod){
    $leadTime = 0;
    $query = "SELECT MAX(supplier.duration) as 'Max Lead Time'
                FROM product
                JOIN recipe on product.productID = recipe.productID
                JOIN rmingredient on recipe.ingredientID = rmingredient.ingredientID
                JOIN rawmaterial on rmingredient.rawMaterialID = rawmaterial.rawMaterialID
                JOIN supplier on rawmaterial.supplierID = supplier.supplierID
                WHERE product.productID = $prod";

    $sql = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($sql);

        $leadTime = $row['Max Lead Time'];

    return $leadTime;
}
//get total sales average
function get_total_average($conn, $prod){
    $query = "SELECT product.name, ROUND(AVG(productsales.quantity)) as 'Average'
                FROM product
                JOIN productsales on product.productID = productsales.productID
                WHERE product.productID = $prod ";

    $sql = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($sql);

    $aveSales = $row['Average'];

    return $aveSales;
}
//get total sales average given the date range
function get_range_average($conn, $prod, $start, $end){
    $query = "SELECT product.name, AVG(productsales.quantity) as 'Average'
                FROM product
                JOIN productsales on product.productID = productsales.productID
                JOIN sales on productsales.salesID = sales.salesID
                WHERE product.productID = '$prod'
                AND sales.saleDate BETWEEN '$start' AND '$end'";

    $sql = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($sql);

    $aveSales = $row['Average'];

    return $aveSales;
}
function get_weighted_average($conn, $prod, $month, $year, $weight){
  $query = "SELECT product.name, AVG(productsales.quantity) as 'Average', sales.saleDate as 'Date'
              FROM product
              JOIN productsales on product.productID = productsales.productID
              JOIN sales on productsales.salesID = sales.salesID
              WHERE product.productID = '$prod'
              AND MONTH(sales.saleDate) = '$month'
              AND YEAR(sales.saleDate) = '$year'";

  $sql = mysqli_query($conn, $query);

  $row = mysqli_fetch_array($sql);

  $total = $row['Average'];
  $weightedTotal = ($row['Average']*$weight)/100;

  return $weightedTotal;
}
//get sales of specific year
function get_yearly($conn, $prod, $year){
  $query = "SELECT product.name, AVG(productsales.quantity) as 'Average'
              FROM product
              JOIN productsales on product.productID = productsales.productID
              JOIN sales on productsales.salesID = sales.salesID
              WHERE product.productID = '$prod'
              AND YEAR(sales.saleDate) = '$year'";
  $sql = mysqli_query($conn, $query);

  $row = mysqli_fetch_array($sql);

  $aveSales = $row['Average'];

  return $aveSales;
}
//get sales of specific month
function get_monthly($conn, $prod, $month){
    $query = "SELECT product.name, AVG(productsales.quantity) as 'Average'
                FROM product
                JOIN productsales on product.productID = productsales.productID
                JOIN sales on productsales.salesID = sales.salesID
                WHERE product.productID = '$prod'
                AND MONTH(sales.saleDate) = '$month'";
    $sql = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($sql);

    $aveSales = $row['Average'];

    return $aveSales;
}
//get needed of the product
function get_ingredients($conn, $ingredid){
    $query = "SELECT product.productID as 'pid',
                product.name as 'pname',
                ingredient.ingredientID as 'iid',
                ingredient.name 'iname',
                recipe.quantity as 'quantity',
                recipe.unitOfMeasurement as 'units'
                FROM product
                JOIN recipe ON product.productID = recipe.productID
                JOIN ingredient on recipe.ingredientID = ingredient.ingredientID
                WHERE ingredient.ingredientID = '$ingredid'";
    $sql = mysqli_query($conn, $query);
    $ids = array();
    if (mysqli_num_rows($sql) > 0){
      while($row = mysqli_fetch_assoc($sql)){
        $ids[] = $row;
      }
    }
    return $ids;
}

function get_machinename($conn,$id){
  $query = "SELECT machine.name FROM Machine WHERE machineID = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];
}

function get_processname($conn,$id){
  $query = "SELECT name FROM processType WHERE processTypeID = '$id'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];
}

function update_inventory($conn,$id,$qty,$remarks){
  $user = $_SESSION['userid'];
  $date = date('Y-m-d');

  $query = "SELECT quantity From Product WHERE productID = $id";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $oldqty = $row[0];

  $query = "UPDATE Product SET quantity = $qty WHERE productID = $id";

    $sql = mysqli_query($conn,$query);

  $query = "INSERT INTO AuditTrail(productID,oldQuantity,quantityChange,dateChange,userID,remarks)
              VALUES('$id','$oldqty','$qty','$date','$user','$remarks')";

    $sql = mysqli_query($conn,$query);

}

function get_allorders($conn){
  $first = date('Y-m-01');
  $last  = date('Y-m-t');

  $query = "SELECT count(orderID) FROM JobOrder WHERE orderDate BETWEEN '$first' AND '$last'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];

}

function get_revenue($conn){
  $first = date('Y-m-01');
  $last  = date('Y-m-t');

  $query = "SELECT SUM(payment) FROM Sales WHERE saleDate BETWEEN '$first' AND '$last'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return number_format($row[0]);

}

function get_prodsold($conn){
  $first = date('Y-m-01');
  $last  = date('Y-m-t');

  $query = "SELECT DISTINCT SUM(ProductSales.quantity) FROM ProductSales
              JOIN Sales ON Sales.salesID = ProductSales.salesID
              WHERE Sales.saleDate BETWEEN '$first' AND '$last'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

      echo $row[0];
}

function get_delayedJOrdersCount($conn){
  $now  = date('Y-m-d');

  $query = "SELECT count(*) FROM JobOrder WHERE dueDate > '$now'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];

}

function get_pendingShipping($conn){
  $query = "SELECT count(*) FROM Shipping
              WHERE status <> 'Shipped'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];

}

function get_prodrestockcount($conn){
      $allInventory = mysqli_query($conn, "SELECT product.name AS productname,
                                                  product.quantity AS quantity,
                                                  productType.name AS producttypename,
                                                  product.productPrice,
                                                  product.productID AS ID
                                                  FROM product
                                                  JOIN productType ON product.productTypeID=productType.productTypeID
                                                  WHERE product.custom <> 1
                                                  GROUP BY product.name
                                                  ");
      $count = 0;
      while ($row = mysqli_fetch_array($allInventory)){
          $id = $row['ID'];
          $prodName = $row['productname'];
          $prodType = $row['producttypename'];
          $quantity = $row['quantity'];
          $price = $row['productPrice'];
          $restockingValue = 100;
          $maxLeadTime = get_maxlead($conn, $id);
          $averageSales = get_total_average($conn, $id);
          $reorderPoint = 100+($averageSales*$maxLeadTime);

          if ($reorderPoint>$quantity){
              $count++;
          }

      }

      return $count;

}

function get_ingrrestockcount($conn){
  $result2 = mysqli_query($conn,'SELECT DISTINCT(Ingredient.ingredientID) AS id,
                                         Ingredient.name AS name,
                                         Ingredient.quantity AS quantity,
                                         RawMaterial.unitOfMeasurement AS uom
                                         FROM Ingredient
                                         JOIN RMIngredient ON RMIngredient.ingredientID = Ingredient.ingredientID
                                         JOIN RawMaterial ON RMIngredient.rawMaterialID = RawMaterial.rawMaterialID
                                         ORDER BY ingredient.ingredientID');

  $count=0;
  while($row2 = mysqli_fetch_array($result2)){
    $id2 = $row2['id'];
    $name2 = $row2['name'];
    $qty2 = $row2['quantity'];
    $uom2 = $row2['uom'];
    $products2 = get_ingredients($conn, $id2);
    $total2 = 0;
    $unit2;
    $inNeeded2 = 0;


    // set collapse box for notifs

    foreach($products2 as $prod2){
      $prodID2 = $prod2['pid'];
      $ave2 = ceil(get_total_average($conn, $prodID2));

      $mlt2 = get_maxlead($conn, $prodID2);

      $reorderpoint2 = ($ave2*$mlt2)+100;

      $recipeQuantity2 = $prod2['quantity'];

      $inNeeded2 = ceil($recipeQuantity2*$reorderpoint2);

      $total2 += $inNeeded2;
    }
    $needed2 = ceil($total2-$qty2);

    if ($needed2<0) {
      $needed2=0;
    }
    if ($total2>$qty2){
      $count += 1;
    }

  }

  return $count;
}

function get_pendingPO($conn){
  $query = "SELECT count(*) FROM PurchaseOrder WHERE status = 'Pending'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];
}

function get_numberofmachinesused($conn){
  $query = "SELECT count(*) FROM Machine WHERE status = 'Used'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];
}

function get_numberofmachinesrepair($conn){
  $query = "SELECT count(*) FROM Machine WHERE status = 'Under Maintenance'";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

  return $row[0];
}

function view_machinesrepair($conn){
  $query = "SELECT machine.machineID,
                   machine.name,
                   machine.status,
                   machine.hoursWorked,
                   machine.lifetimeWorked,
                   machine.acquiredDate,
                   processtype.name AS procname
              FROM machine
              INNER JOIN processtype ON machine.processTypeID = processtype.processTypeID
              WHERE machine.status = 'Under Maintenance'";

    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $id = $row['machineID'];
      $name = $row['name'];
      $status = $row['status'];
      $timesused = $row['hoursWorked'];
      $acquiredDate = $row['acquiredDate'];
      $proctype = $row['procname'];

      echo '<tr>';
        echo '<td>';
          echo '<a href="viewEquipmentHistory.php?id='.$id.'">';
            echo $name;
          echo '</a>';
        echo '</td>';

        echo '<td class="text-center">';
          echo $proctype;
        echo'</td>';

        echo '<td class="text-center">';
          echo $status;
        echo'</td>';

        echo '<td class="text-center">';
          echo seconds_datetime($timesused);
        echo'</td>';

      echo '</tr>';
    }
}

function view_production($conn){
  $query = "SELECT orderID, productID, machineID, timeEstimate, processTypeID
              FROM ProductionProcess
              WHERE status = 'Ongoing'";

    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $id = $row['orderID'];
      $proid = $row['productID'];
      $procid = $row['processTypeID'];
      $machine = $row['machineID'];
      $time = $row['timeEstimate'];

      echo '<tr>';
        echo '<td class="text-center">';
          echo $id;
        echo '</td>';

        echo '<td class="text-center">';
          echo get_prodname($conn,$proid);
        echo'</td>';

        echo '<td class="text-center">';
          echo get_processname($conn,$proid);
        echo'</td>';

        echo '<td class="text-center">';
          echo get_machinename($conn,$machine);
        echo'</td>';

        echo '<td class="text-center">';
          echo seconds_datetime($time);
        echo'</td>';

      echo '</tr>';

    }
}

function view_prodinventory($conn){
  $allInventory2 = mysqli_query($conn, "SELECT product.name AS productname,
                                              product.quantity AS quantity,
                                              productType.name AS producttypename,
                                              product.productPrice,
                                              product.productID AS ID
                                              FROM product
                                              JOIN productType ON product.productTypeID=productType.productTypeID
                                              WHERE product.custom <> 1
                                              GROUP BY product.name");

    while ($row = mysqli_fetch_array($allInventory2)){
        $id = $row['ID'];
        $prodName = $row['productname'];
        $prodType = $row['producttypename'];
        $quantity = $row['quantity'];
        $price = $row['productPrice'];
        $restockingValue = 100;
        $maxLeadTime = get_maxlead($conn, $id);
        $averageSales = get_total_average($conn, $id);
        $reorderPoint = 100+($averageSales*$maxLeadTime);
        $needed = $reorderPoint-$quantity;
        $extra = 1;

        if($needed > 100){
            $extra = ceil($needed * .1);
        }

        if ($needed < 0) {
          $needed = 0;
        }

        echo '<tr>';
        echo '<td><a href="viewIndivProduct.php?id='.$id.'" style="color: #000;text-decoration: none;">';
        echo $prodName;
        echo '</a></td>';
        echo '<td>';
        echo $quantity;
        echo '</td>';
        echo '<td>';
        echo $prodType;
        echo'</td>';
        echo '<td>';
        echo $price;
        echo'</td>';

        echo '<td class="text-center">';
        echo '<a href="makeJobOrder.php?ids='.$id.'&name='.$prodName.'&val='.$needed.'"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button> </a> ';
        if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 102) {
          echo "<a href='#edit".$id."' data-target='#edit".$id."'data-toggle='modal' class='btn btn-warning btn-sm' style='color:white'>
                  <i class='fa fa-edit'></i>
                  </a>";

          ?>
          <div id="edit<?php echo $id; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">
                  <form method="post">
                      <div class="modal-content">

                          <div class="modal-header">
                              <h4>Edit inventory</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>

                          <div class="modal-body">
                              <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                              <div class="text-center">
                                <p>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <!-- <label class="col-sm-2 col-form-label">Quantity:</label> -->
                                    </div>
                                    <div class="col-md-12">
                                      <input class="form-control" type="number" name="qty" value="" placeholder="Quantity">
                                    </div>
                                  </div>
                                </p>
                              </div>
                              <div class="modal-footer">
                                  <button type="submit" name="edit" class="btn btn-primary">Continue</button>
                                  <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                              </div>
                          </div>
                  </form>
                  </div>
              </div>
          </div>

          <?php

       }

        echo '</td>';
        echo '</tr>';

    }
}


function get_timebeforedeadline($conn,$dl,$sec){
  return date("Y-m-d h:i:s",strtotime($dl) - $sec - 432000);
}

function view_jo($conn){
  $result = mysqli_query($conn,'SELECT JobOrder.orderID AS ID,
                                          Customer.customerID AS custid,
                                          Customer.company AS custname,
                                          JobOrder.orderDate AS datereq,
                                          JobOrder.dueDate AS duedate,
                                          JobOrder.type AS type,
                                          JobOrder.status AS status
                                  FROM JobOrder
                                  INNER JOIN Customer ON JobOrder.customerID = Customer.customerID
                                  WHERE JobOrder.status = "Pending for approval" OR JobOrder.status = "Paid"
                                  ORDER BY JobOrder.dueDate DESC');


      while($row = mysqli_fetch_array($result)){
          $id = $row['ID'];
          $cusid = $row['custid'];
          $name = $row['custname'];
          $status = $row['status'];
          $duedate = $row['duedate'];
          $datereq = $row['datereq'];
          $type = $row['type'];

          echo '<tr>';

            echo '<td class="text-center">';
              echo $duedate;
            echo'</td>';

            echo '<td class="text-center">';
              echo $name;
            echo '</td>';

            echo '<td class="text-center">';
              echo $datereq;
            echo'</td>';

            echo '<td class="text-center">';
              echo $type;
            echo'</td>';

            echo '<td class="text-center">';
              echo $status;
            echo'</td>';

          echo '</tr>';
        }
}

function view_po($conn){
  $result = mysqli_query($conn,'SELECT Supplier.company AS suppName,
              PurchaseOrder.purchaseOrderID AS id,
              PurchaseOrder.totalPrice AS price,
              PurchaseOrder.orderDate AS date,
              PurchaseOrder.deadline AS deadline,
              PurchaseOrder.status AS status
            FROM PurchaseOrder
            INNER JOIN Supplier ON PurchaseOrder.supplierID =Supplier.supplierID
            WHERE PurchaseOrder.status <> "removed" AND PurchaseOrder.status <> "Completed!"
            ORDER BY status DESC');


      while($row = mysqli_fetch_array($result)){
          $id = $row['id'];
          $name = $row['suppName'];
          $price = $row['price'];
          $status = $row['status'];
          $date = $row['date'];
          $deadline = $row['deadline'];

          echo '<tr>';

            echo '<td class="text-center">';
              echo $deadline;
            echo'</td>';

            echo '<td class="text-center">';
                echo $name;
            echo '</td>';

            echo '<td class="text-right">';
              echo number_format($price,2);
            echo '</td>';

            echo '<td class="text-center">';
              echo $date;
            echo'</td>';

            echo '<td class="text-center">';
              echo $status;
            echo'</td>';

          echo '</tr>';
        }
    }

function get_JODetails($conn,$id){
    $query = "SELECT product.name AS pName,
                     producttype.name AS type,
                     receipt.quantity,
                     product.productPrice,
                     receipt.subTotal AS total,
                     jobOrder.orderDate
              FROM receipt
              JOIN product on receipt.productID=product.productID
              JOIN producttype on product.productTypeID=producttype.productTypeID
              JOIN jobOrder on receipt.orderID = jobOrder.orderID
              WHERE receipt.orderID=$id";

    $result = mysqli_query($conn,$query);

    $data = array();

    while ($row = mysqli_fetch_array($result)){

        $product = $row['pName'];
        $quantity = $row['quantity'];
        $total = $row['total'];
        $date = $row['orderDate'];

        $data['productName'] = $product;
        $data['quantity'] = $quantity;
        $data['total'] = $total;
        $data['date'] = $date;

      }

      return $data;
  }

function get_JODetail($conn,$id){
  $data = array();

      $query = "SELECT product.name AS pName,
                       producttype.name AS type,
                       receipt.quantity,
                       product.productPrice,
                       receipt.subTotal AS total,
                       jobOrder.orderDate,
                       jobOrder.customerID,
                       jobOrder.orderID
                FROM receipt
                JOIN product on receipt.productID=product.productID
                JOIN producttype on product.productTypeID=producttype.productTypeID
                JOIN jobOrder on receipt.orderID = jobOrder.orderID
                WHERE receipt.orderID=$id";

      $result = mysqli_query($conn,$query);

      for ($i=0; $i < mysqli_num_rows($result); $i++) {
        $row = mysqli_fetch_array($result);

        $data[] = $row;
      }

        return $data;
    }
function get_PODetail($conn,$id){
  $data = array();

      $query = "SELECT RawMaterial.rawMaterialID AS rmid,
                       RawMaterial.name AS rmname,
                       RawMaterial.pricePerUnit AS ppu,
                       RawMaterial.supplierID AS suid,
                       POItem.purchaseOrderID AS poid,
                       POItem.quantity AS qty,
                       POItem.subTotal AS sub,
                       POItem.status AS status,
                       POItem.unitOfMeasurement AS uom,
                       PurchaseOrder.orderDate AS odate
                FROM POItem
                INNER JOIN RawMaterial ON POItem.rawMaterialID = RawMaterial.rawMaterialID
                INNER JOIN PurchaseOrder ON POItem.purchaseOrderId = PurchaseOrder.purchaseOrderID
                WHERE PurchaseOrder.purchaseOrderID =$id";

      $result = mysqli_query($conn,$query);

      for ($i=0; $i < mysqli_num_rows($result); $i++) {
        $row = mysqli_fetch_array($result);

        $data[] = $row;
      }

        return $data;
    }
 ?>
