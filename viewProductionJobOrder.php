<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
    <!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
      echo "<script>window.location='logout.php'</script>";
  }
?>

<!-- put all the contents here  -->

<?php
  // function that checks if the ingredients are enuf to make the products
  function check_for_inventory_level($orderid,$conn){
    $query = "SELECT receipt.productID,
                     receipt.quantity
              FROM receipt
              WHERE orderID = $orderid";

    $sql = mysqli_query($conn,$query);

    for ($i=0; $i < mysqli_num_rows($sql); $i++) {
      $row = mysqli_fetch_array($sql);

      $id = $row['productID'];
      $recipeqty = $row['quantity'];

      // print(" FOR receipt prodid and qty\n<pre>".print_r($row,true)."</pre>");
      $query1 = "SELECT recipe.quantity,
                        recipe.ingredientID
                FROM recipe
                WHERE productID = $id";

          $sql1 = mysqli_query($conn,$query1);

          for($j = 0; $j < mysqli_num_rows($sql1); $j++) {
            // print(" FOR RECEPE ingid and qty\n<pre>".print_r($rowed,true)."</pre>");
            $rowed = mysqli_fetch_array($sql1);
            $ingid = $rowed['ingredientID'];
            $query2 = "SELECT ingredient.quantity
                        FROM ingredient
                        WHERE ingredient.ingredientID = $ingid";

            $sql2 = mysqli_query($conn,$query2);

            $rowing = mysqli_fetch_array($sql2);
            // print("FOR INGREDIENT qty\n<pre>".print_r($rowing,true)."</pre>");
            $currentqty = $rowing['quantity'];
            // echo $currentqty."\n".$recipeqty."\n".$id;


            if ($currentqty < $recipeqty) {
              // return true;
              echo "true";
            }else {
              // return false;
              // echo "false";
              echo $ingid;
            }
          }

        }
  }

  // remove job order conditional submit button
  if(isset($_POST['remove'])){
    $id = $_POST['jo_id'];

    $query = "UPDATE `JobOrder` SET `status` = 'removed' WHERE `orderID` = $id";

    if(mysqli_query($conn,$query)){
      echo "<script>alert('Removed Job Order from list!')</script>";
    }

  }

  // start job order conditional submit button puts the job order in production
  if(isset($_POST['start'])){
    $id = $_POST['jo_id'];

    // insert into production
    $today = date('Y-m-d H:i:s');


  }

 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Job Orders for Production
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Job Order ID</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Date Requested</th>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if($result = mysqli_query($conn,'SELECT JobOrder.orderID AS ID,
                                                        Customer.name AS custname,
                                                        JobOrder.orderDate AS datereq,
                                                        JobOrder.dueDate AS duedate,
                                                        JobOrder.totalPrice AS price,
                                                        JobOrder.type AS type,
                                                        JobOrder.status AS status
                                                FROM JobOrder
                                                INNER JOIN Customer ON JobOrder.customerID =Customer.customerID
                                                WHERE JobOrder.status = "Approved"')){


                    while($row = mysqli_fetch_array($result)){
                        $id = $row['ID'];
                        $name = $row['custname'];
                        $price = $row['price'];
                        $status = $row['status'];
                        $duedate = $row['duedate'];
                        $datereq = $row['datereq'];
                        $type = $row['type'];

                        echo '<tr>';
                          echo '<td class="text-center">';
                              echo $id;
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $name;
                          echo '</td>';

                          echo '<td class="text-center">';
                            echo $datereq;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $duedate;
                          echo'</td>';


                          echo '<td class="text-center">';
                            echo $status;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo '<a href="viewIndivJO.php?id='.$id.'"><button type="button" class="btn btn-primary btn-sm">View Details</button></a>  ';

                            // insert conditional if ingredients are enough
                            // if(check_for_inventory_level($id,$conn)){
                              echo '<a href="#start'.$id.'" data-target="#start'.$id.'" data-toggle="modal"><button type="button" class="btn btn-success btn-sm">Start Production</button></a>  ';
                            // }else {
                              // echo '<button type="button" class="btn btn-sm btn-secondary" disabled>Not enough materials!</button> ';
                            // }
                            // echo '<a href="#remove'.$id.'" data-target="#remove'.$id.'" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm">Remove</button></a>';
                          echo '</td>';

                        echo '</tr>';
                    ?>
                    <div id="start<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="jo_id" value="<?php echo $id; ?>">
                                        <div class="text-center">
                                          <p>
                                            <h6>Start Production?</h6>
                                            <br>
                                            <h6>Note: This action will put the Job Order placed in production to start!</h6>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="start" class="btn btn-primary">Continue</button>
                                            <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>

                    <div id="remove<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="jo_id" value="<?php echo $id; ?>">
                                        <div class="text-center">
                                          <p>
                                            <h6>Remove Job Order?</h6>
                                            <br>
                                            <h6>Note: This action will remove the job order from the list!</h6>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="remove" class="btn btn-primary">Continue</button>
                                            <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <?php

                }
              }
                    ?>
                  <br><br>
                </tbody></table>



        </div>
    </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
