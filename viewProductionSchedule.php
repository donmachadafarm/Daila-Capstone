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

    /*
        next lines are for adding into shipping
    */


        // update production info total good, total yield, total loss, end date, status = finished
        $query1 = "UPDATE Production SET status = 'Finished', totalYield = $yield, totalGoods = $good, totalLost = $loss, endDate = '$datef' WHERE productID = $pid AND orderID = $ordid";
        mysqli_query($conn,$query1);

        // machines used = available and hoursworked and lifetimeworked
        // check first if bakante na lahat ng machines wala ng queue
        $query2 = "SELECT machineID FROM ProductionProcess WHERE productID = $pid AND orderID = $ordid";

          $sql = mysqli_query($conn,$query2);

          while ($row = mysqli_fetch_array($sql)) {
            $mid = $row['machineID'];

            if (check_machine($conn,$mid)) {
              $query3 = "UPDATE Machine SET status = 'For Maintenance' WHERE machineID = $mid";

              mysqli_query($conn,$query3);
            }else {
              $query3 = "UPDATE Machine SET status = 'Available' WHERE machineID = $mid";

              mysqli_query($conn,$query3);
            }
          }

          $query = "UPDATE ProductionProcess SET status = 'Shipping', machineQueue = 0 WHERE orderID = $ordid AND productID = $pid";

            mysqli_query($conn,$query);

          $query = "INSERT INTO Shipping (orderID,productID,shippingQuantity,shippedQuantity,status) VALUES ('{$ordid}','{$pid}','$good',0,'Pending')";

            mysqli_query($conn,$query);

    /*
        next lines are for reordering the queue update the sequence
        and statuses of machines and production
    */

        // reorder the queue for the waiting machines
        // this query selects the next product in the same job order
        $query = "SELECT * FROM ProductionProcess WHERE processSequence = 1 AND status = 'Wait' ORDER BY machineQueue DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

        $nextorder = $row['orderID'];
        $pid = $row['productID'];

        if(mysqli_num_rows($sql)>0){
          $query = "UPDATE ProductionProcess SET machineQueue = machineQueue - 1 WHERE productID = $pid AND orderID = $nextorder";

            mysqli_query($conn,$query);

            // query for first process set status = 'ongoing'
          $query = "UPDATE ProductionProcess SET status = 'Ongoing' WHERE orderID = $nextorder AND productID = $pid AND processSequence = 1";

            mysqli_query($conn,$query);

          $sq = mysqli_query($conn,"SELECT machineID FROM ProductionProcess WHERE productID = $pid AND orderID = $nextorder");

          while ($row = mysqli_fetch_array($sq)) {
            $maid = $row['machineID'];

            $query = "UPDATE Machine SET status = 'Used' WHERE machineID = $maid";

            mysqli_query($conn,$query);
          }

        }

    /*
        next lines are for checking if job order is ready
        for out (invoice) if all products are done in production and ready for adding
    */

          $query = "SELECT * FROM JobOrder WHERE orderID = $ordid";

          $sql = mysqli_query($conn,$query);

          $row = mysqli_fetch_array($sql);

          if (check_for_out($conn,$ordid)) {

              $query = "UPDATE JobOrder SET status = 'Shipping' WHERE orderID = $ordid";

              mysqli_query($conn,$query);

          }

            echo "<script>
              alert('Products are now for Shipping!');
                  </script>";
              // window.location.replace('viewProductionSchedule.php');
  }

  if(isset($_POST['check'])){
    $ordid = $_POST['order_id'];
    $macid = $_POST['mach_id'];
    $proid = $_POST['prod_id'];
    $stats = $_POST['status'];

    echo $proid;


    if (!check_emergency($conn,$macid)) {
      $qry = "SELECT timeEstimate as t FROM ProductionProcess WHERE orderID = $ordid AND machineID = $macid AND productID = $proid";
      $sql = mysqli_query($conn,$qry);
      $row = mysqli_fetch_array($sql);
      $time = $row['t'];

      // update the production process for that row ng specific order machine id at productid  "Ongoing" - > "Done"
      $query = "UPDATE ProductionProcess SET status = 'Done',machineQueue = machineQueue - 1 WHERE orderID = $ordid AND machineID = $macid AND productID = $proid AND status = 'Ongoing'";

      mysqli_query($conn,$query);

      // adds the timesworked
      mysqli_query($conn,"UPDATE Machine SET hoursWorked = hoursWorked + $time, lifetimeWorked = lifetimeWorked + $time WHERE machineID = $macid");

      // readjust the queue
      $query = "SELECT * FROM ProductionProcess WHERE processSequence = 1 AND status = 'Wait' ORDER BY machineQueue DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

      $nextorder = $row['orderID'];
      $pid = $row['productID'];

      if(mysqli_num_rows($sql)>0){
          // query for first process set status = 'ongoing'
        $query = "UPDATE ProductionProcess SET status = 'Ongoing' WHERE orderID = $nextorder AND productID = $pid AND processSequence = 1";

          mysqli_query($conn,$query);

        // $sq = mysqli_query($conn,"SELECT machineID FROM ProductionProcess WHERE productID = $pid AND orderID = $nextorder");
        //
        // while ($row = mysqli_fetch_array($sq)) {
        //   $maid = $row['machineID'];
        //   $query = "UPDATE Machine SET status = 'Used' WHERE machineID = $maid";
        //
        //   mysqli_query($conn,$query);
        // }

      }

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

    }else {
      echo "<script>alert('ERROR MACHINE $macid UNDER MAINTENANCE');</script>";
    }

  }

  if(isset($_POST['delay'])){
    $ordid = $_POST['order_id'];
    $macid = $_POST['mach_id'];
    $proid = $_POST['prod_id'];
    $stats = $_POST['status'];
    $date = $_POST['date'];
    $t = $_POST['time'];
    $deadline = $_POST['deadline'];

    $delay = date("Y-m-d H:i:s",strtotime("$date $t"));

    $datestart = new DateTime($deadline);
    $dateend = new DateTime($delay);

    $datediff = $datestart->diff($dateend);

    $diffinsec = $datediff->format("%S");

    if (check_emergency($conn,$macid)) {
      echo "<script>alert('ERROR: MACHINE UNDER MAINTENANCE');</script>";
    }else {

      $qry = "SELECT timeEstimate as t FROM ProductionProcess WHERE orderID = $ordid AND machineID = $macid AND productID = $proid";
      $sql = mysqli_query($conn,$qry);
      $row = mysqli_fetch_array($sql);
      $time = $row['t'];
      $time+=$diffinsec;

      // update the production process for that row ng specific order machine id at productid  "Ongoing" - > "Done"
      $query = "UPDATE ProductionProcess SET status = 'Done' WHERE orderID = $ordid AND machineID = $macid AND productID = $proid AND status = 'Ongoing'";

      mysqli_query($conn,$query);

      // adds the timesworked
      mysqli_query($conn,"UPDATE Machine SET hoursWorked = hoursWorked + $time, lifetimeWorked = lifetimeWorked + $time WHERE machineID = $macid");


      // DONE UPDATING STATUSES FOR ONE PRODUCT PROCESS
      // SUGGESTION : REDUCE MACHINE QUEUE AFTER UPDATE

      // readjust the queue
      $query = "SELECT * FROM ProductionProcess WHERE processSequence = 1 AND status = 'Wait' ORDER BY machineQueue DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

      $nextorder = $row['orderID'];
      $pid = $row['productID'];

      if(mysqli_num_rows($sql)>0){
          // query for first process set status = 'ongoing'
        $query = "UPDATE ProductionProcess SET status = 'Ongoing' WHERE orderID = $nextorder AND productID = $pid AND processSequence = 1";

          mysqli_query($conn,$query);

        $sq = mysqli_query($conn,"SELECT machineID FROM ProductionProcess WHERE productID = $pid AND orderID = $nextorder");

        // while ($row = mysqli_fetch_array($sq)) {
        //   $maid = $row['machineID'];
        //   $query = "UPDATE Machine SET status = 'Used' WHERE machineID = $maid";
        //
        //   mysqli_query($conn,$query);
        // }

      }


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
                    <!-- <th class="text-center">Process</th> -->
                    <th class="text-center">Order ID</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Early end time</th>
                    <th class="text-center">Late start time</th>
                    <?php if ($_SESSION['userType'] == '103' || $_SESSION['userType'] == '104'): ?>
                      <th class="text-center">Action</th>
                    <?php endif; ?>


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
                        $deadline = $row['dueDate'];
                        $timeest = $row['timeEstimate'];
                        $machine = $row['machineID'];


                        echo '<tr>';
                          echo '<td class="text-center">';
                              echo date("F-d-Y",strtotime($deadline));
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['name'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['orderID'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $row['pname'];
                          echo'</td>';

                          echo '<td class="text-center">';
                              echo date("F-d-Y h:i A",strtotime("+$timeest seconds"));
                          echo'</td>';

                          echo '<td class="text-center">';
                              echo get_timebeforedeadline($conn,$deadline,$timeest);
                          echo'</td>';

                        if ($_SESSION['userType'] == '103' || $_SESSION['userType'] == '104') {
                              echo '<td class="text-center">';
                                if (!check_complete_proc($conn,$row['orderID'],$row['productID'])) {
                                    // finish button na mag sabi tapos na process
                                    echo '<a href="#check'.$id.$machine.'" data-target="#check'.$id.$machine.'" data-toggle="modal">
                                      <button type="button" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Next Process">
                                        <i class="fas fa-arrow-right"></i>
                                      </button></a>  ';
                                    echo '<a href="#delay'.$id.$machine.'" data-target="#delay'.$id.$machine.'" data-toggle="modal">
                                      <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Delay" style="color:white">
                                        <i class="fas fa-clock"></i>
                                      </button></a>  ';
                                  } else {
                                    // finish button for final add ng product
                                    echo '<a href="#finish'.$id.'" data-target="#finish'.$id.'" data-toggle="modal">
                                      <button type="button" class="btn btn-success btn-sm"  data-toggle="tooltip" data-placement="top" title="Finish Process">
                                        Finish
                                      </button></a>  ';
                                  }
                              echo '</td>';
                            }

                        echo '</tr>';
                    ?>

                    <div id="check<?php echo $id.$machine; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <form method="post">
                              <div class="modal-content">

                                  <div class="modal-header">
                                      <h4>Notice</h4>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  </div>

                                  <div class="modal-body">
                                      <input type="hidden" name="order_id" value="<?php echo $row['orderID']; ?>">
                                      <input type="hidden" name="mach_id" value="<?php echo $machine; ?>">
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

                  <div id="delay<?php echo $id.$machine; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <form method="post">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4>Delayed finish?</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="order_id" value="<?php echo $row['orderID']; ?>">
                                    <input type="hidden" name="mach_id" value="<?php echo $row['machineID']; ?>">
                                    <input type="hidden" name="prod_id" value="<?php echo $row['productID']; ?>">
                                    <input type="hidden" name="status" value="<?php echo $row['status']; ?>">
                                    <input type="hidden" name="deadline" value="<?php echo $row['dueDate']; ?>">

                                    <div class="text-center">
                                      <p>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label class="col-form-label">Delay:</label>
                                          </div>
                                          <div class="col-md-5">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                                <div class="input-group-append">
                                                  <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-5">
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input name="time" id="timepicker1" type="text" class="form-control input-small">
                                                <div class="input-group-append">
                                                  <div class="input-group-text"><i class="fas fa-clock"></i></div>
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                      </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="delay" class="btn btn-primary">Continue</button>
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
                                        $query = "SELECT Product.name,Receipt.quantity FROM Receipt
                                                    JOIN Product ON Product.productID = Receipt.productID
                                                    WHERE Product.productID = $id AND Receipt.orderID = $oi";

                                        $sql = mysqli_query($conn,$query);

                                        $row = mysqli_fetch_array($sql);
                                       ?>
                                      <div>
                                        <p>
                                          <h5>Finished production for <strong><?php echo $row['name']; ?>?</strong></h5>
                                          <br>
                                          <h5>Order needs: <?php echo round($row['quantity']); ?>
                                          </h5>
                                        </p>
                                      </div>
                                      <label>Total Yield:</label></br>
                                        <input type="number" name="yield" value="<?php if ($row['quantity']<100) { echo round($row['quantity']); }else {echo round($row['quantity']+($row['quantity']*0.01)); } ?>" class="form-control" readonly>
                                      </br>
                                      <label>Total Good:</label></br>
                                        <input min="1" type="number" name="good" value="<?php if ($row['quantity']<100) { echo round($row['quantity']); }else {echo round($row['quantity']+($row['quantity']*0.01)); } ?>" class="form-control" required>
                                      </br>
                                      <small>items less than 100 will not have 1% extra</small>
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


        <br><br><h3><b>Status:</b></h3>
<div class="row">
  <div class="col-lg-12">
      <table class="display table table-hover table-borderless">
        <thead>
          <tr>
            <th>Due Date</th>
            <th>Order ID</th>
            <th>Product</th>
            <th>Machine</th>
            <th>Process</th>
            <th>Sequence</th>
            <th>Time Estimate</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT ProductionProcess.productID,
                           ProductionProcess.machineID,
                           ProductionProcess.orderID,
                           ProductionProcess.processTypeID,
                           ProductionProcess.processSequence,
                           ProductionProcess.timeEstimate,
                           ProductionProcess.status,
                           jobOrder.dueDate
                      FROM ProductionProcess
                      JOIN JobOrder ON ProductionProcess.orderID = JobOrder.orderID
                      WHERE ProductionProcess.status <> 'Shipping'
                      ORDER BY productionProcess.orderID";

            $sql = mysqli_query($conn,$query);

            for ($i=0; $i < mysqli_num_rows($sql); $i++) {
              $row = mysqli_fetch_array($sql);

              echo "<tr>";
                echo "<td>";
                  echo $row['dueDate'];
                echo "</td>";

                echo "<td>";
                  echo $row['orderID'];
                echo "</td>";

                echo "<td>";
                  echo get_prodname($conn,$row['productID']);
                echo "</td>";

                echo "<td>";
                  echo get_machinename($conn,$row['machineID']);
                echo "</td>";

                echo "<td>";
                  echo get_processname($conn,$row['processTypeID']);
                echo "</td>";

                echo "<td>";
                  echo $row['processSequence'];
                echo "</td>";

                echo "<td>";
                  echo seconds_datetime($row['timeEstimate']);
                echo "</td>";

                echo "<td>";
                  echo $row['status'];
                echo "</td>";
              echo "</tr>";
            }
           ?>
        </tbody>
      </table>
  </div>
</div>

</div><br><br>
<script type="text/javascript">
  $('#timepicker1').timepicker({
      minuteStep: 1,
      showSeconds: true,
      showMeridian: true,
      defaultTime: 'current'
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('table.display').DataTable();

  } );

  $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})



</script>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
