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

  $query = "SELECT JobOrder.orderID AS ID,
                                          Customer.company AS custname,
                                          JobOrder.orderDate AS datereq,
                                          JobOrder.dueDate AS duedate,
                                          JobOrder.totalPrice AS price,
                                          JobOrder.type AS type,
                                          JobOrder.status AS status
                                  FROM JobOrder
                                  INNER JOIN Customer ON JobOrder.customerID =Customer.customerID
                                  WHERE JobOrder.orderID = $id
                                  ORDER BY id DESC LIMIT 1";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $name = $row['custname'];
    $pricez = $row['price'];
    $deadline = $row['duedate'];
    $status = $row['status'];
    $type = $row['type'];
    $date = $row['datereq'];

 ?>

<div class="container">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                   Job Order requested to <?php echo $name; ?>
              </h1>
          </div>
      </div>
      <a onclick="goBack()" class="btn btn-primary btn-sm float-right">go back</a>
      <div class="row">
          <div class="col-lg-12">
            <table class="table table-borderless" id="dataTables-example">
              <tr>
                <td>Total Cost for JO: <b><?php echo number_format($pricez); ?></b></td>
                <td>JO Type: <?php echo $type; ?></td>
                <td>Purchase Order date posted: <?php echo $date; ?></td>
                <td>Deadline for supplier: <?php echo $deadline; ?></td>
              </tr>
            </table>
          </div>
      </div><br><br><br>
      <div class="row">
        <div class="col-lg-12">
          <h3>List of Items Ordered</h3>
          <table class="table table-bordered table-hover" id="dataTables-example">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit of Measurement</th>
                <th>Price per unit</th>
                <th>Sub Total</th>
              </tr>
            </thead>
            <tbody>

                <?php

                $query = "SELECT Product.name AS name,
                                 Product.productPrice AS ppu,
                                 Receipt.orderID AS joid,
                                 Receipt.quantity AS qty,
                                 Receipt.subTotal AS sub,
                                 Product.unitOfMeasurement AS uom
                          FROM Receipt
                          INNER JOIN Product ON Receipt.productID = Product.productID
                          INNER JOIN JobOrder ON Receipt.orderID = JobOrder.orderID
                          WHERE JobOrder.orderID = $id";

                $sql = mysqli_query($conn,$query);

                while ($row = mysqli_fetch_array($sql)) {
                    $joid = $row['joid'];
                    $name = $row['name'];
                    $qty = $row['qty'];
                    $sub = $row['sub'];
                    $ppu = $row['ppu'];
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

                      echo "<td>";
                        echo $ppu;
                      echo "</td>";

                      echo "<td class=text-right>";
                        echo number_format($sub);
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
