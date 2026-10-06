<?php
//  connect database
include 'includes/db_connect.php';

// ============ DELETE ============
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM Customers WHERE customer_id=$id");
    header("Location: customers.php");
    exit;
}

// ============ ADD / UPDATE  ============
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    if ($_POST['customer_id'] == "") {
        mysqli_query($conn, "INSERT INTO Customers (name, phone, email, address) VALUES ('$name','$phone','$email','$address')");
    } else {
        $id = $_POST['customer_id'];
        mysqli_query($conn, "UPDATE Customers SET name='$name', phone='$phone', email='$email', address='$address' WHERE customer_id=$id");
    }
    header("Location: customers.php");
    exit;
}

// ============ EDIT  ============
$edit_id = "";
$edit_name = "";
$edit_phone = "";
$edit_email = "";
$edit_address = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM Customers WHERE customer_id=$id");
    $row = mysqli_fetch_assoc($result);

    $edit_id = $row['customer_id'];
    $edit_name = $row['name'];
    $edit_phone = $row['phone'];
    $edit_email = $row['email'];
    $edit_address = $row['address'];
}

$all_customers = mysqli_query($conn, "SELECT * FROM Customers ORDER BY customer_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Customers</h2>

    <form method="POST">
        <input type="hidden" name="customer_id" value="<?php echo $edit_id; ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?php echo $edit_name; ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $edit_phone; ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $edit_email; ?>">

        <label>Address</label>
        <input type="text" name="address" value="<?php echo $edit_address; ?>">

        <?php
        if ($edit_id == "") {
            echo "<button class='btn' type='submit' name='submit'>+ Add Customer</button>";
        } else {
            echo "<button class='btn' type='submit' name='submit'>Update Customer</button>";
        }
        ?>
    </form>

    <table>
        <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Action</th></tr>
        <?php
        while ($row = mysqli_fetch_assoc($all_customers)) {
            echo "<tr>";
            echo "<td>" . $row['customer_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td class='action-links'>";
            echo "<a href='customers.php?edit=" . $row['customer_id'] . "'>Edit</a>";
            echo "<a href='customers.php?delete=" . $row['customer_id'] . "' onclick=\"return confirm('Delete?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
