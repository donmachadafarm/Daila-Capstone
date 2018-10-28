<!-- navbar -->
<nav class="nav navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <a class="navbar-brand bold" href="index.php">Daila Herbals</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="navbardrop" data-toggle="dropdown">
          Inventory Management
        </a>
        <div class="dropdown-menu">
          <h4 class="dropdown-header"><b>Inventory Management</b></h4>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-center"><b> <-Ingredients-> </b></h6>
          <!-- Add More Links if needed for every functionality -->
          <a class="dropdown-item text-center" href="addingredient.php">Add Ingredient</a>
          <a class="dropdown-item text-center" href="viewingredients.php">View Ingredients</a>
          <!-- use divider if needed -->
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-center"><b> <-Raw Materials-> </b></h6>
          <a class="dropdown-item text-center" href="addrawmaterial.php">Add Raw Materials</a>
          <a class="dropdown-item text-center" href="viewrawmaterials.php">View Raw Materials</a>
          <a class="dropdown-item text-center" href="makepurchaseorder.php">Make Purchase Order</a>
          <a class="dropdown-item text-center" href="viewPurchaseOrders.php">View Purchase Orders</a>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-center"><b><-Products-></b></h6>
          <a class="dropdown-item text-center" href="addproduct.php">Add Product</a>
          <a class="dropdown-item text-center" href="viewInventory.php">View Inventory</a>
          <a class="dropdown-item text-center" href="makejoborder.php">Make Job Order</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="navbardrop" data-toggle="dropdown">
          Production Scheduling
        </a>
        <div class="dropdown-menu">
          <!-- Add More Links if needed for every functionality -->
          <a class="dropdown-item" href="#">Link 1</a>
          <a class="dropdown-item" href="#">Link 2</a>
          <!-- use divider if needed -->
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Link 3</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="navbardrop" data-toggle="dropdown">
          Equipment Monitoring
        </a>
        <div class="dropdown-menu">
          <!-- Add More Links if needed for every functionality -->
          <h4 class="dropdown-header text-center"><b>Equipment Monitoring</b></h4>
          <div class="dropdown-divider"></div>
          <h6 class="dropdown-header text-center">-Machines-</h6>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-center" href="addEquipment.php">Add Machine</a>
          <a class="dropdown-item text-center" href="viewEquipment.php">Machine Monitoring</a>
        </div>
      </li>
    </ul>
    <span class="navbar-text">
      <div class="navbar-nav">
        <?php echo $_SESSION['username']; ?>
      </div>
    </span><a class="nav-item nav-link" href="logout.php">Logout</a>
  </div>
</nav>
