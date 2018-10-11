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

  $query = "SELECT * FROM Machine WHERE machineID = $id";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $name = $row['name'];
    $type = $row['type'];
    $date = $row['acquiredDate'];
    $status = $row['status'];
    $totalhrs = $row['hoursWorked'];

 ?>
<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   <?php echo $name; ?>
              </h1>
          </div>
      </div><a href="viewEquipment.php" class="btn btn-primary btn-sm float-right">go back</a>
      <div class="row">
          <div class="col-lg-10">
            <table class="table table-borderless" id="dataTables-example">
              <tr>
                <td>Machine Type: <?php echo $type; ?></td>
                <td>Date Acquired: <?php echo $date; ?></td>
                <td>Current Status: <?php echo $status; ?></td>
                <td>Lifetime hours used: <?php echo $totalhrs; ?></td>
              </tr>
            </table>
          </div>
      </div><br><br><br>

      <div class="row">
        <div class="col-lg-12">
          <h3>List of Maintenance Transactions</h3>
          <table class="table table-bordered table-hover" id="dataTables-example">
            <thead>
              <tr>
                <th>Transaction ID</th>
                <th>Transaction Cost</th>
                <th>Transaction Date</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>

                <?php
                $query1 = "SELECT * FROM MaintenanceTransaction WHERE machineID = $id";

                  $sql = mysqli_query($conn,$query1);

                while ($row = mysqli_fetch_array($sql)) {
                  $transid = $row['transactionID'];
                  $cost = $row['maintenanceCost'];
                  $date = $row['maintenanceDate'];
                  $remarks = $row['remarks'];

                    echo "<tr>";
                      echo "<td>";
                        echo $transid;
                      echo "</td>";

                      echo "<td>";
                        echo $cost;
                      echo "</td>";

                      echo "<td>";
                        echo $date;
                      echo "</td>";

                      echo "<td>";
                        echo $remarks;
                      echo "</td>";
                    echo "</tr>";
                }
                 ?>

            </tbody>
          </table>
        </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
