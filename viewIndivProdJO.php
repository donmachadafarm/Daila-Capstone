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
                                          Customer.name AS custname,
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
                   Job Order Production - JO #<?php echo $id; ?>
              </h1>
          </div>
      </div>
      <a href="viewProductionJobOrder.php" class="btn btn-primary btn-sm float-right">go back</a>
      <div class="row">
          <div class="col-lg-12">
            <table class="table table-borderless" id="dataTables-example">
              <tr>
                <td>Total Cost for JO: <b><?php echo $pricez; ?></b></td>
                <td>JO Type: <?php echo $type; ?></td>
                <td>Purchase Order date posted: <?php echo $date; ?></td>
                <td>Deadline for supplier: <?php echo $deadline; ?></td>
              </tr>
            </table>
          </div>
      </div><br><br><br>
      <div class="row">
        <div class="col-lg-12">
          <h3>List of items in Production</h3>
            <div class="card">
              <div class="card-header">
                Featured
              </div>
              <div class="card-body">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
              </div>
            </div>
        </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
