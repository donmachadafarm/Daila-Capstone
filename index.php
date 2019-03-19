<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }

?>

<style media="screen">
.card {
  overflow:hidden;
}

.card-body .rotate {
  z-index: 8;
  float: right;
  height: 100%;
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

      <?php if ($_SESSION['userType'] == 100 || $_SESSION['userType'] == 104): ?>

        <div class="col-sm-3 py-4">
            <div class="card bg-secondary text-white h-100">
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
            <div class="card text-white bg-secondary h-100">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-file-invoice-dollar fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase">Revenue this month</h6>
                    <h1 class="display-6"><?php echo get_revenue($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-3 py-4">
            <div class="card text-white bg-secondary h-100">
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
            <div class="card text-white bg-secondary h-100">
                <div class="card-body bg-secondary">
                    <div class="rotate">
                        <i class="fa fa-clock fa-7x"></i>
                    </div>
                    <h6 class="text-uppercase">Delayed Orders</h6>
                    <h1 class="display-6"><?php echo get_delayedOrdersCount($conn); ?></h1>
                </div>
            </div>
        </div>

        <hr class="style1">

            <!-- 1st chart sales -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Company Revenue</b>
                   </div>
                   <div class="card-body">
                     <div id="first" style="width: 650px; height: 360px;"></div>
                   </div>
               </div>
            </div>

            <!-- 2nd chart items pinaka mabenta -->
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header bg-secondary text-white">
                       <b>Best Selling Products</b>
                   </div>
                   <div class="card-body">
                     <div id="second" style="width: 650px; height: 360px;"></div>
                   </div>
               </div>
            </div>





      <?php endif; ?>

     </div>
    </div>
  </div>
  <?php
    $query = "SELECT * FROM LIMIT 5";

   ?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 3],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Pepperoni', 2]
        ]);

        var barchart_options = {title:'Company Revenue per month',
                       width:600,
                       height:360,
                       legend: 'none'};
        var barchart = new google.visualization.LineChart(document.getElementById('first'));
        barchart.draw(data, barchart_options);

        var piechart_options = {title:'Best Selling Products',
                       width:650,
                       height:360};
        var piechart = new google.visualization.PieChart(document.getElementById('second'));
        piechart.draw(data, piechart_options);
      }
      </script>


<?php include "includes/sections/footer.php"; ?>
