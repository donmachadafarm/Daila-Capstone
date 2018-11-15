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

  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM receipt
            WHERE orderID = $orderid";

  $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){

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

      $needstock = array();
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
  $interval = $start_time->diff($end_time);
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

// returns an array(productid,ingredientid,needquantity)
function get_need_inventory($conn,$orderid){
  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM Receipt
            WHERE `orderID` = $orderid";

  $sql = mysqli_query($conn,$query);
  $needstock = array();
  // iterates thru all products in the receipt per joborder
    while($row = mysqli_fetch_array($sql)){

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


      $counter = 0;
      while ($rowed = mysqli_fetch_array($sql1)) {
        $prodakid = $rowed['ProductID'];
        $ingredid = $rowed['Ingredientid'];
        $oriingid = $rowed['IndivNeedINGQTY'];
        $ingquant = $rowed['NeededIngredientQuantity'];
        $currinvq = $rowed['CurrentInventoryQuantity'];

        if ($currinvq < $ingquant) {
          $diff = $ingquant - $currinvq;
          $needstock[$counter]['productid'] = $prodakid;
          $needstock[$counter]['ingredientid'] = $ingredid;
          $needstock[$counter]['needquantityforPO'] = $diff;
        }
        $counter++;
      }
    }
    return $needstock;
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
  $query = "SELECT * FROM Receipt WHERE orderID = $orderid";

  $sqld = mysqli_query($conn,$query);

  $cont = array();
  // per product Iteration
  while ($row = mysqli_fetch_array($sqld)) {
    $orderid = $row['orderID'];
    $produid = $row['productID'];
    $prodqty = $row['quantity'];
    $datenow = date('Y-m-d H:i:s');

    // $row[1] -> product id
    $cont = get_productprocess($conn,$row[1]);
    $checker = 0;
    // per product process iteration
    for ($i=0; $i < count($cont); $i++) {
      // if process has no available machine increment checker
        if (count(get_machine($conn,$cont[$i]))==0) {
          $checker++;
        }
    }

    // if complete lahat ng machines para sa lahat ng process then start production using the first row ng machine id
    if (empty($checker)) {
      // insert into production table  orderid, productid, status, quantity from order, 0 , 0 , 0, start time, 0
      $qry1 = "INSERT INTO Production (orderID,productID,status,quantity,totalGoods,totalYield,totalLost,startTime,endTime)
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

          // insert into productionProcess table all deets product id process type id estimate time to finish per process
          $qry3 = "INSERT INTO ProductionProcess(productID,processTypeID,timeEstimate)
                                          VALUES($produid,$cont[$j],$totaltimeneed)";

          $sql3 = mysqli_query($conn,$qry3);

          // update machine unavailable using machid
          $machidd = get_machine($conn,$cont[$j]);
          $qry4 = "UPDATE Machine SET status = 'Used' WHERE machineID = $machidd[0]";

          $sql4 = mysqli_query($conn,$qry4);

        }
      }
    }else{
      // insert into production table  orderid, productid, status, quantity from order, 0 , 0 , 0, start time, 0
      $qry1 = "INSERT INTO Production (orderID,productID,status,quantity,totalGoods,totalYield,totalLost,startTime,endTime)
                                VALUES($orderid,$produid,'Machine on Queue',$prodqty,0,0,0,'','')";

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

          // insert into productionProcess table all deets product id process type id estimate time to finish per process
          $qry3 = "INSERT INTO ProductionProcess(productID,processTypeID,timeEstimate)
                                          VALUES($produid,$cont[$j],$totaltimeneed)";
          $sql3 = mysqli_query($conn,$qry3);

        }
      }
    }

  }

}

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

function get_productprocess($conn,$prodid){
  $query = "SELECT * FROM ProductProcess WHERE productID = $prodid";

  $sql = mysqli_query($conn,$query);

  $container = array();

  for ($i=0; $i < mysqli_num_rows($sql); $i++) {
    $row = mysqli_fetch_row($sql);
    $container[$i] = $row[1];
  }

  return $container;
}

 ?>
