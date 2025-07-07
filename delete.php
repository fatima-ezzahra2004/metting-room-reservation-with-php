<?php
include("dbConfig.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $connt->prepare("DELETE FROM reservs WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "fail";
    }
    $stmt->close();
}
?>
