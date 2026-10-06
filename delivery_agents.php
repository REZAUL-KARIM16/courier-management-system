<?php
include 'includes/db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM Delivery_Agents WHERE agent_id=$id");
    header("Location: delivery_agents.php");
    exit;
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $branch_id = $_POST['branch_id'];

    if ($_POST['agent_id'] == "") {
        mysqli_query($conn, "INSERT INTO Delivery_Agents (name, phone, branch_id) VALUES ('$name','$phone','$branch_id')");
    } else {
        $id = $_POST['agent_id'];
        mysqli_query($conn, "UPDATE Delivery_Agents SET name='$name', phone='$phone', branch_id='$branch_id' WHERE agent_id=$id");
    }
    header("Location: delivery_agents.php");
    exit;
}

$edit_id = "";
$edit_name = "";
$edit_phone = "";
$edit_branch_id = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM Delivery_Agents WHERE agent_id=$id");
    $row = mysqli_fetch_assoc($result);

    $edit_id = $row['agent_id'];
    $edit_name = $row['name'];
    $edit_phone = $row['phone'];
    $edit_branch_id = $row['branch_id'];
}

// dropdown for branch list
$branches = mysqli_query($conn, "SELECT * FROM Branches");

// table e dekhanor jonno agent + branch_name (JOIN)
$all_agents = mysqli_query($conn, "SELECT a.*, b.branch_name FROM Delivery_Agents a LEFT JOIN Branches b ON a.branch_id=b.branch_id ORDER BY a.agent_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delivery Agents</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h2>Delivery Agents</h2>

    <form method="POST">
        <input type="hidden" name="agent_id" value="<?php echo $edit_id; ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?php echo $edit_name; ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $edit_phone; ?>">

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

        <?php
        if ($edit_id == "") {
            echo "<button class='btn' type='submit' name='submit'>+ Add Agent</button>";
        } else {
            echo "<button class='btn' type='submit' name='submit'>Update Agent</button>";
        }
        ?>
    </form>

    <table>
        <tr><th>ID</th><th>Name</th><th>Phone</th><th>Branch</th><th>Action</th></tr>
        <?php
        while ($row = mysqli_fetch_assoc($all_agents)) {
            echo "<tr>";
            echo "<td>" . $row['agent_id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['branch_name'] . "</td>";
            echo "<td class='action-links'>";
            echo "<a href='delivery_agents.php?edit=" . $row['agent_id'] . "'>Edit</a>";
            echo "<a href='delivery_agents.php?delete=" . $row['agent_id'] . "' onclick=\"return confirm('Delete?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
