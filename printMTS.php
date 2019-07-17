<?php include "includes/sections/header.php"; ?>
<?php //include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}
?>

<!-- put all the contents here  -->

<?php
    $dataArr = $_SESSION['reportMTS'];

    $sum = 0;

?>
<br>
<div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="card text-center">
          <div class="card-header">
            <h3><b>Transactions between <?php echo $_GET['start']; ?> and <?php echo $_GET['end']; ?></b></h3>
          </div>
          <div class="card-body">
            <table class="table table-sm table-hover">
              <thead class="thead-light">
                <tr class="table-active">
                  <td>Job Order ID</td>
                  <td>Product Name</td>
                  <td>Quantity</td>
                  <td class="text-right">Price</td>
                  <td class="text-right">Date</td>
                </tr>
              </thead>
              <tbody>

                <?php
                  foreach ($dataArr as $key => $value) {
                    $data = get_JODetails($conn,$value);

                    echo "<tr>";
                      echo "<td>";
                        echo $value;
                      echo "</td>";

                      echo "<td>";
                        echo $data['productName'];
                      echo "</td>";

                      echo "<td>";
                        echo $data['quantity'];
                      echo "</td>";

                      echo "<td class='text-right'>";
                        echo number_format($data['total']);
                      echo "</td>";

                      echo "<td class='text-right'>";
                        echo $data['date'];
                      echo "</td>";
                    echo "</tr>";

                    $sum += $data['total'];
                  }
                 ?>
               </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <div class="text-center">
              <h3>Total Income: <?php echo number_format($sum); ?></h3><br>
              <h4>***End of Report***</h4>
            </div>
            <div class="text-left text-muted">
              <p class="small">Generated <?php echo date('F-d-Y g:i A'); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>





<?php
  unset($_SESSION['reportMTS']);
 ?>
