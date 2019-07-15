<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }

  function fill_unit_select_box($conn){
    $output = '';
    $query = "SELECT Product.name AS pname,Product.productID AS pid FROM Product";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["pid"].'">'.$row["pname"].'</option>';
    }

    return $output;
  }

  function fill_unit_select_box1($conn){
    $output = '<option disabled selected> Select another </option>';
    $query = "SELECT Product.name AS pname,Product.productID AS pid FROM Product";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["pid"].'">'.$row["pname"].'</option>';
    }

    return $output;
  }

  function fill_customer_select_box($conn){
    $output = '';
    $query = "SELECT * FROM Customer WHERE customerID != 1";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["customerID"].'">'.$row["company"].'</option>';
    }

    return $output;
  }

  $date = date("Y-m-d");

  if (isset($_POST['submit'])){
      $products = $_POST['product'];
      $qty = $_POST['quantity'];
      $customer  = $_POST['customer'];
      $date = $_POST['date'];
      $total = 0;

      $count = count(array_unique($_POST['product']));

      $result = array();

      foreach($products as $index => $value) {
          if(!isset($result[$value])) {
              $result[$value] = 0;
          }
          $result[$value] += $qty[$index];
      }

      // array keys -> prod id
      $arkey = array_keys($result);
      $counter =0;
      $prodn = array();

      // iterate thru all products
      if ($count > 0) {
        for ($i=0; $i < $count; $i++) {
            if ($result[$arkey[$i]]>get_prodqty($conn,$arkey[$i])) {
              $counter++;
              array_push($prodn,$arkey[$i]);
            }
        }
      }

      $prodqty = array();
      foreach ($result as $key => $value) {
        array_push($prodqty,$value);
      }

      $prid = http_build_query(array('prodid' => $arkey));
      $prqt = http_build_query(array('prodqt' => $prodqty));
     echo "<script>
                window.location.replace('salesInvoiceFinish.php?".$prid."&".$prqt."&customer=".$customer."&date=".$date."&counter=".$counter."');
           </script>";

  }
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Daila - Invoice
              </h1>
          </div>
      </div>
      <hr class="style1">
      <div class="row">
          <div class="col-lg-12">

            <form method="POST" id="insert_form">

              <div class="col-lg-6">
                <div class="row">
                  <div class="col">
                    <label><strong>Customer:</strong></label>
                      <select name="customer" class="form-control item_unit" required><option value="" disabled>Select Customer</option><?php echo fill_customer_select_box($conn); ?></select>
                    </br>
                  </div>
                  <div class="col">
                    <label><strong>Date:</strong></label></br>
                      <input type="date" name="date" class="form-control" value="<?php echo $date; ?>" id="txtDateMax" required>
                    </br>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">

                    <div class="table-repsonsive">
                       <table class="table table-borderless" id="item_table">
                        <tr>
                         <th>Product</th>
                         <th>Quantity</th>
                        </tr>
                        <tr>
                          <td><select name="product[]" class="form-control item_unit" required><option value="" disabled>Select Product</option><?php echo fill_unit_select_box($conn); ?></select></td>
                          <td><input type="number" name="quantity[]" min="1" class="form-control item_name" required /></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table>
                     </div>
                  </div>
                </div>
               </div>

               <hr class="style1">

               <div class="col-lg-12">
                 <div align="center">
                    <input type="submit" id="submit" name="submit" class="btn btn-success" value="Confirm" />
                 </div>
               </div>


             </form>

          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

   $(document).on('click', '.add', function(){
      var html = '';
      html += '<tr>';
      html += '<td><select name="product[]" class="form-control item_unit" required><?php echo fill_unit_select_box1($conn); ?></select></td>';
      html += '<td><input type="number" name="quantity[]" min="1" class="form-control item_name" required /></td>';
      html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">x</button></td></tr>';
      $('#item_table').append(html);
     });

     $(document).on('click', '.remove', function(){
      $(this).closest('tr').remove();
     });

  });
</script>





<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
