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
              <h1 class="page-header"><br><br>
                  View Customers
              </h1>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-10">
                    <table class="table table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Payment Method</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $result = mysqli_query($conn,'SELECT * FROM Customer');


                            while($row = mysqli_fetch_array($result)){

                              $name = $row['name'];
                              $method = $row['paymentMethodCode'];
                              $email = $row['email'];
                              $address = $row['address'];
                              $contactnum = $row['contactNum'];

                                  echo '<tr>';
                                    echo '<td>';
                                      echo $name;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $method;
                                    echo '</td>';
                                    echo '<td>';
                                      echo $email;
                                    echo'</td>';
                                    echo '<td>';
                                      echo $address;
                                    echo'</td>';
                                    echo '<td>';
                                      echo $contactnum;
                                    echo'</td>';
                                  echo '</tr>';


                            }


                            echo '<br /><br />';

                            ?>
                            </tbody></table>

          </div>
      </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
