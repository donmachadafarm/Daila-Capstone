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
                  Customer List
              </h1>
              <!-- <hr class="style1"> -->
              <h6>
                  <!-- Click a Customer's name to view transaction history -->
              </h6>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
                    <table class="table table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Name</th>
                                <!-- <th>Position</th> -->
                                <!-- <th>Email</th> -->
                                <!-- <th>Address</th> -->
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                            $result = mysqli_query($conn,'SELECT * FROM Customer WHERE customer.customerID!=1');


                            while($row = mysqli_fetch_array($result)){

                              $cusid = $row['customerID'];
                              $name = $row['company'];
                              $fname = $row['firstName'];
                              $lname = $row['lastName'];
                              $email = $row['email'];
                              $address = $row['address'];
                              $contactnum = $row['contactNum'];
                              $position = $row['position'];

                                  echo '<tr>';
                                    echo '<td>';
                                      echo '<a href="viewIndivCustomer.php?id='.$cusid.'">'.$name.'</a>';
                                    echo '</td>';
                                    echo '<td>';
                                      echo $fname.' '.$lname;
                                    echo '</td>';
                                    // echo '<td>';
                                    //   echo $position;
                                    // echo '</td>';
                                    // echo '<td>';
                                    //   echo $email;
                                    // echo'</td>';
                                    // echo '<td>';
                                    //   echo $address;
                                    // echo'</td>';
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
