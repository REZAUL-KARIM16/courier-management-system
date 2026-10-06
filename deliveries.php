<?php
include 'includes/db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM Deliveries WHERE delivery_id=$id");
    header("Location: deliveries.php");
    exit;
}

if (isset($_POST['submit'])) {
    $parcel_id = $_POST['parcel_id'];
    $agent_id = $_POST['agent_id'];
    $delivery_date = $_POST['delivery_date'];
    $delivery_status = $_POST['delivery_status'];

    if ($_POST['delivery_id'] == "") {
        mysqli_query($conn, "INSERT INTO Deliveries (parcel_id, agent_id, delivery_date, delivery_status) VALUES ('$parcel_id','$agent_id','$delivery_date','$delivery_status')");
    } else {
        $id = $_POST['delivery_id'];
        mysqli_query($conn, "UPDATE Deliveries SET parcel_id='$parcel_id', agent_id='$agent_id', delivery_date='$delivery_date', delivery_status='$delivery_status' WHERE delivery_id=$id");
    }
    header("Location: deliveries.php");
    exit;
}

$edit_id = "";
$edit_parcel_id = "";
$edit_agent_id = "";
$edit_delivery_date = "";
$edit_delivery_status = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM Deliveries WHERE delivery_id=$id");
    $row = mysqli_fetch_assoc($result);

    $edit_id = $row['delivery_id'];
    $edit_parcel_id = $row['parcel_id'];
    $edit_agent_id = $row['agent_id'];
    $edit_delivery_date = $row['delivery_date'];
    $edit_delivery_status = $row['delivery_status'];
}

$parcels = mysqli_query($conn, "SELECT * FROM Parcels");
$agents = mysqli_query($conn, "SELECT * FROM Delivery_Agents");
$status_list = array("Pending", "In Transit", "Delivered", "Failed");

$all_deliveries = mysqli_query($conn, "SELECT d.*, p.description, a.name AS agent_name FROM Deliveries d LEFT JOIN Parcels p ON d.parcel_id=p.parcel_id LEFT JOIN Delivery_Agents a ON d.agent_id=a.agent_id ORDER BY d.delivery_id DESC");

function delivery_badge($status) {
    $class = "status-pending";
    if ($status == "In Transit") $class = "status-transit";
    if ($status == "Delivered") $class = "status-delivered";
    if ($status == "Failed") $class = "status-failed";
    return "<span class='status $class'>$status</span>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Deliveries</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Deliveries</h2>

    <form method="POST">
        <input type="hidden" name="delivery_id" value="<?php echo $edit_id; ?>">

        <label>Parcel</label>
        <select name="parcel_id" required>
            <option value="">-- Select Parcel --</option>
            <?php
            while ($p = mysqli_fetch_assoc($parcels)) {
                if ($p['parcel_id'] == $edit_parcel_id) {
                    echo "<option value='" . $p['parcel_id'] . "' selected>Parcel #" . $p['parcel_id'] . " - " . $p['description'] . "</option>";
                } else {
                    echo "<option value='" . $p['parcel_id'] . "'>Parcel #" . $p['parcel_id'] . " - " . $p['description'] . "</option>";
                }
            }
            ?>
        </select>

        <label>Delivery Agent</label>
        <select name="agent_id" required>
            <option value="">-- Select Agent --</option>
            <?php
            while ($a = mysqli_fetch_assoc($agents)) {
                if ($a['agent_id'] == $edit_agent_id) {
                    echo "<option value='" . $a['agent_id'] . "' selected>" . $a['name'] . "</option>";
                } else {
                    echo "<option value='" . $a['agent_id'] . "'>" . $a['name'] . "</option>";
                }
            }
            ?>
        </select>

        <label>Delivery Date</label>
        <input type="date" name="delivery_date" value="<?php echo $edit_delivery_date; ?>" required>

        <label>Delivery Status</label>
        <select name="delivery_status">
            <?php
            foreach ($status_list as $s) {
                if ($s == $edit_delivery_status) {
                    echo "<option value='$s' selected>$s</option>";
                } else {
                    echo "<option value='$s'>$s</option>";
                }
            }
            ?>
        </select>

        <?php
        if ($edit_id == "") {
            echo "<button class='btn' type='submit' name='submit'>+ Add Delivery</button>";
        } else {
            echo "<button class='btn' type='submit' name='submit'>Update Delivery</button>";
        }
        ?>
    </form>

    <table>
        <tr><th>ID</th><th>Parcel</th><th>Agent</th><th>Date</th><th>Status</th><th>Action</th></tr>
        <?php
        while ($row = mysqli_fetch_assoc($all_deliveries)) {
            echo "<tr>";
            echo "<td>" . $row['delivery_id'] . "</td>";
            echo "<td>Parcel #" . $row['parcel_id'] . " - " . $row['description'] . "</td>";
            echo "<td>" . $row['agent_name'] . "</td>";
            echo "<td>" . $row['delivery_date'] . "</td>";
            echo "<td>" . delivery_badge($row['delivery_status']) . "</td>";
            echo "<td class='action-links'>";
            echo "<a href='deliveries.php?edit=" . $row['delivery_id'] . "'>Edit</a>";
            echo "<a href='deliveries.php?delete=" . $row['delivery_id'] . "' onclick=\"return confirm('Delete?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
