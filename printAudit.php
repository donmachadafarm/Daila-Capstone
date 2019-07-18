<?php include "includes/sections/header.php"; ?>
<?php //include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}
?>

<!-- put all the contents here  -->

<?php
    // $dataArr = $_SESSION['reportMTS'];
    //
    if (isset($_GET['start'])) {
      $start = $_GET['start'];
    }
    if (isset($_GET['end'])) {
      $end = $_GET['end'];
    }
    //
    // $sum = 0;

    $new = $_GET['data'];

?>
<br>
<div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="text-center">
          <h2>Audit Trail Report</h2><br>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card text-center">
          <div class="card-header">
              <?php if (isset($_GET['start'])): ?>
                <h3>Audit between <?php echo $start; ?> and <?php echo $end; ?></h3>
              <?php else: ?>
                <h3>Audit Summary</h3>
              <?php endif; ?>
          </div>
          <div class="card-body">
            <table class="table table-sm table-hover">
              <thead class="thead-light">
                <tr class="table-active">
                  <td class="text-center">Date Checked</td>
                  <td class="text-center">Product</td>
                  <td class="text-center">Starting Count</td>
                  <td class="text-center">Ending Count</td>
                  <td class="text-center">Remarks</td>
                  <td class="text-center">User</td>
                </tr>
              </thead>
              <tbody>

                <?php
                  foreach ($new as $key => $value) {

                      echo '<tr>';

                          echo '<td class="text-center">';
                              echo $value['date'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo get_prodname($conn,$value['prod']);
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $value['old'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $value['count'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo $value['remark'];
                          echo '</td>';

                          echo '<td class="text-center">';
                              echo get_username($conn,$value['user']);
                          echo '</td>';

                      echo '</tr>';

                  }
                 ?>
               </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <div class="text-center">
              <h4>***End of Report***</h4>
            </div><br><br>
            <div class="text-left text-muted">
              <p class="small">Generated by user: <?php echo get_username($conn,$_SESSION['userid']); ?>
                <br><?php echo date('F-d-Y g:i A'); ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>





<?php
  // unset($_SESSION['reportMTS']);
 ?>
