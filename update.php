<?php
include("dbConfig.php");

if (
    isset($_POST['id']) &&
    isset($_POST['title']) &&
    isset($_POST['startDate']) &&
    isset($_POST['endDate'])
) {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $stmt = $connt->prepare("UPDATE reservs SET title=?, start=?, end=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $startDate, $endDate, $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "fail";
    }
    $stmt->close();
}
?>
