<?php
include 'includes/db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM Parcels WHERE parcel_id=$id");
    header("Location: parcels.php");
    exit;
}

if (isset($_POST['submit'])) {
    $customer_id = $_POST['customer_id'];
    $branch_id = $_POST['branch_id'];
    $weight = $_POST['weight'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $booking_date = $_POST['booking_date'];

    if ($_POST['parcel_id'] == "") {
        mysqli_query($conn, "INSERT INTO Parcels (customer_id, branch_id, weight, description, status, booking_date) VALUES ('$customer_id','$branch_id','$weight','$description','$status','$booking_date')");
    } else {
        $id = $_POST['parcel_id'];
        mysqli_query($conn, "UPDATE Parcels SET customer_id='$customer_id', branch_id='$branch_id', weight='$weight', description='$description', status='$status', booking_date='$booking_date' WHERE parcel_id=$id");
    }
    header("Location: parcels.php");
    exit;
}

$edit_id = "";
$edit_customer_id = "";
$edit_branch_id = "";
$edit_weight = "";
$edit_description = "";
$edit_status = "";
$edit_booking_date = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM Parcels WHERE parcel_id=$id");
    $row = mysqli_fetch_assoc($result);

    $edit_id = $row['parcel_id'];
    $edit_customer_id = $row['customer_id'];
    $edit_branch_id = $row['branch_id'];
    $edit_weight = $row['weight'];
    $edit_description = $row['description'];
    $edit_status = $row['status'];
    $edit_booking_date = $row['booking_date'];
}

$customers = mysqli_query($conn, "SELECT * FROM Customers");
$branches = mysqli_query($conn, "SELECT * FROM Branches");
$status_list = array("Booked", "In Transit", "Delivered", "Cancelled");

$all_parcels = mysqli_query($conn, "SELECT p.*, c.name AS customer_name, b.branch_name FROM Parcels p LEFT JOIN Customers c ON p.customer_id=c.customer_id LEFT JOIN Branches b ON p.branch_id=b.branch_id ORDER BY p.parcel_id DESC");

// status  color badge 
function status_badge($status) {
    $class = "status-booked";
    if ($status == "In Transit") $class = "status-transit";
    if ($status == "Delivered") $class = "status-delivered";
    if ($status == "Cancelled") $class = "status-cancelled";
    return "<span class='status $class'>$status</span>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parcels</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Parcels</h2>

    <form method="POST">
        <input type="hidden" name="parcel_id" value="<?php echo $edit_id; ?>">

        <label>Customer</label>
        <select name="customer_id" required>
            <option value="">-- Select Customer --</option>
            <?php
            while ($c = mysqli_fetch_assoc($customers)) {
                if ($c['customer_id'] == $edit_customer_id) {
                    echo "<option value='" . $c['customer_id'] . "' selected>" . $c['name'] . "</option>";
                } else {
                    echo "<option value='" . $c['customer_id'] . "'>" . $c['name'] . "</option>";
                }
            }
            ?>
        </select>

        <label>Branch</label>
        <select name="branch_id" required>
            <option value="">-- Select Branch --</option>
            <?php
            while ($b = mysqli_fetch_assoc($branches)) {
                if ($b['branch_id'] == $edit_branch_id) {
                    echo "<option value='" . $b['branch_id'] . "' selected>" . $b['branch_name'] . "</option>";
                } else {
                    echo "<option value='" . $b['branch_id'] . "'>" . $b['branch_name'] . "</option>";
                }
            }
            ?>
        </select>

        <label>Weight (kg)</label>
        <input type="number" step="0.01" name="weight" value="<?php echo $edit_weight; ?>" required>

        <label>Description</label>
        <input type="text" name="description" value="<?php echo $edit_description; ?>">

        <label>Status</label>
        <select name="status">
            <?php
            foreach ($status_list as $s) {
                if ($s == $edit_status) {
                    echo "<option value='$s' selected>$s</option>";
                } else {
                    echo "<option value='$s'>$s</option>";
                }
            }
            ?>
        </select>

        <label>Booking Date</label>
        <input type="date" name="booking_date" value="<?php echo $edit_booking_date; ?>" required>

        <?php
        if ($edit_id == "") {
            echo "<button class='btn' type='submit' name='submit'>+ Add Parcel</button>";
        } else {
            echo "<button class='btn' type='submit' name='submit'>Update Parcel</button>";
        }
        ?>
    </form>

    <table>
        <tr><th>ID</th><th>Customer</th><th>Branch</th><th>Weight</th><th>Description</th><th>Status</th><th>Date</th><th>Action</th></tr>
        <?php
        while ($row = mysqli_fetch_assoc($all_parcels)) {
            echo "<tr>";
            echo "<td>" . $row['parcel_id'] . "</td>";
            echo "<td>" . $row['customer_name'] . "</td>";
            echo "<td>" . $row['branch_name'] . "</td>";
            echo "<td>" . $row['weight'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . status_badge($row['status']) . "</td>";
            echo "<td>" . $row['booking_date'] . "</td>";
            echo "<td class='action-links'>";
            echo "<a href='parcels.php?edit=" . $row['parcel_id'] . "'>Edit</a>";
            echo "<a href='parcels.php?delete=" . $row['parcel_id'] . "' onclick=\"return confirm('Delete?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
