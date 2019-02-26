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
                      Recipe.quantity*$recipeqty AS NeededIngredientQuantity
                FROM `Recipe`
                INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                WHERE Recipe.productID = $id";

      $sql1 = mysqli_query($conn,$query1);

      while ($rowed = mysqli_fetch_array($sql1)) {
        $prodakid = $rowed['ProductID'];
        $ingredid = $rowed['Ingredientid'];
        $oriingid = $rowed['IndivNeedINGQTY'];
        $ingquant = $rowed['NeededIngredientQuantity'];
        $currinvq = $rowed['CurrentInventoryQuantity'];

        if ($currinvq < $ingquant) {
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
  return gmdate("H:i:s",$seconds);
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
          $currinvq = $rowed['CurrentInventoryQuantity'];

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

function get_need_inventory2($conn,$orderid){
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
                  JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                  WHERE Recipe.productID = $id AND Recipe.quantity*$recipeqty > Ingredient.quantity";
        $sql1 = mysqli_query($conn,$query1);

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
            $needstock[$j][$i]['needquantityforPO'] = $diff;
          }
        }

    }

    return $needstock;
}

function get_need_inventory3($conn,$prodid,$qty){
      $needstock = array();

      $query1 = "SELECT Recipe.productID AS ProductID,
                        Recipe.ingredientID AS Ingredientid,
                        Ingredient.name AS ingname,
                        Ingredient.quantity AS CurrentInventoryQuantity,
                        RawMaterial.supplierID AS supid,
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


          $needstock[$i]['productid'] = $prodakid;
          $needstock[$i]['ingredientid'] = $ingredid;
          $needstock[$i]['ingname'] = $ingrenam;
          $needstock[$i]['supid'] = $supplyid;
          $needstock[$i]['needqty'] = $ingquant;

        }



    return $needstock;
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

          // $sql = mysqli_query($conn,"SELECT JobOrder.dueDate
          //                             FROM JobOrder
          //                             JOIN ProductionProcess ON JobOrder.orderID = ProductionProcess.orderID
          //                             WHERE ProductionProcess.processTypeID = $cont[$j] AND ProductionProcess.machineID = $queuedmach");
          //
          // // store sa array
          // $deadlines = array();
          // while ($row = mysqli_fetch_array($sql)) {
          //   array_push($deadlines,$row[0]);
          //  }
          //
          // // sortsort
          //  function date_sort($a, $b) {
          //      return strtotime($a) - strtotime($b);
          //  }
          //  usort($deadlines, "date_sort");
          //
          // // finding the closest date sa array ng deadlines
          //  echo find_closest_today($deadlines,date('Y-m-d'));
          //
          //  $qr = "UPDATE ProductionProcess SET machineQueue = machineQueue + 1 WHERE machineQueue >= $newqueue ORDER BY machineQueue DESC";
          //
          //      mysqli_query($conn,$qr);

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
    $query = "SELECT machineID FROM Machine WHERE processTypeID = $proc AND status <> 'Under Maintenance'";

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


 ?>
