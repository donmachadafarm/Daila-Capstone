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
  // for repair
  if (isset($_POST['repair'])) {
    $repid = $_POST['repair_id'];

    $query = "UPDATE Machine SET status = 'Under Maintenance' WHERE machineID = $repid";

    $sql = mysqli_query($conn,$query);

  }

  // for finished repair
  if (isset($_POST['finish'])) {
    $finishid = $_POST['finish_repair_id'];
    $cost = $_POST['cost'];
    $date = $_POST['datefinish'];
    $problem = $_POST['problem'];
    $solution = $_POST['solution'];

    $query = "INSERT INTO MaintenanceTransaction (machineID,maintenanceCost,maintenanceDate,problemIdentified,solution)
                VALUES ('{$finishid}','{$cost}','{$date}','{$problem}','{$solution}')";

    $sql = mysqli_query($conn,$query);

    $query = "UPDATE Machine SET status = 'Available', hoursWorked = 0 WHERE machineID = $finishid";

    $sql = mysqli_query($conn,$query);
  }
 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Machine Monitor
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Process Connected</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Hours Worked</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $result = mysqli_query($conn,'SELECT machine.machineID,
                                                     machine.name,
                                                     machine.status,
                                                     machine.hoursWorked,
                                                     machine.lifetimeWorked,
                                                     machine.acquiredDate,
                                                     processtype.name AS procname
                                                FROM machine
                                                INNER JOIN processtype ON machine.processTypeID = processtype.processTypeID');


                while($row = mysqli_fetch_array($result)){
                    $id = $row['machineID'];
                    $name = $row['name'];
                    $status = $row['status'];
                    $timesused = $row['hoursWorked'];
                    $acquiredDate = $row['acquiredDate'];
                    $proctype = $row['procname'];

                    echo '<tr>';
                      echo '<td>';
                        echo '<a href="viewEquipmentHistory.php?id='.$id.'">';
                          echo $name;
                        echo '</a>';
                      echo '</td>';

                      echo '<td class="text-center">';
                        echo $proctype;
                      echo'</td>';

                      echo '<td class="text-center">';
                        echo $status;
                      echo'</td>';

                      echo '<td class="text-center">';
                        echo $timesused;
                      echo'</td>';

                      echo '<td class="text-center">';
                      //modal trigger button
                      if ($status == "Available") {
                        echo '<a href="#repair'.$id.'" data-target="#repair'.$id.'" data-toggle="modal"><button type="button" class="btn btn-success btn-sm"><i class="fas fa-wrench"></i> Repair</button></a>';
                      }else if($status == "Used"){
                        echo '<button type="button" class="btn btn-sm btn-secondary" disabled>In Use</button>';
                      }else {
                        echo '<a href="#finish'.$id.'" data-target="#finish'.$id.'" data-toggle="modal"><button type="button" class="btn btn-secondary btn-sm"><i class="fas fa-wrench"></i> Fix</button></a>';
                      }
                      echo '</td>';

                    echo '</tr>';
                    ?>
                    <div id="finish<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <!-- Modal content-->
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="finish_repair_id" value="<?php echo $id; ?>">
                                        <div>
                                          <p>
                                            <h5>Are you done repairing <strong><?php echo $name; ?>?</strong></h5>
                                            <br>
                                          </p>
                                        </div>
                                        <label>Cost for maintenance:</label></br>
                                          <input type="number" name="cost" class="form-control" required>
                                        </br>
                                        <label>Date finished maintenance:</label></br>
                                          <input type="date" name="datefinish" id="txtDateMax" class="form-control" required>
                                        </br>
                                        <label>Problem Identified ("n/a" if none):</label></br>
                                          <textarea name="problem" rows="2" class="form-control"></textarea>
                                        </br>
                                        <label>Solution ("n/a" if none):</label></br>
                                          <textarea name="solution" rows="2" class="form-control"></textarea>
                                        </br>
                                        <div class="modal-footer">
                                            <button type="submit" name="finish" class="btn btn-primary">YES</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>

                    <div id="repair<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <!-- Modal content-->
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="repair_id" value="<?php echo $id; ?>">
                                        <div>
                                          <p>
                                            <h6 class="text-center">Are you sure you want repair <strong><?php echo $name; ?>?</strong></h6>
                                            <br>
                                            <h6 class="text-center">This will put the machine under maintenance status!</h6>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="repair" class="btn btn-primary">YES</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <?php

                }
                echo '<br><br>';
                ?>

                </tbody></table>



        </div>
    </div>
</div>
<br><br>

<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
