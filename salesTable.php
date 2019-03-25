<?php
/**
 * Created by PhpStorm.
 * User: jmcervantes02
 * Date: 17/11/2018
 * Time: 4:43 PM
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

<!-- put all the contents here  -->

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center"><br><br>
                Product Sales Report
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" id="txtDateMax" name="startDate">
                <input type="date" max="<?php echo date("Y-m-d"); ?>" name="endDate"><br>
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
                if (isset($_POST['search'])){
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $result = mysqli_query($conn, "SELECT product.name as name, SUM(productsales.quantity) as 'products sold', product.productPrice AS 'unitPrice', SUM(productsales.subTotal) as 'total sales'
                                                FROM productsales
                                                JOIN product on productsales.productID=product.productID
                                                JOIN sales on productsales.salesID=sales.salesID
                                                WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                                                GROUP BY product.name
                                                ORDER BY `total sales`  DESC");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(subTotal) as subTotal
                                                FROM productsales
                                                JOIN sales on productsales.salesID=sales.salesID
                                                WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'");

                    if ($count == "0"){
                        echo '<h2 class="text-center">No transactions within the specified range</h2>';
                    }
                    else {

                        echo '<h2 class="text-center">Transactions between ';
                        echo $startDate;
                        echo ' and ';
                        echo $endDate;
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

                        echo '</tbody>';
                        echo '</table>';

                        echo '<div class="container">';
                        echo '<div class="row">';
                        echo '<div class="col-lg-12">';
                        echo '<h4 class="text-right">Total Revenue: ';
                        echo number_format($totalSale, 2);
                        echo '</h4>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';


                    }
                }

                else{
                    $result = mysqli_query($conn, "SELECT product.name as name, SUM(productsales.quantity) as 'products sold', product.productPrice AS 'unitPrice', SUM(productsales.subTotal) as 'total sales'
                                                FROM productsales
                                                JOIN product on productsales.productID=product.productID
                                                JOIN sales on productsales.salesID=sales.salesID
                                                GROUP BY product.name
                                                ORDER BY `total sales`  DESC");
                    $count=mysqli_num_rows($result);

                    $result2 = mysqli_query($conn, "SELECT SUM(subTotal) as subTotal
                                                FROM productsales
                                                JOIN sales on productsales.salesID=sales.salesID");

                    if ($count == "0"){
                        echo '<h2 class="text-center">There are no transactions yet</h2>';
                    }
                    else {

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

                        echo '</tbody>';
                        echo '</table>';

                        echo '<div class="container">';
                        echo '<div class="row">';
                        echo '<div class="col-lg-12">';
                        echo '<h4 class="text-right">Total Revenue: ';
                        echo number_format($totalSale, 2);
                        echo '</h4>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';


                    }
                }

                ?>

        </div>
    </div>
</div>

<script>
    document.getElementById('txtDateMax').onchange = function () {
        document.getElementById('endPicker').setAttribute('min',  this.value);
    };
</script>
