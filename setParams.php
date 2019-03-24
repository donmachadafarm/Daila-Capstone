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
//List all months from now til last year
$thisMonth = date("m-Y");
$oneMonth = date("m-Y", strtotime("-1 months"));
$twoMonths = date("m-Y", strtotime("-2 months"));
$threeMonths = date("m-Y", strtotime("-3 months"));
$fourMonths = date("m-Y", strtotime("-4 months"));
$fiveMonths = date("m-Y", strtotime("-5 months"));
$sixMonths = date("m-Y", strtotime("-6 months"));
$sevenMonths = date("m-Y", strtotime("-7 months"));
$eightMonths = date("m-Y", strtotime("-8 months"));
$nineMonths = date("m-Y", strtotime("-9 months"));
$tenMonths = date("m-Y", strtotime("-10 months"));
$elMonths = date("m-Y", strtotime("-11 months"));
$thisMonthW = date("M-Y");
$oneMonthW = date("M-Y", strtotime("-1 months"));
$twoMonthsW = date("M-Y", strtotime("-2 months"));
$threeMonthsW = date("M-Y", strtotime("-3 months"));
$fourMonthsW = date("M-Y", strtotime("-4 months"));
$fiveMonthsW = date("M-Y", strtotime("-5 months"));
$sixMonthsW = date("M-Y", strtotime("-6 months"));
$sevenMonthsW = date("M-Y", strtotime("-7 months"));
$eightMonthsW = date("M-Y", strtotime("-8 months"));
$nineMonthsW = date("M-Y", strtotime("-9 months"));
$tenMonthsW = date("M-Y", strtotime("-10 months"));
$elMonthsW = date("M-Y", strtotime("-11 months"));

?>

<!-- put all the contents here  -->

<div class="container">
  <div id="page-wrapper">
      <div class="row">
          <div class="col-lg-12">
              <h1 class="page-header"><br><br>
                  Set Forecast Parameters
              </h1>

              <hr class="style1">
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">

            <form method="post" onsubmit="return totalCheck(this)" action="viewInventory.php" id="insert_form" name="weight_form">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="table-repsonsive">
                       <table class="table table-borderless" id="item_table">
                        <tr>
                         <th>Month</th><br>
                         <th>Weight (Must total to 100)</th>
                        </tr>
                        <tr>
                          <input class = 'form-control' type = 'hidden' name = 'example' value = 8>
                          <td><select name="date[]" class="form-control item_unit" required><option value="" disabled>Select Product</option><option value="<?php echo $thisMonth; ?>"><?php echo $thisMonthW; ?></option><option value="<?php echo $oneMonth; ?>"><?php echo $oneMonthW; ?></option><option value="<?php echo $twoMonths; ?>"><?php echo $twoMonthsW; ?></option><option value="<?php echo $threeMonths; ?>"><?php echo $threeMonthsW; ?></option><option value="<?php echo $fourMonths; ?>"><?php echo $fourMonthsW; ?></option><option value="<?php echo $fiveMonths; ?>"><?php echo $fiveMonthsW; ?></option><option value="<?php echo $sixMonths; ?>"><?php echo $sixMonthsW; ?></option><option value="<?php echo $sevenMonths; ?>"><?php echo $sevenMonthsW; ?></option><option value="<?php echo $eightMonths; ?>"><?php echo $eightMonthsW; ?></option><option value="<?php echo $nineMonths; ?>"><?php echo $nineMonthsW; ?></option><option value="<?php echo $tenMonths; ?>"><?php echo $tenMonthsW; ?></option><option value="<?php echo $elMonths; ?>"><?php echo $elMonthsW; ?></option></select></td>
                          <td><input type="number" name="weight[]" oninput="addTotal()" class="form-control item_name" required /></td>
                          <td><button type="button" name="add" class="btn btn-success btn-sm add">+</button></td>
                        </tr>
                       </table>
                    </div>
                  </div>
                </div>
              </div>

                <div class="col-lg-12">
                    <div align="center">
                        <b id = "wtotal"name = "total">Total: </b>
                    </div>
                </div><br>
            
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
                                       <h6>Confirm Job Order?</h6>

                                     </p>
                                   </div>
                                   <div class="modal-footer">
                                       <input type="submit" id="submit" name="choose" class="btn btn-success" value="Continue" onClick="return totalCheck();" />
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

  function addTotal(){
    var arr = document.getElementsByName('weight[]');
    var tot = 0;
    for(var i=0; i<arr.length;i++){
        if(parseInt(arr[i].value)) {
            tot += parseInt(arr[i].value);
        }
    }
    document.getElementById("wtotal").innerHTML = "Total: " + tot;
  }

  function totalCheck(){

    var arr = document.getElementsByName('weight[]');
    var tot = 0;
    for(var i=0; i<arr.length;i++){
        if(parseInt(arr[i].value)) {
            tot += parseInt(arr[i].value);
        }
    }        

    if(tot!=100){
        alert("Please make sure numbers total 100.");
        //document.getElementById("wtotal").innerHTML = "Total: " + tot;
        return false;
    }else{
        return true;
    }

  }

  $(document).ready(function(){

      var count = 1;

   $(document).on('click', '.add', function(){
        if(count<12){
            var html = '';
            count++;
            html += '<tr>';
            html += '<td><select name="date[]" class="form-control item_unit"><option value="" disabled>Select Month</option><option value="<?php echo $thisMonth; ?>"><?php echo $thisMonthW; ?></option><option value="<?php echo $oneMonth; ?>"><?php echo $oneMonthW; ?></option><option value="<?php echo $twoMonths; ?>"><?php echo $twoMonthsW; ?></option><option value="<?php echo $threeMonths; ?>"><?php echo $threeMonthsW; ?></option><option value="<?php echo $fourMonths; ?>"><?php echo $fourMonthsW; ?></option><option value="<?php echo $fiveMonths; ?>"><?php echo $fiveMonthsW; ?></option><option value="<?php echo $sixMonths; ?>"><?php echo $sixMonthsW; ?></option><option value="<?php echo $sevenMonths; ?>"><?php echo $sevenMonthsW; ?></option><option value="<?php echo $eightMonths; ?>"><?php echo $eightMonthsW; ?></option><option value="<?php echo $nineMonths; ?>"><?php echo $nineMonthsW; ?></option><option value="<?php echo $tenMonths; ?>"><?php echo $tenMonthsW; ?></option><option value="<?php echo $elMonths; ?>"><?php echo $elMonthsW; ?></option></select></td>';
            html += '<td><input type="number" oninput="addTotal()" name="weight[]" class="form-control item_name" required /></td>';
            html += '<td><button type="button"  name="remove" class="btn btn-danger btn-sm remove">x</button></td></tr>';
            $('#item_table').append(html);
        }
        if(count==12){
            alert("Cannot exceed 12 inputs.");
        }
     });

     $(document).on('click', '.remove', function(){
      $(this).closest('tr').remove();
      count--;
     });

  });
</script>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
