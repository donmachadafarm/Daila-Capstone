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


 ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><br><br>
                Internal Shipping of Orders
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-borderless table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th class="text-center">Job Order #</th>
                    <th class="text-center">Total Items</th>
                    <th class="text-center">Production Date</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                if($result = mysqli_query($conn,'SELECT JobOrder.orderID AS orid,
                                                        count(Production.orderID) AS count,
                                                        Production.endDate AS date,
                                                        JobOrder.type AS type,
                                                        JobOrder.status AS status
                                                FROM JobOrder
                                                JOIN Production ON Production.orderID = JobOrder.orderID
                                                WHERE JobOrder.status == "Shipping"')){


                    while($row = mysqli_fetch_array($result)){
                        $orderid = $row['orid'];
                        $count = $row['count'];
                        $date = $row['date'];
                        $type = $row['type'];
                        $status = $row['status'];

                        echo '<tr>';
                          echo '<td class="text-center">';
                              echo $orderid;
                          echo '</td>';

                          echo '<td class="text-center">';
                            echo $count;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $date;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $type;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo $status;
                          echo'</td>';

                          echo '<td class="text-center">';
                            echo '<a href="ViewIndivShipping.php?id='.$orderid.'"><button type="button" class="btn btn-primary btn-sm">View Details</button></a> ';
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
