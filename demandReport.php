<?php
/**
 * Created by PhpStorm.
 * User: jmcervantes02
 * Date: 20/11/2018
 * Time: 6:47 PM
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


<div class="container">
  <br>
    <div class="row">
        <div class="col-lg-12" align="center">
            <h1 class="text-center" align="center"><br>
                Top Products    
            </h1>
            <h6 class="text-center">
                Enter a Date Range
            </h6>
            <form method="post" class="text-center">
                <input type="date" id="txtDateMax" name="startDate">
                <input type="date" id="endPicker" name="endDate"><br>
                <input type="submit" name="search">
            </form>
        </div>
    </div>
    <div class="row">

<?php
if (isset($_POST['search'])){
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    echo '<h2 class="text-center">Transactions between ';
    echo $startDate;
    echo ' and ';
    echo $endDate;
    echo '</h2><br>';

    echo        '<table class="columns" align="center">';
    echo            '<tr>';
    echo                '<td><div id="piechart" style="width: 450px; height: 350px;border: 1px solid #ccc"></div></td>';
    echo                '<td><div id="piechart2" style="width: 450px; height: 350px;border: 1px solid #ccc"></div></td>';
    echo            '</tr>';
    echo        '</table>';
    echo        '<table class="columns" align="center">';


    $connect = mysqli_connect("localhost", "root", "", "capstone_daila");
    $query = "SELECT product.name as name,
                     SUM(productsales.quantity) as 'products sold'
                    FROM productsales
                    JOIN product on productsales.productID=product.productID
                    JOIN sales on productsales.salesID=sales.salesID
                    WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                    GROUP BY product.name
                    ORDER BY `products sold`  DESC
                    LIMIT 5";
    $result = mysqli_query($connect, $query);

    $query2 = "SELECT product.name as name, SUM(productsales.subTotal) as 'total sales'
                    FROM productsales
                    JOIN product on productsales.productID=product.productID
                    JOIN sales on productsales.salesID=sales.salesID
                    WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                    GROUP BY product.name
                    ORDER BY `total sales`  DESC
                    LIMIT 5";
    $result2 = mysqli_query($connect, $query2);

    $query3 = "SELECT y.name as 'name', y.quantitySold 'Quantity Sold', y.saleDate FROM
                    (SELECT x.name, x.quantitySold, x.saleDate FROM
                        (SELECT product.name as 'name', COALESCE (productsales.quantity, 0) as ' quantitySold' , COALESCE (sales.saleDate, 'NO SALE') as 'saleDate' 
                            FROM product 
                            LEFT JOIN productsales ON product.productID = productsales.productID 
                            LEFT JOIN sales ON productsales.salesID = sales.salesID 
                            
                            UNION ALL 
                            
                            SELECT product.name as 'name', COALESCE (productsales.quantity, 0) as ' quantitySold' , COALESCE (sales.saleDate, 'NO SALE') as 'saleDate'
                            FROM productsales 
                            LEFT JOIN product ON productsales.productID = product.productID 
                            LEFT JOIN sales ON productsales.salesID = sales.salesID 
                            WHERE product.name IS NULL
                            
                            UNION ALL 
                            
                            SELECT product.name as 'name', COALESCE (productsales.quantity, 0) as ' quantitySold' , COALESCE (sales.saleDate, 'NO SALE') as 'saleDate'
                            FROM sales 
                            LEFT JOIN productsales ON sales.salesID = productsales.salesID 
                            LEFT JOIN product ON productsales.productID = product.productID
                        WHERE product.name IS NULL AND productsales.quantity IS NULL) x
                        WHERE x.name IS NOT NULL AND x.saleDate BETWEEN '$startDate' AND '$endDate' OR x.quantitySold = 0) y
                    WHERE y.name IS NOT NULL AND y.quantitySold = 0";
    $result3 = mysqli_query($connect, $query3);
    
    echo            '<tr>';
    echo            '<td>Products Not Sold: </td>';
                    while($row = mysqli_fetch_array($result3)){
                        $productName = $row['name'];
    echo                '<td>';
    echo                $productName;
    echo                '</td>';
                    }
    echo            '</tr>';
    echo        '</table>';
}

else{

   $endDate = date("Y-m-d");
   $startDate = date("Y-m-d",strtotime("-7 days"));

  echo '<h2 class="text-center">Transactions between ';
  echo date("F d Y",strtotime("-7 days"));;
  echo ' and ';
  echo date("F d Y");
  echo '</h2><br>';

  echo        '<table class="columns" align="center">';
  echo            '<tr>';
  echo                '<td><div id="piechart" style="width: 450px; height: 350px;border: 1px solid #ccc"></div></td>';
  echo                '<td><div id="piechart2" style="width: 450px; height: 350px;border: 1px solid #ccc"></div></td>';
  echo            '</tr>';
  echo        '</table>';
  echo        '<table class="columns" align="center">';
  
  $connect = mysqli_connect("localhost", "root", "", "capstone_daila");
  $query = "SELECT product.name as name,SUM(productsales.quantity) as 'products sold'
                  FROM productsales
                  JOIN product on productsales.productID=product.productID
                  JOIN sales on productsales.salesID=sales.salesID
                  WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                  GROUP BY product.name
                  ORDER BY `products sold`  DESC
                  LIMIT 5";
  $result = mysqli_query($connect, $query);
  $query2 = "SELECT product.name as name, SUM(productsales.subTotal) as 'total sales'
                  FROM productsales
                  JOIN product on productsales.productID=product.productID
                  JOIN sales on productsales.salesID=sales.salesID
                  WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                  GROUP BY product.name
                  ORDER BY `total sales`  DESC
                  LIMIT 5";
  $result2 = mysqli_query($connect, $query2);

  $query3 = "SELECT y.name as 'name', y.quantitySold 'Quantity Sold', y.saleDate FROM
                (SELECT x.name, x.quantitySold, x.saleDate FROM
                    (SELECT product.name as 'name', COALESCE (productsales.quantity, 0) as ' quantitySold' , COALESCE (sales.saleDate, 'NO SALE') as 'saleDate' 
                        FROM product 
                        LEFT JOIN productsales ON product.productID = productsales.productID 
                        LEFT JOIN sales ON productsales.salesID = sales.salesID 
                        
                        UNION ALL 
                        
                        SELECT product.name as 'name', COALESCE (productsales.quantity, 0) as ' quantitySold' , COALESCE (sales.saleDate, 'NO SALE') as 'saleDate'
                        FROM productsales 
                        LEFT JOIN product ON productsales.productID = product.productID 
                        LEFT JOIN sales ON productsales.salesID = sales.salesID 
                        WHERE product.name IS NULL
                        
                        UNION ALL 
                        
                        SELECT product.name as 'name', COALESCE (productsales.quantity, 0) as ' quantitySold' , COALESCE (sales.saleDate, 'NO SALE') as 'saleDate'
                        FROM sales 
                        LEFT JOIN productsales ON sales.salesID = productsales.salesID 
                        LEFT JOIN product ON productsales.productID = product.productID
                    WHERE product.name IS NULL AND productsales.quantity IS NULL) x
                    WHERE x.name IS NOT NULL AND x.saleDate BETWEEN '$startDate' AND '$endDate' OR x.quantitySold = 0) y
                WHERE y.name IS NOT NULL AND y.quantitySold = 0";
    $result3 = mysqli_query($connect, $query3);

    echo            '<tr>';
    echo            '<td>Products Not Sold: </td>';
                    while($row = mysqli_fetch_array($result3)){
                        $productName = $row['name'];
    echo                '<td>';
    echo                $productName;
    echo                '</td>';
                    }
    echo            '</tr>';
    echo        '</table>';
}
?>

</div>

</div>
</body>
</html>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart()
    {
        var data = google.visualization.arrayToDataTable([
            ['name', 'products sold'],
            <?php
            while($row = mysqli_fetch_array($result)){
                echo "['".$row["name"]."', ".$row["products sold"]."],";   
            }
            ?>
        ]);

        var data2 = google.visualization.arrayToDataTable([
            ['name', 'total sales'],
            <?php
            while ($row = mysqli_fetch_array($result2)){
                echo "['".$row["name"]."', ".$row["total sales"]."],";
            }
            ?>
        ]);

        var data3 = google.visualization.arrayToDataTable([
            ['name', 'Quantity Sold'],
            <?php
            while ($row = mysqli_fetch_array($result3)){
                echo "['".$row["name"]."', ".$row["Quantity Sold"]."],";
            }
            ?>
        ]);


        var options = {
            title: 'Top Units Sold'
        };

        var options2 = {
            title: 'Top Gross Sales'
        };

        var options3 = {
            title: 'Products Not Sold'
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('piechart'));
        chart.draw(data, options);

        var chart2 = new google.visualization.ColumnChart(document.getElementById('piechart2'));
        chart2.draw(data2, options2);

        var chart3 = new google.visualization.ColumnChart(document.getElementById('piechart3'));
        chart3.draw(data3, options3);
    }
</script>
<script>
    document.getElementById('txtDateMax').onchange = function () {
        document.getElementById('endPicker').setAttribute('min',  this.value);
    };
</script>
