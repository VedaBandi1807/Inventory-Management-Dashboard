<?php
// database_connection.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$port = 3306;
$username = "root";
$password = "";
$dbname = "InvMangSys";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to fetch results from database
function fetchData($conn, $query)
{
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error . "<br>Query: " . $query);
    }
    return $result->fetch_assoc();
}

// Dashboard Metrics Queries

$totalSalesQuery = "SELECT SUM(TotalAmount) as total_sales_amount 
                    FROM Orders";

// WHERE OrderDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)

$customerCountQuery = "SELECT COUNT(*) as customer_count FROM Customer";
$vendorCountQuery = "SELECT COUNT(*) as vendor_count FROM Vendor";
$orderCountQuery = "SELECT COUNT(*) as order_count 
                    FROM Orders";

// WHERE OrderDate >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)

// Execute queries
$totalSalesAmount = fetchData($conn, $totalSalesQuery)['total_sales_amount'] ?? 0;
$customerCount = fetchData($conn, $customerCountQuery)['customer_count'];
$vendorCount = fetchData($conn, $vendorCountQuery)['vendor_count'];
$orderCount = fetchData($conn, $orderCountQuery)['order_count'];

$monthlySalesQuery = "SELECT CONCAT(MONTHNAME(OrderDate),',',YEAR(OrderDate)) AS month, SUM(TotalAmount) AS total FROM Orders GROUP BY MONTH(OrderDate),YEAR(OrderDate) ORDER BY OrderDate;";
$result = $conn->query($monthlySalesQuery);
$months = [];
$sales = [];

while ($row = $result->fetch_assoc()) {
    $months[] = $row['month'];
    $sales[] = $row['total'];
}

// Fetch vendor-wise sales data
$vendorSalesQuery = "SELECT v.Name AS vendor_name, SUM(vo.TotalAmount) AS total FROM VendorOrders vo
                    JOIN Vendor v ON vo.VendorID = v.VendorID
                    GROUP BY v.Name";
$result = $conn->query($vendorSalesQuery);
$vendors = [];
$vendorSales = [];

while ($row = $result->fetch_assoc()) {
    $vendors[] = $row['vendor_name'];
    $vendorSales[] = $row['total'];
}

// Pass data to JavaScript
echo "<script>
        const months = " . json_encode($months) . ";
        const sales = " . json_encode($sales) . ";
        const vendors = " . json_encode($vendors) . ";
        const vendorSales = " . json_encode($vendorSales) . ";
    </script>";

?>

<!DOCTYPE html>
<html>

<head>
    <!-- Your existing head tags and CSS links -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="page-wrapper">
        <div class="content">
            <div class="row">

                <!-- Customers Count -->
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count">
                        <div class="dash-counts">
                            <h4><?php echo number_format($customerCount); ?></h4>
                            <h5>Customers</h5>
                        </div>
                    </div>
                </div>

                <!-- Vendors Count -->
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4><?php echo number_format($vendorCount); ?></h4>
                            <h5>Vendors</h5>
                        </div>
                    </div>
                </div>

                <!-- Orders Count -->
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das2">
                        <div class="dash-counts">
                            <h4><?php echo number_format($orderCount); ?></h4>
                            <h5>Sales Orders</h5>
                        </div>
                    </div>
                </div>

                <!-- Vendor Orders Count -->
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das3">
                        <div class="dash-counts">
                            <h4>$<?php echo number_format($totalSalesAmount, 2); ?></h4>
                            <h5>Total Sales Amount</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div>
                    <h3>Monthly Sales Breakdown</h3>
                    <canvas id="monthlySalesChart" style="max-height: 400px;"></canvas>
                </div>
                <div>
                    <h3>Vendor-Wise Sales</h3>
                    <canvas id="vendorSalesChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Your existing script tags -->
</body>

</html>