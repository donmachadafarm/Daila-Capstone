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
                Product Sales Chart
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

    $connect = mysqli_connect("localhost", "root", "", "capstone_daila");
    $query = "SELECT product.name as name,
                     SUM(productsales.quantity) as 'products sold'
                    FROM productsales
                    JOIN product on productsales.productID=product.productID
                    JOIN sales on productsales.salesID=sales.salesID
                    WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                    GROUP BY product.name
                    ORDER BY `products sold`  DESC";
    $result = mysqli_query($connect, $query);
    $query2 = "SELECT product.name as name, SUM(productsales.subTotal) as 'total sales'
                    FROM productsales
                    JOIN product on productsales.productID=product.productID
                    JOIN sales on productsales.salesID=sales.salesID
                    WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                    GROUP BY product.name
                    ORDER BY `total sales`  DESC";

    $result2 = mysqli_query($connect, $query2);

}
else{

   $endDate = date("Y-m-d");
   $startDate = date("Y-m-d",strtotime("-7 days"));

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

  $connect = mysqli_connect("localhost", "root", "", "capstone_daila");
  $query = "SELECT product.name as name,SUM(productsales.quantity) as 'products sold'
                  FROM productsales
                  JOIN product on productsales.productID=product.productID
                  JOIN sales on productsales.salesID=sales.salesID
                  WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                  GROUP BY product.name
                  ORDER BY `products sold`  DESC";
  $result = mysqli_query($connect, $query);
  $query2 = "SELECT product.name as name, SUM(productsales.subTotal) as 'total sales'
                  FROM productsales
                  JOIN product on productsales.productID=product.productID
                  JOIN sales on productsales.salesID=sales.salesID
                  WHERE sales.saleDate BETWEEN '$startDate' AND '$endDate'
                  GROUP BY product.name
                  ORDER BY `total sales`  DESC";

  $result2 = mysqli_query($connect, $query2);


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
            while($row = mysqli_fetch_array($result))
            {
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
        ])

        var options = {
            title: 'Units Sold',
            pieHole: 0
        };

        var options2 = {
            title: 'Gross Sales',
            pieHole: 0
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);

        var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart2.draw(data2, options2);
    }
</script>
<script>
    document.getElementById('txtDateMax').onchange = function () {
        document.getElementById('endPicker').setAttribute('min',  this.value);
    };
</script>
