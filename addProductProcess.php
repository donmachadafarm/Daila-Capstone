<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
<!-- heading sections -->

<?php
  // checks if logged in ung user else pupunta sa logout.php to end session
  if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
  }
?>

<?php
  function fill_process_select_box($conn){
    $output = '';
    $query = "SELECT * FROM ProcessType";
    $sql = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($sql)){
      $output .= '<option value="'.$row["processTypeID"].'">'.$row["name"].'</option>';
    }

    return $output;
  }

  // Main submit conditional
  if (isset($_POST['submit'])){
      $prodid = $_GET['id'];
      $seq = $_POST['sequence'];
      $procid = $_POST['process'];
      $timemin = $_POST['timemin'];
      $timesec = $_POST['timesec'];

      $count = count($_POST['process']);
      // print_p($seq);
      if($count>0){

        for($i = 0; $i<$count; $i++){
          $t = ($timemin[$i]*60) +$timesec[$i];
          $query = "INSERT INTO ProductProcess(productID,processTypeID,processSequence,timeNeed) VALUES('{$prodid}','{$procid[$i]}','{$seq[$i]}','{$t}')";

            mysqli_query($conn,$query);

        }


        echo "<script>
          alert('Product Process Added!');
           window.location.replace('viewInventory.php');
              </script>";


      }
    }
?>

<!-- put all the contents here  -->


<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Add Product Process
              </h1>

              <hr class="style1">
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">

            <form method="post" id="insert_form">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">

                    <div class="table-repsonsive">
                       <table class="table table-borderless" id="item_table">
                        <tr>
                         <th></th>
                         <th>Process Type</th>
                         <th>Minutes</th>
                         <th>Seconds</th>
                        </tr>
                        <tr>
                          <td><input type="hidden" class="form-control" name="sequence[]" value="1" />1</td>
                          <td><select name="process[]" class="form-control item_unit" required><option value="" disabled>Select Process</option><?php echo fill_process_select_box($conn); ?></select></td>
                          <td><input type="number" max="59" min="0" name="timemin[]" class="form-control" placeholder="0" value="0" required></td>
                          <td><input type="number" max="59" min="0" name="timesec[]" class="form-control" placeholder="0" required></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table>
                     </div>

                  </div>
                </div>
               </div>
               <div class="col-lg-12">
                 <div align="center">
                  <a href="#confirm" data-target="#confirm" data-toggle="modal"><button type="button" class="btn btn-success">Submit</button></a>
                 </div>
               </div>

               <!-- // modal -->
               <div id="confirm" class="modal fade" role="dialog">
                   <div class="modal-dialog">
                           <div class="modal-content">

                               <div class="modal-header">
                                   <h4>Notice</h4>
                                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                               </div>

                               <div class="modal-body">
                                   <div class="text-center">
                                     <p>
                                       <h6>Confirm Product Process?</h6>
                                       <br>
                                     </p>
                                   </div>
                                   <div class="modal-footer">
                                       <input type="submit" id="submit" name="submit" class="btn btn-success" value="Finish!" />
                                       <button type="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">Close</button>
                                   </div>
                               </div>
                       </div>
                   </div>
               </div>

             </form>

          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var count = 1;
   $(document).on('click', '.add', function(){
      var html = '';
      count++;
      html += '<tr>';
      html += '<td><input type="hidden" name="sequence[]" value="'+count+'" />'+count+'</td>';
      html += '<td><select name="process[]" class="form-control item_unit" required><option value="" disabled>Select Process</option><?php echo fill_process_select_box($conn); ?></select></td>';
      html += '<td><input type="number" max="59" min="0" name="timemin[]" class="form-control" placeholder="0" value="0" required></td>';
      html += '<td><input type="number" max="59" min="0" name="timesec[]" class="form-control" placeholder="0" required></td>';
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
