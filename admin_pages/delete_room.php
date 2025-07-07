<?php
session_start();
require_once('../dbConfig.php');

// Vérifie que l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $roomId = (int)$_GET['id'];

    // Supprimer la salle dans la base
    $query = "DELETE FROM rooms WHERE id = $roomId";
    if (mysqli_query($connt, $query)) {
        // Succès : redirection vers la liste des salles
        header("Location: rooms.php?msg=delete_success");
        exit();
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($connt);
    }
} else {
    // Pas d'id, redirection simple
    header("Location: rooms.php");
    exit();
}
?>
