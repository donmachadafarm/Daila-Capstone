<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }

  $counter = $_GET['counter'];
  $customer = $_GET['customer'];
  $date = $_GET['date'];

  $prodid = $_GET['prodid'];
  $prodqt = $_GET['prodqt'];

  if (isset($_POST['submit'])){
      $total = $_POST['total'];
      $or = $_POST['or'];

      $query = "INSERT INTO Sales (officialReceipt,saleDate,totalPrice,payment) VALUES ('$or','$date','$total','$total')";

        mysqli_query($conn,$query);

      $query = "SELECT * FROM Sales ORDER BY salesID DESC LIMIT 1";

        $sql = mysqli_query($conn,$query);

        $row = mysqli_fetch_array($sql);

        $salesid = $row['salesID'];

      foreach ($prodid as $key => $value) {
        $sql = mysqli_query($conn,"SELECT * FROM Product WHERE productID = $value");

          $row = mysqli_fetch_array($sql);

          $subtotal = $prodqt[$key] * $row['productPrice'];

        $query1 = "INSERT INTO ProductSales(productID,salesID,quantity,subTotal) VALUES('{$value}','{$salesid}','{$prodqt[$key]}','{$subtotal}')";

          $sql = mysqli_query($conn,$query1);

        $query2 = "UPDATE Product SET quantity = quantity - '{$prodqt[$key]}' WHERE productID = '{$value}'";

          mysqli_query($conn,$query2);
      }

        echo "<script>
          alert('Invoice posted!');
          window.location.replace('salesInvoice.php');
              </script>";

  }

?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h2 class="page-header"><br><br><br>
                  Daila - Invoice Confirmation
              </h2>
          </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="row">
            <div class="col">
              <label><strong>Customer: <?php echo get_customerName($conn,$customer); ?></strong></label>
              </br>
            </div>
            <div class="col">
              <label><strong>Date: <?php echo $date; ?> </strong></label></br>
              </br>
            </div>
          </div><br>
        </div>
      </div>
      <hr class="style1">
      <div class="row">
          <div class="col-lg-12">

            <form method="post" id="insert_form">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">

                    <div class="table-repsonsive">
                       <table class="table table-hover table-bordered " id="item_table">
                         <thead class="">
                          <tr>
                           <th>Product</th>
                           <th>Note</th>
                           <th>Quantity</th>
                           <th>Unit Price</th>
                           <th>Subtotal</th>
                          </tr>
                        </thead>
                        <?php foreach ($prodid as $key => $value): ?>
                          <tr>
                            <td><?php echo get_prodname($conn,$value); ?></td>
                            <td>
                              <?php if ($prodqt[$key]>get_prodqty($conn,$value)): ?>
                                <?php echo "Not enough products!"; ?>
                              <?php else: ?>
                                <?php echo "Available"; ?>
                              <?php endif; ?>
                            </td>
                            <td><?php echo $prodqt[$key]; ?></td>
                            <td class="text-right"><?php echo number_format(get_prodPrice($conn,$value)); ?></td>
                            <td class="text-right"><?php echo number_format($prodqt[$key]*get_prodPrice($conn,$value)); ?></td>
                          </tr>
                        <?php endforeach; ?>
                       </table>
                     </div>
                  </div>
                </div>
               </div>

               <div class="col-lg-12">
                 <div class="panel panel-default">
                   <div class="panel-body text-right">
                     <?php
                      $total = 0;
                      foreach ($prodid as $key => $value) {
                          $total += $prodqt[$key]*get_prodPrice($conn,$value);
                      }
                     ?>
                     <br /><br /><b>Total Price:</b> <?php echo number_format($total); ?>
                     <input type="hidden" name="total" value="<?php echo $total; ?>">
                   </div>
                 </div>
               </div>

               <hr class="style1">

               <div class="col-lg-12">
                 <div align="center">
                   <?php if ($counter>=1): ?>
                     <a href="salesInvoice.php"><button type="button" class="btn btn-warning">Go back</button></a>
                   <?php else: ?>
                     <a href="#confirm" data-target="#confirm" data-toggle="modal"><button type="button" class="btn btn-success">Finish</button></a>
                   <?php endif; ?>
                 </div>
               </div>

               <!-- // modal -->
               <div id="confirm" class="modal fade" role="dialog">
                   <div class="modal-dialog">
                           <div class="modal-content">

                               <div class="modal-header">
                                   <h4>Notice</h4>
                                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                               </div>

                               <div class="modal-body">
                                   <div class="text-center">
                                     <p>
                                       <h4>Finalize Invoice?</h4>
                                       <br>

                                       <div class="form-group row">
                                         <label class="col-sm-2 col-form-label">OR #: </label>
                                         <div class="col-sm-10">
                                           <input required type="number" name="or" value="" placeholder="" class="form-control"><br>
                                           <input type="hidden" name="totalprice" value="<?php echo $total; ?>">
                                         </div>
                                       </div>


                                     </p>
                                   </div>
                                   <div class="modal-footer">
                                       <input type="submit" id="submit" name="submit" class="btn btn-success" value="Confirm" />
                                       <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                   </div>
                               </div>
                       </div>
                   </div>
               </div>

             </form>

          </div>
      </div>
  </div>
</div>






<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
