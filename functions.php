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

// returns an array(productid,ingredientid,needquantity)
function get_need_inventory($conn,$orderid){
  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM receipt
            WHERE orderID = $orderid";

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

        $qry = "UPDATE Ingredient SET quantity -= $ingquant WHERE ingredientID = $ingid";

        mysqli_query($conn,$qry);
      }
    }
}

function check_available_machine($conn,$procid){
  $query = "SELECT machineID FROM Machine WHERE status = 'Available' AND processTypeID = '$procid'";

  $sql = mysqli_query($conn,$query);

  $cont = array();

  while($row = mysqli_fetch_array($sql)){
    array_push($cont,$row['machineID']);
  }
  return $cont;
}

function check_available_machines_production($conn,$orderid){
  $query = "SELECT * FROM Receipt WHERE orderID = $orderid";

  $sql = mysqli_query($conn,$query);

  $cont = array();
  $machproc = array();

  $i = 0;

  while ($row = mysqli_fetch_array($sql)) {

    $cont = get_productprocess($conn,$row[1]);

    $counter = count($cont);

    for ($i=0; $i < $counter; $i++) {
      print_p(get_machine($conn,$cont[$i]));
    }
    // print_p($cont);
    // $procar = array();

    // for ($i=0; $i < count($cont); $i++) {
    //   $procid = $cont[$i];
    //
    //   $query1 = "SELECT machineID FROM Machine WHERE status = 'Available' AND processTypeID = $procid";
    //
    //   $sql1 = mysqli_query($conn,$query1);
    //
    //   for ($i=0; $i < mysqli_num_rows($sql1); $i++) {
    //     $row = mysqli_fetch_row($sql1);
    //
    //   }
    //
    //
    //
    // }

  }

}

function get_machine($conn,$proc){
    $query = "SELECT machineID FROM Machine WHERE status = 'Available' AND processTypeID = $proc";

    $sql = mysqli_query($conn,$query);

    $arrmach = array();

    for ($i=0; $i < mysqli_num_rows($sql); $i++) {
      $row = mysqli_fetch_array($sql);

      $arrmach[$i]['machineid'] = $row['machineID'];
    }
    // print_p($arrmach);
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
