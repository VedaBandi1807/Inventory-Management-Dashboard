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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
  $name = !empty($_POST['Name']) ? $_POST['Name'] : $product['Name'];
  $description = !empty($_POST['description']) ? $_POST['description'] : $product['description'];
  $unit_price = !empty($_POST['unitPrice']) ? $_POST['unitPrice'] : $product['UnitPrice'];
  $selling_price = !empty($_POST['sellingPrice']) ? $_POST['sellingPrice'] : $product['SellingPrice'];
  $inventory_count = !empty($_POST['inventoryCount']) ? $_POST['inventoryCount'] : $product['InventoryCount'];
  $reorder_threshold = !empty($_POST['reorderThreshold']) ? $_POST['reorderThreshold'] : $product['ReorderThreshold'];
  $vendor_id = !empty($_POST['vendorName']) ? $_POST['vendorName'] : $product['VendorID'];

  // Debugging output
  echo "Product ID: " . $product_id . "<br>";
  echo "Name: $name<br>";
  echo "Description: $description<br>";
  echo "Unit Price: $unit_price<br>";
  echo "Selling Price: $selling_price<br>";
  echo "Inventory Count: $inventory_count<br>";
  echo "Reorder Threshold: $reorder_threshold<br>";
  echo "Vendor ID: $vendor_id<br>";

  $check_vendor_sql = "SELECT COUNT(*) FROM vendor WHERE VendorID = '$vendor_id'";
  $result = $conn->query($check_vendor_sql);
  $row = $result->fetch_assoc();

  if ($row['COUNT(*)'] == 0) {
    echo "Error: Vendor does not exist.";
  } else {
    // Proceed with inserting the product into the product table
    echo "Trying to insert";
    $sql = "INSERT INTO product (Name, Description, UnitPrice, SellingPrice, InventoryCount, ReorderThreshold, VendorID)
            VALUES ('$name', '$description', $unit_price, $selling_price, $inventory_count, $reorder_threshold, '$vendor_id')";

    if ($conn->query($sql) === TRUE) {
      echo "New product added successfully.";
    } else {
      echo "Error: " . $conn->error;
    }
  }
}

$sql = "SELECT VendorID, Name FROM vendor";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <title>addProduct</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
  <link
    rel="stylesheet"
    href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <div id="global-loader">
    <div class="whirly-loader"></div>
  </div>

  <div class="main-wrapper">
    <div class="header">
      <div class="header-left active">
        <a href="index.html" class="logo">
          <img src="assets/img/logo.png" alt="" />
        </a>
        <a href="index.html" class="logo-small">
          <img src="assets/img/logo-small.png" alt="" />
        </a>
        <a id="toggle_btn" href="javascript:void(0);"> </a>
      </div>

      <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </a>

      <ul class="nav user-menu">
        <li class="nav-item dropdown has-arrow main-drop">
          <a
            href="javascript:void(0);"
            class="dropdown-toggle nav-link userset"
            data-bs-toggle="dropdown">
            <span class="user-img"><img src="assets/img/profile_pic.jpg" alt="" />
              <span class="status online"></span></span>
          </a>
          <div class="dropdown-menu menu-drop-user">
            <div class="profilename">
              <div class="profileset">
                <span class="user-img"><img src="assets/img/profile_pic.jpg" alt="" />
                  <span class="status online"></span></span>
                <div class="profilesets">
                  <h6>John Doe</h6>
                  <h5>Admin</h5>
                </div>
              </div>
              <hr class="m-0" />
              <a class="dropdown-item" href="profile.html">
                <i class="me-2" data-feather="user"></i> My Profile</a>
              <hr class="m-0" />
              <a class="dropdown-item logout pb-0" href="homepage.html"><img
                  src="assets/img/icons/log-out.svg"
                  class="me-2"
                  alt="img" />Logout</a>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div class="sidebar" id="sidebar">
      <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
          <ul>
            <li>
              <a
                href="index.html"
                onclick="showPage('dashboard')"><img src="assets/img/icons/dashboard.svg" alt="img" /><span>
                  Dashboard</span>
              </a>
            </li>
            <li>
              <a href="products.php" class="active" onclick="showPage('products')"><img src="assets/img/icons/product.svg" alt="img" /><span>
                  Products</span>
              </a>
            </li>
            <li>
              <a href="newsales.php" onclick="showPage('sales')"><img src="assets/img/icons/sales1.svg" alt="img" /><span>
                  New Sale</span>
              </a>
            </li>
            <li>
              <a href="orders.php" onclick="showPage('orders')"><img src="assets/img/icons/expense1.svg" alt="img" /><span>
                  Customer Orders</span>
              </a>
            </li>
            <li>
              <a href="purchases.php" onclick="showPage('purchases')"><img src="assets/img/icons/purchase1.svg" alt="img" /><span>
                  Purchases</span>
              </a>
            </li>
            <li>
              <a href="customers.php" onclick="showPage('customers')"><img src="assets/img/icons/users1.svg" alt="img" /><span>
                  Customers</span>
              </a>
            </li>
            <li>
              <a href="vendors.php" onclick="showPage('vendors')"><img src="assets/img/icons/transfer1.svg" alt="img" /><span>
                  Vendors</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Product Add</h4>
            <h6>Create new product</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form action="addproduct.php" method="POST">
              <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="Name">Product Name</label>
                    <input type="text" name="Name" required />
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="unitPrice">Unit Price</label>
                    <input type="number" class="form-control" id="unitPrice" name="unitPrice" step="0.01" min="0" required>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="sellingPrice">Selling Price</label>
                    <input type="number" class="form-control" id="sellingPrice" name="sellingPrice" step="0.01" min="0" required>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="reorderThreshold">Reorder Threshold</label>
                    <input type="number" class="form-control" id="reorderThreshold" name="reorderThreshold" required>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="vendorName">Vendor</label>
                    <select class="select" id="vendorName" name="vendorName" required>
                      <!-- Options will be populated from the Vendor table -->
                      <option value="">Select Vendor</option>
                      <!-- Assuming these vendor options are dynamically generated from the database -->
                      <?php
                      // Check if there are vendors in the database
                      if ($result->num_rows > 0) {
                        // Output the vendor options
                        while ($row = $result->fetch_assoc()) {
                          echo "<option value='" . $row['VendorID'] . "'>" . $row['Name'] . "</option>";
                        }
                      } else {
                        echo "<option value=''>No vendors available</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label for="inventoryCount">Inventory Count</label>
                    <input type="number" class="form-control" id="inventoryCount" name="inventoryCount" required>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description"></textarea>
                  </div>
                </div>
                <div class="col-lg-12">
                  <button type="submit" name="add_product" class="btn btn-submit me-2">Submit</button>
                  <a href="products.php" class="btn btn-cancel">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/jquery.slimscroll.min.js"></script>
  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/plugins/select2/js/select2.min.js"></script>
  <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
  <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>