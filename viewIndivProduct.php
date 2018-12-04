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

  $query = "SELECT * FROM Product WHERE productID = $id";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $name = $row['name'];
    $quantity = $row['quantity'];
    $prodprice = $row['productPrice'];
    $prodtype = $row['productTypeID'];

  $query1 = "SELECT * FROM ProductType WHERE productTypeID = $prodtype";

    $sql = mysqli_query($conn,$query1);

    $row = mysqli_fetch_array($sql);

    $typename = $row['name'];
 ?>
<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   <?php echo $name; ?>
              </h1>
          </div>
      </div><a href="viewInventory.php" class="btn btn-primary btn-sm float-right">go back</a>
      <div class="row">
          <div class="col-lg-10">
            <table class="table table-borderless" id="dataTables-example">
              <tr>
                <td>Quantity: <?php echo $quantity; ?></td>
                <td>Price: <?php echo $prodprice; ?></td>
                <td>Product Type: <?php echo $typename; ?></td>
              </tr>
            </table>
          </div>
      </div>
      <hr class="style1">

      <div class="row">
        <div class="col-lg-12">
          <h3>List of Recipe</h3>
          <table class="table table-bordered table-hover" id="dataTables-example">
            <thead>
              <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Unit of Measurement</th>
              </tr>
            </thead>
            <tbody>

                <?php
                $query = "SELECT Recipe.quantity AS qty,
                                  Recipe.unitOfMeasurement AS uom,
                                  Ingredient.name AS name
                          FROM Recipe
                          INNER JOIN Ingredient ON Recipe.ingredientID=Ingredient.ingredientID
                          WHERE Recipe.productID = $id";

                $sql = mysqli_query($conn,$query);

                while ($row = mysqli_fetch_array($sql)) {
                    $name = $row['name'];
                    $qty = $row['qty'];
                    $uom = $row['uom'];

                    echo "<tr>";
                      echo "<td>";
                        echo $name;
                      echo "</td>";
                      echo "<td>";
                        echo $qty;
                      echo "</td>";
                      echo "<td>";
                        echo $uom;
                      echo "</td>";
                    echo "</tr>";
                }
                 ?>

            </tbody>
          </table>
        </div>
      </div>

<br><br>

      <div class="row">
        <div class="col-lg-12">
          <h3>List of Process to make per piece</h3>
          <table class="table table-bordered table-hover" id="dataTables-example">
            <thead>
              <tr>
                <th>Name</th>
                <th>Time to make per piece</th>
                <th>Sequence</th>
              </tr>
            </thead>
            <tbody>

                <?php
                $query = "SELECT productProcess.processTypeID AS ptid,
                                 productProcess.processSequence AS ptsq,
                                 productProcess.timeNeed AS time,
                                 processType.name AS name
                          FROM productProcess
                          JOIN processType ON processType.processTypeID=productProcess.processTypeID
                          WHERE productProcess.productID = $id
                          ORDER BY ptsq ASC";

                $sql = mysqli_query($conn,$query);

                while ($row = mysqli_fetch_array($sql)) {
                    $name = $row['name'];
                    $procseq = $row['ptsq'];
                    $proctid = $row['ptid'];
                    $time = $row['time'];

                    echo "<tr>";
                      echo "<td>";
                        echo $name;
                      echo "</td>";
                      echo "<td>";
                        echo seconds_datetime($time);
                      echo "</td>";
                      echo "<td>";
                        echo $procseq;
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
