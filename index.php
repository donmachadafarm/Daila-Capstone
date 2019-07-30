<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }


  if (isset($_POST['edit'])) {
    $id = $_POST['prodid'];
    $qty = $_POST['qty'];

    update_inventory($conn,$id,$qty);
  }
?>

<style media="screen">
a {
  color: #000;
  text-decoration: none;
}
.card {
  overflow:hidden;
}

.card-body .rotate {
  z-index: 8;
  float: right;
  height: 10%;
}

.card-body .rotate i {
  color: rgba(20, 20, 20, 0.15);
  position: absolute;
  left: 0;
  left: auto;
  right: -10px;
  bottom: 0;
  display: block;
  -webkit-transform: rotate(-44deg);
  -moz-transform: rotate(-44deg);
  -o-transform: rotate(-44deg);
  -ms-transform: rotate(-44deg);
  transform: rotate(-44deg);
}
</style>

<!-- put all the contents here  -->


  <div id="page-wrapper">
    <div class="col main pt-5 mt-1">
      <div class="row mb-3">

      <?php if ($_SESSION['userType'] == 100): ?>

        <div class="col-sm-3 py-4">
            <div class="card bg-secondary text-white h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-list fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase">Orders this month</h6>
                    <h1 class="display-6"><?php echo get_allorders($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-file-invoice-dollar fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase">Revenue this month</h6>
                    <h1 class="display-6"><?php echo "₱".get_revenue($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-share-square fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase">Products sold</h6>
                    <h1 class="display-6"><?php echo get_prodsold($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-clock fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase">Delayed Orders</h6>
                    <h1 class="display-6"><?php echo get_delayedJOrdersCount($conn); ?></h1>
                </div>
            </div>
        </div>

        <div class="spacer5"></div>

            <!-- 1st chart sales -->
            <!-- <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Company Revenue</b>
                   </div>
                   <div class="card-body">
                     <div id="first" style="width: 650px; height: 360px;"></div>
                   </div>
               </div>
            </div> -->

            <!-- 2nd chart items pinaka mabenta -->
            <!-- <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Best Selling Products</b>
                   </div>
                   <div class="card-body">
                     <div id="second" style="width: 650px; height: 360px;"></div>
                   </div>
               </div>
            </div> -->

      <?php endif; ?>

      <?php if ($_SESSION['userType'] == 101): ?>

        <div class="col-sm-3 py-4">
            <div class="card bg-secondary text-white h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-archive fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewIngredientsRestock.php" style="color: #FFFFFF;text-decoration: none;">Ingredients to restock</a></h6>
                    <h1 class="display-6"><?php echo get_ingrrestockcount($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-laptop-medical fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewInventoryRestock.php" style="color: #FFFFFF;text-decoration: none;">Products to restock</a></h6>
                    <h1 class="display-6"><?php echo get_prodrestockcount($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-share-square fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewInternalShipping.php" style="color: #FFFFFF;text-decoration: none;">Pending Internal Shipping</a></h6>
                    <h1 class="display-6"><?php echo get_pendingShipping($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-clock fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewjoborders.php" style="color: #FFFFFF;text-decoration: none;">Delayed Orders</a></h6>
                    <h1 class="display-6"><?php echo get_delayedJOrdersCount($conn); ?></h1>
                </div>
            </div>
        </div>

            <!--  -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Pending Job Orders List</b>
                   </div>
                   <div class="card-body">
                     <table class="table table-borderless table-hover display" id="">
                       <thead>
                         <th class="text-center">DueDate</th>
                         <th class="text-center">Company</th>
                         <th class="text-center">DateOrder</th>
                         <th class="text-center">Type</th>
                         <th class="text-center">Status</th>
                       </thead>
                       <tbody>
                         <?php echo view_jo($conn); ?>
                       </tbody>
                     </table>
                   </div>
               </div>
            </div>

            <!--  -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Pending Purchase Orders List</b>
                   </div>
                   <div class="card-body">
                     <table class="table table-borderless table-hover display" id="">
                       <thead>
                         <th class="text-center">DueDate</th>
                         <th class="text-center">Supplier</th>
                         <th class="text-center">Cost</th>
                         <th class="text-center">Order Date</th>
                         <th class="text-center">Status</th>
                       </thead>
                       <tbody>
                         <?php echo view_po($conn); ?>
                       </tbody>
                     </table>
                   </div>
               </div>
            </div>

      <?php endif; ?>

      <?php if ($_SESSION['userType'] == 102): ?>

        <div class="col-sm-4 py-4">
            <div class="card bg-secondary text-white h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-archive fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewingredients.php" style="color: #FFFFFF;text-decoration: none;">Ingredients to restock</a></h6>
                    <h1 class="display-6"><?php echo get_ingrrestockcount($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-laptop-medical fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewinventory.php" style="color: #FFFFFF;text-decoration: none;">Products to restock</a></h6>
                    <h1 class="display-6"><?php echo get_prodrestockcount($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-list-alt fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewPurchaseOrders.php" style="color: #FFFFFF;text-decoration: none;">Pending Purchase Orders</a></h6>
                    <h1 class="display-6"><?php echo get_pendingPO($conn); ?></h1>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-clock fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewPurchaseOrders.php" style="color: #FFFFFF;text-decoration: none;">Delayed Orders</a></h6>
                    <h1 class="display-6"><?php //echo 'pwet'; ?></h1>
                </div>
            </div>
        </div> -->

            <!--  -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Pending Purchase Orders List</b>
                   </div>
                   <div class="card-body">
                     <table class="table table-borderless table-hover display" id="">
                       <thead>
                         <th class="text-center">DueDate</th>
                         <th class="text-center">Company</th>
                         <th class="text-center">DateOrder</th>
                         <th class="text-center">Type</th>
                         <th class="text-center">Status</th>
                       </thead>
                       <tbody>
                         <?php echo view_po($conn); ?>
                       </tbody>
                     </table>
                   </div>
               </div>
            </div>

            <!--  -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Product Inventory</b>
                   </div>
                   <div class="card-body">
                     <table class="table table-borderless table-hover " id="example">
                       <thead>
                         <th class="text-center">Product</th>
                         <th class="text-center">Quantity</th>
                         <th class="text-center">Type</th>
                         <th class="text-center">Price</th>
                         <th class="text-center">Action</th>
                       </thead>
                       <tbody>
                         <?php echo view_prodinventory($conn); ?>
                       </tbody>
                     </table>
                   </div>
               </div>
            </div>

      <?php endif; ?>

      <?php if ($_SESSION['userType'] == 103): ?>

        <div class="col-sm-6 py-4">
            <div class="card bg-secondary text-white h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-cogs fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewProductionSchedule.php" style="color: #FFFFFF;text-decoration: none;">Machines in use</a></h6>
                    <h1 class="display-6"><?php echo get_numberofmachinesused($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 py-4">
            <div class="card text-white bg-secondary h-30">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-wrench fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase"><a href="viewEquipment.php" style="color: #FFFFFF;text-decoration: none;">Machines need repair</a></h6>
                    <h1 class="display-6"><?php echo get_numberofmachinesrepair($conn); ?></h1>
                </div>
            </div>
        </div>


            <!--  -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Unavailable machines</b>
                   </div>
                   <div class="card-body">
                     <table class="table table-borderless table-hover display" id="">
                       <thead>
                         <th class="text-center">Name</th>
                         <th class="text-center">Process Connected</th>
                         <th class="text-center">Status</th>
                         <th class="text-center">Hours Worked</th>
                       </thead>
                       <tbody>
                         <?php echo view_machinesrepair($conn); ?>
                       </tbody>
                     </table>
                   </div>
               </div>
            </div>

            <!--  -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Machines Need Repair</b>
                   </div>
                   <div class="card-body">
                     <table class="table table-borderless table-hover " id="example">
                       <thead>
                         <th class="text-center">Name</th>
                         <th class="text-center">Process</th>
                         <th class="text-center">Hour Worked</th>
                       </thead>
                       <tbody>
                         <?php echo view_machinefor($conn); ?>
                       </tbody>
                     </table>
                   </div>
               </div>
            </div>

      <?php endif; ?>

     </div>
    </div>
  </div>
  <?php

    // ADMIN AND PRESIDENT QUERIES
    $first = date("Y-m-d",strtotime("-5 Months"));
    $last = date("Y-m-d");

    $query = "SELECT DISTINCT SUM(ProductSales.quantity) AS qty,
                              product.name as name
                FROM ProductSales
                JOIN Sales ON Sales.salesID = ProductSales.salesID
                JOIN Product ON Product.productID = ProductSales.productID
                WHERE Sales.saleDate BETWEEN '$first' AND '$last' LIMIT 5";

      $sql = mysqli_query($conn,$query);

    $first = date('Y-m-01');
    $last  = date('Y-m-t');

    $query1 = "SELECT SUM(payment) AS revenue, saledate AS month FROM Sales WHERE saleDate BETWEEN '$first' AND '$last' limit 10";

      $sql1 = mysqli_query($conn,$query1);
    // END OF ADMIN AND PRESIDENT QUERIES

   ?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        $(document).ready(function() {
          $('table.display').DataTable();
            } );

        $(document).ready( function () {
          var table = $('#example').DataTable( {
            pageLength : 6,
            lengthMenu: [[6, 10, 20], [6, 10, 20]]
          } )
        } );

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

          var data = google.visualization.arrayToDataTable([
              ['month', 'revenue'],
              <?php
              while($rowe = mysqli_fetch_array($sql1))
              {
                  $month = date('F',strtotime($rowe["month"]));
                  echo "['".$month."', ".$rowe["revenue"]."],";
              }
              ?>
          ]);

          var data2 = google.visualization.arrayToDataTable([
              ['name', 'total sales'],
              <?php
              while ($row = mysqli_fetch_array($sql)){
                  echo "['".$row["name"]."', ".$row["qty"]."],";
              }
              ?>
          ])



        var barchart_options = {title:'Company Revenue per month',
                       width:600,
                       height:360,
                       legend: 'none',
                       hAxis: {
                          title: 'Months'},
                       vAxis: {
                          title: 'Revenue'
                              }};

        var piechart_options = {title:'Best Selling Products',
                       width:650,
                       height:360,
                       colors: ['#e2431e', '#d3362d', '#e7711b',
                     '#e49307', '#e49307', '#b9c246']};

        var barchart = new google.visualization.LineChart(document.getElementById('first'));
        barchart.draw(data, data);

        var piechart = new google.visualization.PieChart(document.getElementById('second'));
        piechart.draw(data, data2);
      }
      </script>


<?php include "includes/sections/footer.php"; ?>
