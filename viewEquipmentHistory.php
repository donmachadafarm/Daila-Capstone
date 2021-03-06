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
    $date = $row['acquiredDate'];
    $status = $row['status'];
    $totalhrs = $row['hoursWorked'];
    $totallife = $row['lifetimeWorked'];

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
                <td>Date Acquired: <?php echo $date; ?></td>
                <td>Current Status: <?php echo $status; ?></td>
                <td>Lifetime times used: <?php echo seconds_datetime($totallife); ?></td>
              </tr>
            </table>
          </div>
      </div><br>
      <hr class="style1">

      <div class="row">
        <div class="col-lg-12">
          <h3>List of Maintenance Transactions</h3>
          <table class="table table-bordered table-hover" id="dataTables-example">
            <thead>
              <tr>
                <th>Transaction Count</th>
                <th>Transaction Cost</th>
                <th>Transaction Date</th>
                <th>Problem Identified</th>
                <th>Solution</th>
              </tr>
            </thead>
            <tbody>

                <?php
                $query1 = "SELECT * FROM MaintenanceTransaction WHERE machineID = $id";

                  $sql = mysqli_query($conn,$query1);

                for ($i=0; $i < mysqli_num_rows($sql); $i++) {

                  $row = mysqli_fetch_array($sql);
                  $tranc = $i+1;
                  $cost = $row['maintenanceCost'];
                  $date = $row['maintenanceDate'];
                  $probi = $row['problemIdentified'];
                  $solu = $row['solution'];

                    echo "<tr>";
                      echo "<td>";
                        echo $tranc;
                      echo "</td>";

                      echo "<td>";
                        echo $cost;
                      echo "</td>";

                      echo "<td>";
                        echo $date;
                      echo "</td>";

                      echo "<td>";
                        echo $probi;
                      echo "</td>";

                      echo "<td>";
                        echo $solu;
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
