<?php
/**
 * Created by PhpStorm.
 * User: jmcervantes02
 * Date: 19/11/2018
 * Time: 2:41 PM
 */
?>

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
                List of Customers
            </h1>
            <?php
            $result = mysqli_query($conn,'SELECT customer.name 
                                                FROM Customer 
                                                WHERE customer.customerID!=1');
            while($row = mysqli_fetch_array($result)){
                $name = $row['name'];

                echo '';

            }
            ?>
        </div>
    </div>
</div>

