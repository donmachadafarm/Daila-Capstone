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

<div class="container">
    <div class="row">
        <div class="col-lg-2">

        </div>
        <div class="col-lg-6">
            <h1 class="text-center"><br><br>
                Finished Goods Inventory Report
            </h1><br>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">

        </div>
        <div class="col-lg-8">
            <table class="table table-responsive table-hover">
                <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Price Per Unit</th>
                      <th class="text-right">SubTotal</th>
                  </tr>
                </thead>

                <tbody>
                  <?php
                    $query = "SELECT * FROM Product";

                      $sql = mysqli_query($conn,$query);
                      $sum = 0;
                    for ($i=0; $i < mysqli_num_rows($sql); $i++) {
                      $row = mysqli_fetch_array($sql);
                      $sub = $row['productPrice'] * $row['quantity'];
                      echo "<tr>";
                        echo "<td>";
                          echo $row['name'];
                        echo "</td>";

                        echo "<td>";
                          echo $row['quantity'];
                        echo "</td>";


                        echo "<td>";
                          echo $row['productPrice'];
                        echo "</td>";


                        echo "<td class='text-right'>";
                          echo number_format($row['productPrice'] * $row['quantity']);
                        echo "</td>";

                      echo "</tr>";
                      $sum+=$sub;
                    }
                  ?>
                </tbody>
              </table>
              <br>
              <!-- <hr class="style1"> -->
              <br>



        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-center">
        <h4>Total: <?php echo number_format($sum); ?></h4><br><br>
      </div>
    </div>
</div>
