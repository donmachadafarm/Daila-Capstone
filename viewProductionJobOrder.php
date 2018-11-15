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

  // remove job order conditional submit button
  if(isset($_POST['remove'])){
    $id = $_POST['jo_id'];

    $query = "UPDATE `JobOrder` SET `status` = 'removed' WHERE `orderID` = $id";

    if(mysqli_query($conn,$query)){
      echo "<script>alert('Removed Job Order from list!')</script>";
    }

  }



 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Job Orders in Production
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Job Order ID</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Job Order Type</th>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if($result = mysqli_query($conn,'SELECT JobOrder.orderID AS ID,
                                                        Customer.name AS custname,
                                                        JobOrder.dueDate AS duedate,
                                                        JobOrder.totalPrice AS price,
                                                        JobOrder.type AS type,
                                                        JobOrder.status AS status
                                                FROM JobOrder
                                                INNER JOIN Customer ON JobOrder.customerID =Customer.customerID
                                                WHERE JobOrder.status = "Production"')){


                    while($row = mysqli_fetch_array($result)){
                        $id = $row['ID'];
                        $name = $row['custname'];
                        $price = $row['price'];
                        $status = $row['status'];
                        $duedate = $row['duedate'];
                        $type = $row['type'];

                        echo '<tr>';
                          echo '<td class="text-center">';
                              echo $id;
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $name;
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $type;
                          echo '</td>';

                          echo '<td class="text-center">';
                            echo $duedate;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $status;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo '<a href="viewIndivProdJO.php?id='.$id.'"><button type="button" class="btn btn-primary btn-sm">View Details</button></a>  ';
                          echo '</td>';

                        echo '</tr>';

                      }
                    }
                    ?>

                  <br><br>
                </tbody></table>



        </div>
    </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
