<!-- navbar -->
<nav class="nav navbar navbar-expand-lg navbar-light bg-secondary fixed-top">
  <a class="navbar-brand text-light bold" href="index.php">Daila Herbals</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">

    <ul class="navbar-nav mr-auto">


      <!-- inventory management -->


      <?php if($_SESSION['userType']==104 || $_SESSION['userType']==101 || $_SESSION['userType']==102 || $_SESSION['userType']==103): ?>
      <li class="nav-item dropdown">
        <a class="nav-link text-light" href="#" id="navbardrop" data-toggle="dropdown">
          Inventory Management
        </a>
        <div class="dropdown-menu">

          <?php if($_SESSION['userType']==101  || $_SESSION['userType']==102 || $_SESSION['userType']==103 || $_SESSION['userType']==104): ?>
            <h6 class="dropdown-header text-center"><b> Ingredients </b></h6>
            <!-- Add More Links if needed for every functionality -->
              <?php if($_SESSION['userType'] == 101 || $_SESSION['userType']==102 || $_SESSION['userType']==104): ?>
              <a class="dropdown-item text-center" href="addingredient.php">Add Ingredient</a>
              <?php endif; ?>
              <a class="dropdown-item text-center" href="viewingredients.php">View Ingredients</a>
            <!-- use divider if needed -->
            <div class="dropdown-divider"></div>
          <?php endif;?>

          <?php if($_SESSION['userType']==101  || $_SESSION['userType']==102 || $_SESSION['userType']==103 || $_SESSION['userType']==104): ?>
            <h6 class="dropdown-header text-center"><b> Raw Materials </b></h6>

              <?php if($_SESSION['userType'] == 101 || $_SESSION['userType']==102 || $_SESSION['userType']==104): ?>
              <a class="dropdown-item text-center" href="addrawmaterial.php">Add Raw Materials</a>
              <?php endif;?>

              <a class="dropdown-item text-center" href="viewrawmaterials.php">View Raw Materials</a>

              <?php if($_SESSION['userType'] == 101 || $_SESSION['userType']==102 || $_SESSION['userType']==104): ?>
              <a class="dropdown-item text-center" href="makepurchaseorder.php">Create Purchase Order</a>
              <a class="dropdown-item text-center" href="viewPurchaseOrders.php">View Purchase Orders</a>
              <?php endif;?>

              <div class="dropdown-divider"></div>
          <?php endif; ?>

          <?php if($_SESSION['userType']==101  || $_SESSION['userType']==102 || $_SESSION['userType']==104): ?>
            <h6 class="dropdown-header text-center"><b> Products </b></h6>
              <a class="dropdown-item text-center" href="addproduct.php">Add Product</a>
            <?php if($_SESSION['userType'] == 101 || $_SESSION['userType']==102 || $_SESSION['userType']==104): ?>
              <a class="dropdown-item text-center" href="viewInventory.php">View Products</a>
            <?php endif; ?>

          <?php endif; ?>

        </div>
      </li>
      <?php endif; ?>


      <!-- production management -->


      <?php if($_SESSION['userType']==104 || $_SESSION['userType']==101 || $_SESSION['userType']==103): ?>
      <li class="nav-item dropdown">
        <a class="nav-link text-light" href="#" id="navbardrop" data-toggle="dropdown">
          Production Management
        </a>
        <div class="dropdown-menu">

          <?php if($_SESSION['userType']==104 || $_SESSION['userType']==101): ?>
          <h6 class="dropdown-header text-center"><b> Job Orders </b></h6>
            <a class="dropdown-item text-center" href="samplejo.php">Create Job Order</a>
            <a class="dropdown-item text-center" href="viewJobOrders.php">Pending Job Orders</a>
          <?php endif; ?>
          <div class="dropdown-divider"></div>
          <?php if($_SESSION['userType']==103 || $_SESSION['userType']==104 || $_SESSION['userType']==101): ?>
          <h6 class="dropdown-header text-center"><b> Production </b></h6>
            <a class="dropdown-item text-center" href="viewProductionSchedule.php">Production Schedule</a>
            <?php if($_SESSION['userType']==104 || $_SESSION['userType']==101): ?>
            <a class="dropdown-item text-center" href="viewInternalShipping.php">View Internal Shipping</a>
          <?php endif; ?>
          <div class="dropdown-divider"></div>
          <?php endif; ?>
          <?php if($_SESSION['userType']==104 || $_SESSION['userType']==101): ?>
          <!-- Add More Links if needed for every functionality -->
          <h6 class="dropdown-header text-center"><b> Invoice </b></h6>
            <a class="dropdown-item text-center"  href="viewProductionJobOrder.php">Production Out</a>
            <a class="dropdown-item text-center" href="salesInvoice.php">Create Invoice</a>
          <?php endif; ?>

        </div>
      </li>
      <?php endif; ?>


      <!-- equipment monitoring -->


      <?php if($_SESSION['userType'] == 103 || $_SESSION['userType']==104): ?>
      <li class="nav-item dropdown">
        <a class="nav-link text-light" href="#" id="navbardrop" data-toggle="dropdown">
          Equipment Monitoring
        </a>
        <div class="dropdown-menu">
          <h6 class="dropdown-header text-center"><b> Machines </b></h6>
          <a class="dropdown-item text-center" href="addEquipment.php">Add Machine</a>
          <a class="dropdown-item text-center" href="viewEquipment.php">Machine Monitoring</a>
        </div>
      </li>
      <?php endif;?>


      <!-- reports -->


      <li class="nav-item dropdown">
        <a class="nav-link text-light" href="#" id="navbardrop" data-toggle="dropdown">Reports</a>
        <div class="dropdown-menu">

            <?php if ($_SESSION['userType']==101 || $_SESSION['userType']==100 || $_SESSION['userType']==104): ?>
              <h6 class="dropdown-header text-center"><b> Job Order Reports </b></h6>
              <!-- Add More Links if needed for every functionality -->
              <a class="dropdown-item text-center" href="jobOrderReportMTS.php">Made-To-Stock</a>
            <?php  if($_SESSION['userType']==100): ?>
              <a class="dropdown-item text-center" href="presReportMTO.php">Made-To-Order</a>
            <?php else: ?>
              <a class="dropdown-item text-center" href="jobOrderReportMTO.php">Made-To-Order</a>
            <?php endif; ?>
              <!-- use divider if needed -->
              <div class="dropdown-divider"></div>
            <?php endif; ?>

            <?php if ($_SESSION['userType']==100 || $_SESSION['userType']==104): ?>
              <h6 class="dropdown-header text-center"><b> Purchase Order Report </b></h6>
              <a class="dropdown-item text-center" href="purchaseOrderReport.php">Purchase Order Report</a>
              <div class="dropdown-divider"></div>
              <h6 class="dropdown-header text-center"><b> Sales Report </b></h6>
              <a class="dropdown-item text-center" href="salesChart.php">Product Sales Chart</a>
              <a class="dropdown-item text-center" href="salesTable.php">Product Sales Table</a>
              <a class="dropdown-item text-center" href="demandReport.php">Demand Report</a>
              <!-- <a class="dropdown-item text-center" href="salesChart.php">Daily Sales Report</a> -->
              <div class="dropdown-divider"></div>
            <?php endif; ?>

            <?php if ($_SESSION['userType']==103 || $_SESSION['userType']==100 || $_SESSION['userType']==104): ?>
              <h6 class="dropdown-header text-center"><b> Machine Report </b></h6>
              <a class="dropdown-item text-center" href="viewMachineReport.php">Historical Machine Report</a>
              <div class="dropdown-divider"></div>
            <?php endif; ?>

            <?php if ($_SESSION['userType']==102 || $_SESSION['userType']==100 || $_SESSION['userType']==104): ?>
              <h6 class="dropdown-header text-center"><b> Discrepancy Report </b></h6>
              <a class="dropdown-item text-center" href="viewAuditTrail.php">Inventory Discrepancy</a>
              <div class="dropdown-divider"></div>
            <?php endif; ?>

            <?php if ($_SESSION['userType']==100 || $_SESSION['userType']==104): ?>
              <h6 class="dropdown-header text-center"><b> Inventory Report </b></h6>
              <a class="dropdown-item text-center" href="viewOnHand.php">Finished Goods Inventory</a>
            <?php endif; ?>

        </div>
      </li>
    </ul>

    <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link text-light" href="#" id="navbardrop" data-toggle="dropdown">
            <?php echo $_SESSION['username']; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <?php if ($_SESSION['userType'] == 104 || $_SESSION['userType'] == 100 || $_SESSION['userType'] == 101): ?>
              <a class="dropdown-item" href="addCustomer.php">Add Customer</a>
              <?php if ($_SESSION['userType'] == 100): ?>
                <a class="dropdown-item" href="addSupplier.php">Add Supplier</a>
              <?php endif; ?>
              <div class="dropdown-divider"></div>
            <?php endif; ?>
            <a class="nav-item nav-link text-center" href="logout.php"><i class="fas fa-power-off"></i> Logout</a>
          </div>
        </li>
    </ul>

  </div>
</nav>
