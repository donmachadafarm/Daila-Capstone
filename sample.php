<?php

include 'includes/sections/header.php';

// pretty print assoc arrays
// print("<pre>".print_r($result,true)."</pre>");

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


function check_for_inventory_match($orderid,$conn){
  $invgreat = 0;

  $query = "SELECT receipt.productID,
                   receipt.quantity
            FROM receipt
            WHERE orderID = $orderid";

  $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){

    $id = $row['productID'];
    $recipeqty = $row['quantity'];

    // echo "<br /><b>prodid:</b> " . $id . "<br />";
    // echo "<b>prodqty on order:</b> " . $recipeqty . "<br />";

    $query1 = "SELECT Recipe.productID AS ProductID,
                      Recipe.ingredientID AS Ingredientid,
                      Recipe.quantity AS NeededIngredientQuantity,
                      Ingredient.quantity AS CurrentInventoryQuantity
                FROM `Recipe`
                INNER JOIN Ingredient ON Ingredient.ingredientID = Recipe.ingredientID
                WHERE Recipe.productID = $id";

      $sql1 = mysqli_query($conn,$query1);


      while ($rowed = mysqli_fetch_array($sql1)) {
        $prodakid = $rowed['ProductID'];
        $ingredid = $rowed['Ingredientid'];
        $ingquant = $rowed['NeededIngredientQuantity'];
        $currinvq = $rowed['CurrentInventoryQuantity'];

        // printf("Product id -> %s <br> Ingredient id -> %s <br> NeededQuantity -> %s <br> CurrentInventoryQuantity -> %s<br /><br />", $prodakid,$ingredid,$ingquant,$rowed[3]);

        if ($currinvq > $ingquant) {
          $invgreat++;
        }
      }
    }
    return $invgreat;
}

function check_time_diff_mins($start_time,$end_time){
  $interval = $start_time->diff($end_time);
  $hours   = $interval->format('%h');
  $minutes = $interval->format('%i');
  return $hours * 60 + $minutes;
}







?>
