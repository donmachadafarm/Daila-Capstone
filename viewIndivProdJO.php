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
  $id = $_GET['id'];

  // add inventory if set
  if(isset($_POST['add'])){
    $pid = $_POST['prodid'];
    $yield = $_POST['yield'];
    $good = $_POST['good'];
    $loss = $_POST['loss'];
    $datef = $_POST['datef'];

    // add qty to product inventory using ['good']
    $query = "UPDATE Product SET quantity = quantity + $good  WHERE productID = $pid";

    mysqli_query($conn,$query);
    // update production info total good, total yield, total loss, end date, status = finished
    $query1 = "UPDATE Production SET status = 'Finished', totalYield = $yield, totalGoods = $good, totalLost = $loss, endTime = $datef WHERE productID = $pid";

    mysqli_query($conn,$query1);
    // machines used = available and timesused++
    $query2 = "SELECT machineID FROM ProductionProcess WHERE productID = $pid";

      $sql = mysqli_query($conn,$query2);

      while ($row = mysqli_fetch_array($sql)) {
        $query3 = "UPDATE Machine SET status = 'Available', timesUsed = timesUsed + 1 WHERE machineID = $row[0]";

        mysqli_query($conn,$query3);
      }
      echo "<script>
        alert('Products are added to inventory!');
        window.location.replace('viewIndivProdJO.php?id=".$pid."');
            </script>";

  }

 ?>

<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   Job Order Production - JO #<?php echo $id; ?>
              </h1>
          </div>
      </div>
      <a href="viewProductionJobOrder.php" class="btn btn-primary btn-sm float-right">go back</a>

      <div class="row">
        <div class="col-lg-12">
          <hr class="style1">
          <h3>List of items in Production</h3>
          <?php
          $qry = "SELECT Receipt.productID AS prodid,Receipt.quantity AS prodqty,Production.status AS status
                    FROM Receipt
                    INNER JOIN Production ON Production.productID = Receipt.productID
                    WHERE Receipt.orderID = '$id'";

          $sqli = mysqli_query($conn,$qry);

          // iterate thru all products within jo
          while ($row = mysqli_fetch_array($sqli)) {
            $qry1 = "SELECT * FROM Product WHERE productID = '$row[0]'";

              $sql1 = mysqli_query($conn,$qry1);

              $rowe = mysqli_fetch_array($sql1);

            $q = "SELECT SUM(timeEstimate) FROM ProductionProcess WHERE productID = '$row[0]'";

              $sq = mysqli_query($conn,$q);

              $r = mysqli_fetch_array($sq);
            ?>

            <br>
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col">
                    <h5>Name: <strong><?php echo $rowe['name']; ?></strong></h5>
                  </div>
                  <div class="col">
                    <h5>Quantity: <?php echo round($row[1]+($row[1]*0.01)); ?></h5>
                  </div>
                  <div class="col">
                    <h5>Estimate total time: <?php echo round($r[0],2); ?></h5>
                  </div>
                </div>

              </div>
              <div class="card-body">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Process Name</th>
                      <th>Machine Name</th>
                      <th>Estimated Time to finish</th>
                    </tr>
                  </thead>
                  <tbody>
            <?
              $qry2 = "SELECT ProductionProcess.processTypeID,
                        	    ProductionProcess.machineID,
                              ProductionProcess.timeEstimate AS time,
                              ProcessType.name AS procname,
                              Machine.name AS machname
                        FROM ProductionProcess
                        INNER JOIN ProcessType ON ProductionProcess.processTypeID = ProcessType.processTypeID
                        INNER JOIN Machine ON ProductionProcess.machineID = Machine.machineID
                        WHERE ProductionProcess.productID = '$row[0]'";

                 $sql2 = mysqli_query($conn,$qry2);

                 while($rowd = mysqli_fetch_array($sql2)){;
            ?>
                    <tr>
                      <td><?php echo $rowd['procname']; ?></td>
                      <td><?php echo $rowd['machname']; ?></td>
                      <td><?php echo $rowd['time']; ?></td>
                    </tr>

            <?php
            // CLOSING NG WHILE LOOP FOR PRODUCTS ITERATION DONT REMOVE
              }
              ?>
            </tbody>
          </table>
              <!-- button float right -->
              <?php if ($row[2] == 'Started'){
                      echo '<a href="#add'.$row[0].'" data-target="#add'.$row[0].'" data-toggle="modal"><button type="button" class="btn btn-success float-right">Finish Production</button></a>';
                    } else {
                      echo '<button type="button" class="btn btn-sm btn-secondary btn-block" disabled>Finished!</button>';
                    } ?>
            </div>
          </div>

          <div id="add<?php echo $row[0]; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">
                  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                      <!-- Modal content-->
                      <div class="modal-content">

                          <div class="modal-header">
                              <h4>Notice</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>

                          <div class="modal-body">
                              <input type="hidden" name="prodid" value="<?php echo $row[0]; ?>">
                              <div>
                                <p>
                                  <h5>Finished production for <strong><?php echo $rowe['name']; ?>?</strong></h5>
                                  <br>
                                </p>
                              </div>
                              <label>Total Yield:</label></br>
                                <input type="number" name="yield" class="form-control" required>
                              </br>
                              <label>Total Good:</label></br>
                                <input type="number" name="good" class="form-control" required>
                              </br>
                              <label>Total Loss:</label></br>
                                <input type="number" name="loss" class="form-control" required>
                              </br>
                              <label>Date finished production:</label></br>
                                <input type="date" name="datef" id="txtDate" class="form-control" required>
                              </br>
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
           ?>




        </div>
      </div>
</div>
<br><br>

<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
