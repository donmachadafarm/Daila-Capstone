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
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                On Hand Inventory Report
            </h1><br>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-hover">
                <thead>
                  <tr>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Price Per Unit</th>
                      <th>SubTotal</th>
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


                        echo "<td>";
                          echo $row['productPrice'] * $row['quantity'];
                        echo "</td>";

                      echo "</tr>";
                      $sum+=$sub;
                    }
                  ?>
                </tbody>
              </table>
              <br><hr class="style1"><br>



        </div>
    </div>
    <div class="row">
      <div class="col-lg-12 text-right">
        <h4>Total: <?php echo number_format($sum); ?></h4><br><br>
      </div>
    </div>
</div>
