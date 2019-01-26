<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
    <!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
      echo "<script>window.location='logout.php'</script>";
  }
?>

<?php

  if(isset($_POST['add'])){
    $pid = $_POST['prodid'];
    $yield = $_POST['yield'];
    $good = $_POST['good'];
    $loss = $_POST['yield'] - $_POST['good'];
    $ordid = $_POST['orderID'];
    $datef = date('Y-m-d H:i:s');

    // add qty to product inventory using ['good']
    $query = "UPDATE Product SET quantity = quantity + $good WHERE productID = $pid";

    mysqli_query($conn,$query);
    // update production info total good, total yield, total loss, end date, status = finished
    $query1 = "UPDATE Production SET status = 'Finished', totalYield = $yield, totalGoods = $good, totalLost = $loss, endDate = '$datef' WHERE productID = $pid AND orderID = $ordid";

    mysqli_query($conn,$query1);
    // machines used = available and timesused++
    // check first if bakante na lahat ng machines wala ng queue
    $query2 = "SELECT machineID FROM ProductionProcess WHERE productID = $pid";

      $sql = mysqli_query($conn,$query2);

      while ($row = mysqli_fetch_array($sql)) {
        $query3 = "UPDATE Machine SET status = 'Available', timesUsed = timesUsed + 1 WHERE machineID = $row[0]";

        mysqli_query($conn,$query3);
      }

      $query = "UPDATE ProductionProcess SET status = 'Added' WHERE orderID = $ordid AND productID = $pid";

      mysqli_query($conn,$query);


    // reorder the queue for the waiting machines
    $query = "SELECT * FROM ProductionProcess WHERE processSequence = 1 AND status = 'Wait' AND productID = $pid ORDER BY machineQueue DESC LIMIT 1";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $nextorder = $row['orderID'];
    if(mysqli_num_rows($sql)>0){
      $query = "UPDATE ProductionProcess SET machineQueue = machineQueue - 1 WHERE productID = $pid";

        mysqli_query($conn,$query);


      $query = "UPDATE ProductionProcess SET status = 'Ongoing' WHERE orderID = $nextorder AND productID = $pid AND processSequence = 1";

        mysqli_query($conn,$query);
    }

    $query = "SELECT * FROM JobOrder WHERE orderID = $ordid";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    if (check_for_out($conn,$ordid)) {
      if($row['type'] == 'Made to Order'){
        $query = "UPDATE JobOrder SET status = 'For Out' WHERE orderID = $ordid";

        mysqli_query($conn,$query);
      }else {
        $query = "UPDATE JobOrder SET status = 'Finished' WHERE orderID = $ordid";

        mysqli_query($conn,$query);
      }
    }

      echo "<script>
        alert('Products are added to inventory!');
            </script>";
        // window.location.replace('viewProductionSchedule.php');




  }

  if(isset($_POST['check'])){
    $ordid = $_POST['order_id'];
    $macid = $_POST['mach_id'];
    $proid = $_POST['prod_id'];
    $stats = $_POST['status'];


    // update the production process for that row ng specific order machine id at productid  "Ongoing" - > "Done"
    $query = "UPDATE ProductionProcess SET status = 'Done' WHERE orderID = $ordid AND machineID = $macid AND productID = $proid AND status = 'Ongoing'";

    mysqli_query($conn,$query);

    // GETS THE NEXT MACHINE FOR THE SEQUENCE
    $sql = mysqli_query($conn,"SELECT machineID FROM ProductionProcess WHERE orderID = $ordid AND productID = $proid AND status = 'Wait' ORDER BY processSequence LIMIT 1");

    $row = mysqli_fetch_array($sql);

    // CHECKS IF THERE IS STILL SOME MACHINE IN QUEUE FOR THE PRODUCTION PROCESS
    if(isset($row)){
        $query = "UPDATE ProductionProcess SET status = 'Ongoing' WHERE orderID = $ordid AND machineID = $row[0] AND productID = $proid AND status = 'Wait'";

        mysqli_query($conn,$query);
    }else {
        $sql = mysqli_query($conn,"SELECT machineID FROM ProductionProcess WHERE orderID = $ordid AND productID = $proid AND status = 'Done' ORDER BY processSequence DESC LIMIT 1");

        $row = mysqli_fetch_array($sql);

        mysqli_query($conn,"UPDATE ProductionProcess SET status = 'Finish' WHERE orderID = $ordid AND machineID = $row[0] AND productID = $proid AND status = 'Done'");

    }
  }


  if(isset($_POST['delayed'])){

  }

 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header"><br><br>
                Production Schedule for <?php echo date("F j, Y"); ?>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Machine</th>
                    <th class="text-center">Process</th>
                    <th class="text-center">Order ID</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Estimated Time</th>
                    <!-- <th class="text-center">Start Time</th> -->
                    <!-- <th class="text-center">End Time</th> -->
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if($result = mysqli_query($conn,'SELECT ProductionProcess.machineID, Machine.name AS name, JobOrder.dueDate, ProductionProcess.orderID,
                                                        ProductionProcess.productID, Product.name AS pname, ProcessType.name as ptname,
                                                        ProductionProcess.timeEstimate, ProductionProcess.status
                                                    FROM `ProductionProcess`
                                                    JOIN JobOrder ON ProductionProcess.orderID = JobOrder.orderID
                                                    JOIN Machine ON Machine.machineID = ProductionProcess.machineID
                                                    JOIN Product ON ProductionProcess.productID = Product.productID
                                                    JOIN ProcessType ON ProcessType.processTypeID = ProductionProcess.processTypeID
                                                    WHERE ProductionProcess.status = "Ongoing" OR ProductionProcess.status = "Finish"
                                                    ORDER BY JobOrder.dueDate ASC')){


                    while($row = mysqli_fetch_array($result)){
                        $id = $row['productID'];

                        echo '<tr>';
                          echo '<td class="text-center">';
                              echo $row['dueDate'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['name'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['ptname'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['orderID'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['pname'];
                          echo'</td>';

                          echo '<td class="text-center">';
                              echo seconds_datetime($row['timeEstimate']);
                          echo'</td>';
                          //
                          // echo '<td class="text-center">';
                          //     echo "starttime";
                          // echo'</td>';
                          //
                          // echo '<td class="text-center">';
                          //     echo "endtime";
                          // echo'</td>';

                          echo '<td class="text-center">';
                            // echo '<a href="#delay'.$id.'" data-target="#delay'.$id.'" data-toggle="modal"><button type="button" class="btn btn-warning btn-sm">Delay</button></a>';
                              if (!check_complete_proc($conn,$row['orderID'],$row['productID'])) {
                                // finish button na mag sabi tapos na process
                                echo '<a href="#check'.$id.'" data-target="#check'.$id.'" data-toggle="modal">
                                  <button type="button" class="btn btn-primary btn-sm">
                                    Next
                                  </button></a>  ';
                              } else {
                                // finish button for final add ng product
                                echo '<a href="#finish'.$id.'" data-target="#finish'.$id.'" data-toggle="modal">
                                  <button type="button" class="btn btn-success btn-sm">
                                    Finish
                                  </button></a>  ';
                              }
                          echo '</td>';

                        echo '</tr>';
                    ?>
                    <div id="delay<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="prodid" value="<?php echo $id; ?>">
                                        <input type="hidden" name="ordeid" value="<?php echo $row['orderID']; ?>">
                                        <input type="hidden" name="machid" value="<?php echo $row['machineID']; ?>">

                                        <div class="text-center">
                                          <p>
                                            <h6>Delayed?</h6>
                                            <br>
                                            <h6>Note: This action will put the Job Order in production!</h6><br>
                                            <input type="time" name="time">
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="delayed" class="btn btn-primary">Continue</button>
                                            <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>

                    <div id="check<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <form method="post">
                              <div class="modal-content">

                                  <div class="modal-header">
                                      <h4>Notice</h4>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>

                                  <div class="modal-body">
                                      <input type="hidden" name="order_id" value="<?php echo $row['orderID']; ?>">
                                      <input type="hidden" name="mach_id" value="<?php echo $row['machineID']; ?>">
                                      <input type="hidden" name="prod_id" value="<?php echo $row['productID']; ?>">
                                      <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                                      <div class="text-center">
                                        <p>
                                          <h6>Finished?</h6>
                                          <br>
                                          <h6>Note: This action will continue to the next product process!</h6><br>

                                        </p>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="submit" name="check" class="btn btn-primary">Continue</button>
                                          <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                  </div>
                          </form>
                        </div>
                    </div>
                  </div>

                  <div id="finish<?php echo $id; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                          <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                              <!-- Modal content-->
                              <div class="modal-content">

                                  <div class="modal-header">
                                      <h4>Notice</h4>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>

                                  <div class="modal-body">
                                      <input type="hidden" name="prodid" value="<?php echo $row['productID']; ?>">
                                      <input type="hidden" name="orderID" value="<?php echo $row['orderID']; ?>">

                                      <?php
                                        $oi = $row['orderID'];
                                        $query = "SELECT Product.name,Receipt.quantity FROM Receipt JOIN Product ON Product.productID = Receipt.productID WHERE Product.productID = $id AND Receipt.orderID = $oi";

                                        $sql = mysqli_query($conn,$query);

                                        $row = mysqli_fetch_array($sql);
                                       ?>
                                      <div>
                                        <p>
                                          <h5>Finished production for <strong><?php echo $row['name']; ?>?</strong></h5>
                                          <br>
                                          <h5>Order needs: <?php echo round($row['quantity']+($row['quantity']*0.01)); ?></h5>
                                        </p>
                                      </div>
                                      <label>Total Yield:</label></br>
                                        <input type="number" name="yield" class="form-control" required>
                                      </br>
                                      <label>Total Good:</label></br>
                                        <input type="number" name="good" class="form-control" required>
                                      </br>
                                      <small>items less than 100 will not have 1% extra</small>
                                      <!-- <label>Total Loss:</label></br>
                                        <input type="number" name="loss" class="form-control" required>
                                      </br> -->
                                      <div class="modal-footer">
                                          <button type="submit" name="add" class="btn btn-primary">Confirm</button>
                                          <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Cancel</button>
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
                </tbody>

              </table>




    </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
