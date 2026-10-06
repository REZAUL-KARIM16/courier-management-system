<?php
// aikhane database er shathe connect kortesi
// mysqli_connect(server, username, password, database_name)
$conn = mysqli_connect("localhost", "root", "", "courier_db");

// jodi connect na hoy, error dekhaia script off kore dibo
if (!$conn) {
    die("Database connect hoy nai: " . mysqli_connect_error());
}
?>
