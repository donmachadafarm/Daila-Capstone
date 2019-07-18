<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}

$dataArr = array();
?>

<!-- put all the contents here  -->

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center"><br><br>
                Audit Trail
            </h2>
            <h6 class="text-center">
                Enter a Date Range
            </h6><br>
            <form method="post" class="text-center">
              <div class="row justify-content-md-center">
                <div class="col-md-1">
                  <label class="col-form-label bold"><b>From:</b></label>
                </div>
                <div class="col-md-3">
                  <div class="input-group">
                      <input type="date" name="startDate" class="form-control" max="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>
                <div class="col-md-1">
                  <label class="col-form-label"><b>To:</b></label>
                </div>
                <div class="col-md-3">
                  <div class="input-group">
                      <input type="date" name="endDate" class="form-control">
                  </div>
                </div>
                <div class="col-md-2">
                  <input class="btn btn-primary" type="submit" name="search">
                </div>
              </div>
            </form>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Date Checked</th>
                    <th>Product</th>
                    <th>Starting Count</th>
                    <th>Ending Count</th>
                    <th>Remarks</th>
                    <th>user</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($_POST['search'])){
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];

                    $result = mysqli_query($conn, "SELECT * FROM AuditTrail WHERE dateChange
                                                    BETWEEN '$startDate' AND '$endDate' ORDER BY dateChange DESC");

                    $count = 0;
                    $new = "";
                        while ($row = mysqli_fetch_array($result)) {

                            $date = $row['dateChange'];
                            $prod = $row['productID'];
                            $cont = $row['quantityChange'];
                            $old = $row['oldQuantity'];
                            $remk = $row['remarks'];
                            $user = $row['userID'];


                            echo '<tr>';

                                echo '<td class="text-center">';
                                    echo $date;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo get_prodname($conn,$prod);
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $old;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $cont;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo $remk;
                                echo '</td>';

                                echo '<td class="text-center">';
                                    echo get_username($conn,$user);
                                echo '</td>';

                            echo '</tr>';

                            $dataArr[$count]['date'] = $date;
                            $dataArr[$count]['prod'] = $prod;
                            $dataArr[$count]['old'] = $old;
                            $dataArr[$count]['count'] = $cont;
                            $dataArr[$count]['remark'] = $remk;
                            $dataArr[$count]['user'] = $user;

                            $count++;

                            $new = http_build_query(array('data' => $dataArr));

                        }

                        echo "<div class='col-lg-12'>";
                          echo "<div class=text-center>";
                            echo "<a href='printAudit.php?$new&start=$startDate&end=$endDate' class='btn btn-success'>Print this report</a>";
                          echo "</div>";
                        echo "</div>";
                }else{
                  $result = mysqli_query($conn, "SELECT * FROM AuditTrail WHERE dateChange ORDER BY dateChange DESC");

                  $count = 0;
                      while ($row = mysqli_fetch_array($result)) {

                          $date = $row['dateChange'];
                          $prod = $row['productID'];
                          $cont = $row['quantityChange'];
                          $old = $row['oldQuantity'];
                          $remk = $row['remarks'];
                          $user = $row['userID'];


                          echo '<tr>';

                              echo '<td class="text-center">';
                                  echo $date;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo get_prodname($conn,$prod);
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $old;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $cont;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo $remk;
                              echo '</td>';

                              echo '<td class="text-center">';
                                  echo get_username($conn,$user);
                              echo '</td>';

                          echo '</tr>';

                          $dataArr[$count]['date'] = $date;
                          $dataArr[$count]['prod'] = $prod;
                          $dataArr[$count]['old'] = $old;
                          $dataArr[$count]['count'] = $cont;
                          $dataArr[$count]['remark'] = $remk;
                          $dataArr[$count]['user'] = $user;

                          $count++;

                          $new = http_build_query(array('data' => $dataArr));
                      }
                      echo "<div class='col-lg-12'>";
                        echo "<div class=text-center>";
                          echo "<a href='printAudit.php?$new' class='btn btn-success'>Print this page</a>";
                        echo "</div>";
                      echo "</div>";
                }

                ?>
              </tbody>
            </table><br><br>
        </div>
    </div>
</div>

<script>
    document.getElementById('txtDateMax').onchange = function () {
    document.getElementById('endPicker').setAttribute('min',  this.value);
    };

    $(document).ready(function() {
      $('table.display').DataTable();
        } );

    $(document).ready( function () {
      var table = $('#dataTables-example').DataTable( {
        pageLength : 6,
        lengthMenu: [[6, 10, 20], [6, 10, 20]]
      } )
    } );
</script>
