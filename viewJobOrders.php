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

  // remove job order conditional submit button
  if(isset($_POST['remove'])){
    $id = $_POST['jo_id'];

    $query = "UPDATE `JobOrder` SET `status` = 'removed' WHERE `orderID` = $id";

    if(mysqli_query($conn,$query)){
      echo "<script>alert('Removed Job Order from list!')</script>";
    }

  }

  // approve job order conditional submit button puts the job order in production
  if(isset($_POST['approve'])){
    $id = $_POST['jo_id'];

    $query = "UPDATE `JobOrder` SET `status` = 'Production' WHERE `orderID` = $id";

    if(mysqli_query($conn,$query)){
      echo "<script>alert('Job order products are now in production!')</script>";
    }

    // subtract ingredients per product based on Quantity
    reduce_inventory_rawmats_production($conn,$id);

    // start production



  }

 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Job Orders
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Date Requested</th>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Type</th>
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
                                                        JobOrder.type AS type,
                                                        JobOrder.status AS status
                                                FROM JobOrder
                                                INNER JOIN Customer ON JobOrder.customerID = Customer.customerID
                                                WHERE JobOrder.status = "Pending for approval"')){


                    while($row = mysqli_fetch_array($result)){
                        $id = $row['ID'];
                        $name = $row['custname'];
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
                            echo $type;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $status;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo '<a href="viewIndivJO.php?id='.$id.'"><button type="button" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button></a>  ';
                            echo '<a href="#remove'.$id.'" data-target="#remove'.$id.'" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></a>';
                            if (check_for_inventory_match($conn,$id)>0) {
                              echo '<a href="#check'.$id.'" data-target="#check'.$id.'" data-toggle="modal">
                                <button type="button" class="btn btn-secondary btn-sm">
                                  <i class="fas fa-exclamation-circle"></i>
                                </button></a>  ';
                            } else {
                              echo '<a href="#approve'.$id.'" data-target="#approve'.$id.'" data-toggle="modal">
                                <button type="button" class="btn btn-success btn-sm">
                                  <i class="fas fa-check-circle"></i>
                                </button></a>  ';
                            }
                          echo '</td>';

                        echo '</tr>';
                    ?>
                    <div id="approve<?php echo $id; ?>" class="modal fade" role="dialog">
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
                                            <h6>Approve Order?</h6>
                                            <br>
                                            <h6>Note: This action will put the Job Order in production!</h6><br>

                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="approve" class="btn btn-primary">Continue</button>
                                            <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>

                    <div id="check<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                      <h5 class="text-center">Lacking ingredients on the following products:</h5>
                                        <?php print_p(get_need_inventory($conn,$id)); ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                    </div>

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
                </tbody>

              </table>



        </div>
    </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
