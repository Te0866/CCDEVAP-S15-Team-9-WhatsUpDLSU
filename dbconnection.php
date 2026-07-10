<?php
$conn = mysqli_connect("localhost", "root", "Password@1234", "whatsupdlsu");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
