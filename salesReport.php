<?php
/**
 * Created by PhpStorm.
 * User: jmcervantes02
 * Date: 05/12/2018
 * Time: 2:28 AM
 */?>
<?php include "includes/sections/header.php"; ?>
<?php include "includes/sections/navbar.php"; ?>
    <!-- heading sections -->

<?php
// checks if logged in ung user else pupunta sa logout.php to end session
if (!isset($_SESSION['userType'])){
    echo "<script>window.location='logout.php'</script>";
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                Daily Sales Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" id="txtDateMax" name="startDate""><br>
                <input type="submit" name="search">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Units Sold</th>
                    <th>Price Per Unit</th>
                    <th>Gross Sales</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!isset($_POST['search'])) {
                    $dateNow = date("Y-m-d");

                    $result = mysqli_query($conn, "SELECT product.name as name, SUM(productsales.quantity) as 'products sold', product.productPrice AS 'unitPrice', SUM(productsales.subTotal) as 'total sales' 
                                                    FROM productsales
                                                    JOIN product on productsales.productID=product.productID
                                                    JOIN sales on productsales.salesID=sales.salesID
                                                    WHERE sales.saleDate = '$dateNow'
                                                    GROUP BY product.name
                                                    ORDER BY `total sales`  DESC");
                    $count = mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(subTotal) as subTotal 
                                                FROM productsales
                                                JOIN sales on productsales.salesID=sales.salesID
                                                WHERE sales.saleDate = '$dateNow'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions on ';
                        echo $dateNow;
                        echo '</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Daily Sales Report of ';
                        echo $dateNow;
                        echo '</h2><br>';

                        while($row = mysqli_fetch_array($result2)){
                            $totalSale = $row['subTotal'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $name = $row['name'];
                            $times = $row['products sold'];
                            $unitPrice = $row['unitPrice'];
                            $totalPrice = $row['total sales'];

                            echo '<tr>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $times;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo number_format($unitPrice, 2);
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo number_format($totalPrice, 2);
                            echo '</td>';

                            echo '</tr>';

                        }
                    }

                }

                else{
                    $startDate = $_POST['startDate'];

                    $result = mysqli_query($conn, "SELECT product.name as name, SUM(productsales.quantity) as 'products sold', product.productPrice AS 'unitPrice', SUM(productsales.subTotal) as 'total sales' 
                                                    FROM productsales
                                                    JOIN product on productsales.productID=product.productID
                                                    JOIN sales on productsales.salesID=sales.salesID
                                                    WHERE sales.saleDate = '$startDate'
                                                    GROUP BY product.name
                                                    ORDER BY `total sales`  DESC");
                    $count = mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(subTotal) as subTotal 
                                                FROM productsales
                                                JOIN sales on productsales.salesID=sales.salesID
                                                WHERE sales.saleDate = '$startDate'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions on ';
                        echo $startDate;
                        echo '</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Daily Sales Report of ';
                        echo $startDate;
                        echo '</h2><br>';

                        while($row = mysqli_fetch_array($result2)){
                            $totalSale = $row['subTotal'];
                        }

                        while ($row = mysqli_fetch_array($result)) {

                            $name = $row['name'];
                            $times = $row['products sold'];
                            $unitPrice = $row['unitPrice'];
                            $totalPrice = $row['total sales'];

                            echo '<tr>';

                            echo '<td class="text-center">';
                            echo $name;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo $times;
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo number_format($unitPrice, 2);
                            echo '</td>';

                            echo '<td class="text-center">';
                            echo number_format($totalPrice, 2);
                            echo '</td>';

                            echo '</tr>';

                        }
                    }

                }

                ?>
                </tbody>
            </table>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="text-right">Total Sales: <?php echo number_format($totalSale, 2) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('datePicker').valueAsDate = new Date();
</script>
