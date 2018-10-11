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
// Query
if (isset($_POST['submit'])){
    $status="Available";
    $name=$_POST['name'];
    $type=$_POST['type'];
    $date=$_POST['date'];

    if(!isset($message)){
        $query="INSERT into Machine (name,type,acquiredDate,status) values ('{$name}','{$type}','{$date}','{$status}')";
        if (mysqli_query($conn,$query)) {

            echo "<script>
            alert('Equipment $name is created');
          </script>";
        }else {
            echo "<script> alert('Failed to Add Equipment!');
              </script>";
        }
    }else{
        echo "<script> alert('$message');
            </script>";
    }
}/*End of main Submit conditional*/
?>

<!-- put all the contents here  -->


<div class="container">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><br><br>
                    Add Equipment
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="form-group">
                                <p class="form-control-static">
                                    <label>Name:</label></br>
                                    <input type="text" name="name" class="form-control" required>
                                    </br>
                                    <label>Type of Machine:</label></br>
                                    <input type="text" name="type" class="form-control" required>
                                    </br>
                                    <label>Date Acquired:</label></br>
                                    <input type="date" name="date" class="form-control" required>
                                    </br>
                                </p>
                                <input type="submit" name="submit" value="Add Equipment" class="btn btn-success"/></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- end of content -->


<?php include "includes/sections/footer.php"; ?>
