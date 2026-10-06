<?php
include 'includes/db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM Payments WHERE payment_id=$id");
    header("Location: payments.php");
    exit;
}

if (isset($_POST['submit'])) {
    $parcel_id = $_POST['parcel_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];

    if ($_POST['payment_id'] == "") {
        mysqli_query($conn, "INSERT INTO Payments (parcel_id, amount, payment_date, payment_method, payment_status) VALUES ('$parcel_id','$amount','$payment_date','$payment_method','$payment_status')");
    } else {
        $id = $_POST['payment_id'];
        mysqli_query($conn, "UPDATE Payments SET parcel_id='$parcel_id', amount='$amount', payment_date='$payment_date', payment_method='$payment_method', payment_status='$payment_status' WHERE payment_id=$id");
    }
    header("Location: payments.php");
    exit;
}

$edit_id = "";
$edit_parcel_id = "";
$edit_amount = "";
$edit_payment_date = "";
$edit_payment_method = "";
$edit_payment_status = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM Payments WHERE payment_id=$id");
    $row = mysqli_fetch_assoc($result);

    $edit_id = $row['payment_id'];
    $edit_parcel_id = $row['parcel_id'];
    $edit_amount = $row['amount'];
    $edit_payment_date = $row['payment_date'];
    $edit_payment_method = $row['payment_method'];
    $edit_payment_status = $row['payment_status'];
}

$parcels = mysqli_query($conn, "SELECT * FROM Parcels");
$method_list = array("Cash", "bKash", "Nagad", "Card", "Bank Transfer");
$status_list = array("Paid", "Unpaid", "Refunded");

$all_payments = mysqli_query($conn, "SELECT pay.*, p.description FROM Payments pay LEFT JOIN Parcels p ON pay.parcel_id=p.parcel_id ORDER BY pay.payment_id DESC");

function payment_badge($status) {
    $class = "status-unpaid";
    if ($status == "Paid") $class = "status-paid";
    if ($status == "Refunded") $class = "status-transit";
    return "<span class='status $class'>$status</span>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Payments</h2>

    <form method="POST">
        <input type="hidden" name="payment_id" value="<?php echo $edit_id; ?>">

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

        <label>Amount (Tk)</label>
        <input type="number" step="0.01" name="amount" value="<?php echo $edit_amount; ?>" required>

        <label>Payment Date</label>
        <input type="date" name="payment_date" value="<?php echo $edit_payment_date; ?>" required>

        <label>Payment Method</label>
        <select name="payment_method">
            <?php
            foreach ($method_list as $m) {
                if ($m == $edit_payment_method) {
                    echo "<option value='$m' selected>$m</option>";
                } else {
                    echo "<option value='$m'>$m</option>";
                }
            }
            ?>
        </select>

        <label>Payment Status</label>
        <select name="payment_status">
            <?php
            foreach ($status_list as $s) {
                if ($s == $edit_payment_status) {
                    echo "<option value='$s' selected>$s</option>";
                } else {
                    echo "<option value='$s'>$s</option>";
                }
            }
            ?>
        </select>

        <?php
        if ($edit_id == "") {
            echo "<button class='btn' type='submit' name='submit'>+ Add Payment</button>";
        } else {
            echo "<button class='btn' type='submit' name='submit'>Update Payment</button>";
        }
        ?>
    </form>

    <table>
        <tr><th>ID</th><th>Parcel</th><th>Amount</th><th>Date</th><th>Method</th><th>Status</th><th>Action</th></tr>
        <?php
        while ($row = mysqli_fetch_assoc($all_payments)) {
            echo "<tr>";
            echo "<td>" . $row['payment_id'] . "</td>";
            echo "<td>Parcel #" . $row['parcel_id'] . " - " . $row['description'] . "</td>";
            echo "<td>" . $row['amount'] . "</td>";
            echo "<td>" . $row['payment_date'] . "</td>";
            echo "<td>" . $row['payment_method'] . "</td>";
            echo "<td>" . payment_badge($row['payment_status']) . "</td>";
            echo "<td class='action-links'>";
            echo "<a href='payments.php?edit=" . $row['payment_id'] . "'>Edit</a>";
            echo "<a href='payments.php?delete=" . $row['payment_id'] . "' onclick=\"return confirm('Delete?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
