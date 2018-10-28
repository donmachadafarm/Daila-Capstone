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

  // remove purchase order conditional submit button
  if(isset($_POST['remove'])){
    $id = $_POST['po_id'];

    $query = "UPDATE `PurchaseOrder` SET `status` = 'removed' WHERE `purchaseOrderID` = $id";

    if(mysqli_query($conn,$query)){
      echo "<script>alert('Removed Purchase Order from list!')</script>";
    }

  }

 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Purchase Orders
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Total Cost</th>
                    <th class="text-center">Date Requested</th>
                    <th class="text-center">Deadline</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if($result = mysqli_query($conn,'SELECT Supplier.name AS suppName,
                                                     PurchaseOrder.purchaseOrderID AS id,
                                                     PurchaseOrder.totalPrice AS price,
                                                     PurchaseOrder.orderDate AS date,
                                                     PurchaseOrder.deadline AS deadline,
                                                     PurchaseOrder.status AS status
                                                FROM PurchaseOrder
                                                INNER JOIN Supplier ON PurchaseOrder.supplierID =Supplier.supplierID
                                                WHERE PurchaseOrder.status <> "removed"')){


                    while($row = mysqli_fetch_array($result)){
                        $id = $row['id'];
                        $name = $row['suppName'];
                        $price = $row['price'];
                        $status = $row['status'];
                        $date = $row['date'];
                        $deadline = $row['deadline'];

                        echo '<tr>';
                          echo '<td class="text-center">';
                              echo $name;
                          echo '</td>';

                          echo '<td class="text-center">';
                            echo $price;
                          echo '</td>';

                          echo '<td class="text-center">';
                            echo $date;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $deadline;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $status;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo '<a href="viewIndivPO.php?id='.$id.'"><button type="button" class="btn btn-primary btn-sm">View Details</button></a> ';
                            echo '<a href="#remove'.$id.'" data-target="#remove'.$id.'" data-toggle="modal"><button type="button" class="btn btn-danger btn-sm">Remove</button></a>';
                          echo '</td>';

                        echo '</tr>';
                    ?>

                    <div id="remove<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="po_id" value="<?php echo $id; ?>">
                                        <div class="text-center">
                                          <p>
                                            <h6>Remove Purchase Order?</h6>
                                            <br>
                                            <h6>Note: This action will remove the purchase order from the list!</h6>
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
