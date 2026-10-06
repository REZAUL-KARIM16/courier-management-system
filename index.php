<?php
//  connect database
include 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Courier Management System</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Dashboard</h2>
    <p style="color:#8a8fa3; margin-top:-10px; margin-bottom:24px;">This system is used to manage Customers, Parcels, Branches, Delivery Agents, Deliveries, and Payments.</p>

    <div class="stat-grid">
        <?php
        
        $modules = [
            ["label" => "Customers", "table" => "Customers", "link" => "customers.php"],
            ["label" => "Branches", "table" => "Branches", "link" => "branches.php"],
            ["label" => "Delivery Agents", "table" => "Delivery_Agents", "link" => "delivery_agents.php"],
            ["label" => "Parcels", "table" => "Parcels", "link" => "parcels.php"],
            ["label" => "Deliveries", "table" => "Deliveries", "link" => "deliveries.php"],
            ["label" => "Payments", "table" => "Payments", "link" => "payments.php"],
        ];

        foreach ($modules as $m) {
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM " . $m['table']);
            $row = mysqli_fetch_assoc($result);
            echo "<div class='stat-card'>";
            echo "<div class='stat-label'>" . $m['label'] . "</div>";
            echo "<div class='stat-num'>" . $row['total'] . "</div>";
            echo "<a href='" . $m['link'] . "'>Open →</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>
</body>
</html>
