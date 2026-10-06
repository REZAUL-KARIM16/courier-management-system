<?php
include 'includes/db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM Branches WHERE branch_id=$id");
    header("Location: branches.php");
    exit;
}

if (isset($_POST['submit'])) {
    $branch_name = $_POST['branch_name'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];

    if ($_POST['branch_id'] == "") {
        mysqli_query($conn, "INSERT INTO Branches (branch_name, location, phone) VALUES ('$branch_name','$location','$phone')");
    } else {
        $id = $_POST['branch_id'];
        mysqli_query($conn, "UPDATE Branches SET branch_name='$branch_name', location='$location', phone='$phone' WHERE branch_id=$id");
    }
    header("Location: branches.php");
    exit;
}

$edit_id = "";
$edit_name = "";
$edit_location = "";
$edit_phone = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM Branches WHERE branch_id=$id");
    $row = mysqli_fetch_assoc($result);

    $edit_id = $row['branch_id'];
    $edit_name = $row['branch_name'];
    $edit_location = $row['location'];
    $edit_phone = $row['phone'];
}

$all_branches = mysqli_query($conn, "SELECT * FROM Branches ORDER BY branch_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Branches</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Branches</h2>

    <form method="POST">
        <input type="hidden" name="branch_id" value="<?php echo $edit_id; ?>">

        <label>Branch Name</label>
        <input type="text" name="branch_name" value="<?php echo $edit_name; ?>" required>

        <label>Location</label>
        <input type="text" name="location" value="<?php echo $edit_location; ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $edit_phone; ?>">

        <?php
        if ($edit_id == "") {
            echo "<button class='btn' type='submit' name='submit'>+ Add Branch</button>";
        } else {
            echo "<button class='btn' type='submit' name='submit'>Update Branch</button>";
        }
        ?>
    </form>

    <table>
        <tr><th>ID</th><th>Branch Name</th><th>Location</th><th>Phone</th><th>Action</th></tr>
        <?php
        while ($row = mysqli_fetch_assoc($all_branches)) {
            echo "<tr>";
            echo "<td>" . $row['branch_id'] . "</td>";
            echo "<td>" . $row['branch_name'] . "</td>";
            echo "<td>" . $row['location'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td class='action-links'>";
            echo "<a href='branches.php?edit=" . $row['branch_id'] . "'>Edit</a>";
            echo "<a href='branches.php?delete=" . $row['branch_id'] . "' onclick=\"return confirm('Delete?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
