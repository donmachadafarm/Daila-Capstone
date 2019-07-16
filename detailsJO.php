<?php
/**
 * Created by PhpStorm.
 * User: jmcervantes02
 * Date: 20/11/2018
 * Time: 9:23 AM
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

<?php
    $id = $_GET['id'];

    $query = "SELECT
                customer.company AS cName,
                joborder.orderDate AS od,
                joborder.dueDate AS dd,
                joborder.totalPrice AS TOTAL,
                joborder.remarks

                FROM joborder
                JOIN customer on joborder.customerID=customer.customerID
                WHERE joborder.orderID=$id";

    $sql = mysqli_query($conn,$query);

    $row = mysqli_fetch_array($sql);

    $cName = $row['cName'];
    $orderDate = $row['od'];
    $dueDate = $row['dd'];
    $remarks = $row['remarks'];
    $TOTAL = $row['TOTAL']

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                Details of Job Order ID# <?php echo $id;?>
            </h1>
            <div class="row">
                <div class="col text-center">
                    <h5><br>Customer: <?php echo $cName; ?> </h5>
                </div>

                <div class="col text-center">
                    <h5><br>Order Date: <?php echo $orderDate; ?></h5>
                </div>

                <div class="col text-center">
                    <h5><br>Due Date: <?php echo $dueDate; ?></h5>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                </div>

                <div class="col text-center">
                </div>

                <div class="col text-center">
                    <h5>Total Order Price: <?php echo number_format($TOTAL, 2) ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example"><br>
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Type</th>
                    <th>Quantity Ordered</th>
                    <th>Price Per Unit</th>
                    <th>Sub Total</th>
                </tr>
                </thead>
                <tbody>

                <?php

                $result = mysqli_query($conn, "SELECT product.name AS pName,
                                                            producttype.name AS type,
                                                            receipt.quantity,
                                                            product.productPrice,
                                                            receipt.subTotal AS total
                                                    FROM receipt
                                                    JOIN product on receipt.productID=product.productID
                                                    JOIN producttype on product.productTypeID=producttype.productTypeID
                                                    WHERE receipt.orderID=$id");

                    while ($row = mysqli_fetch_array($result)){

                        $product = $row['pName'];
                        $productType = $row['type'];
                        $quantity = $row['quantity'];
                        $price = $row['productPrice'];
                        $total = $row['total'];

                        echo '<tr>';

                            echo '<td class="text-center">';
                                echo $product;
                            echo '</td>';

                            echo '<td class="text-center">';
                                echo $productType;
                            echo '</td>';

                            echo '<td class="text-center">';
                                echo $quantity;
                            echo '</td>';

                            echo '<td class="text-center">';
                                echo number_format($price, 2);
                            echo '</td>';

                            echo '<td class="text-center">';
                                echo number_format($total, 2);
                            echo '</td>';

                        echo '</tr>';

                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col text-center">
        </div>

        <div class="col text-center">
            <button type="button" class="btn btn-primary" onclick="goBack()" name="button">Back</button>
        </div>

        <div class="col text-center">
        </div>
    </div>

</div>
