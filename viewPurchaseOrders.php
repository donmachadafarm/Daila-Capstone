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

  // update status PO and add all the requested raw mats into inventory (INVENTORY AND RAW MATERIALS UPDATE)
  if (isset($_POST['update'])) {
    $poid = $_POST['po_id'];

    $query = "UPDATE Machine SET status = 'Under Maintenance' WHERE machineID = $repid";

    $sql = mysqli_query($conn,$query);

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
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $result = mysqli_query($conn,'SELECT Supplier.name AS suppName,
                                                     PurchaseOrder.purchaseOrderID AS id,
                                                     PurchaseOrder.totalPrice AS price,
                                                     PurchaseOrder.orderDate AS date,
                                                     PurchaseOrder.status AS status
                                                FROM PurchaseOrder
                                                INNER JOIN Supplier ON PurchaseOrder.supplierID =Supplier.supplierID');


                while($row = mysqli_fetch_array($result)){
                    $id = $row['id'];
                    $name = $row['suppName'];
                    $price = $row['price'];
                    $status = $row['status'];
                    $date = $row['date'];

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
                        echo $status;
                      echo'</td>';

                      echo '<td class="text-center">';
                        echo '<a href="viewIndivPO.php?id='.$id.'"><button type="button" class="btn btn-primary btn-sm">View Details</button></a> ';
                        echo '<a href="#update'.$id.'" data-target="#update'.$id.'" data-toggle="modal"><button type="button" class="btn btn-success btn-sm">Update Status</button></a>';
                      echo '</td>';

                    echo '</tr>';
                    ?>

                    <div id="update<?php echo $id; ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <form method="post">
                                <!-- Modal content-->
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h4>Notice</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="hidden" name="po_id" value="<?php echo $id; ?>">
                                        <div class="text-center">
                                          <p>
                                            <h6>Update Purchase Order Status?</h6>
                                            <br>
                                            <h6>Note: This action will add the requested Raw Materials into the inventory!</h6>
                                          </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="repair" class="btn btn-primary">Continue</button>
                                            <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
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


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
