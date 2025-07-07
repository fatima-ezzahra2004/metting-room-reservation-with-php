<?php
require_once('../dbConfig.php');

header('Content-Type: text/plain');
error_log("edit_user.php appelé - méthode: " . $_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    error_log("Données reçues: " . print_r($_POST, true));
    
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    error_log("Valeurs après traitement - ID: $id, Username: $username, Email: $email, Role: $role");

    if (!empty($username) && !empty($email) && !empty($role)) {
        $stmt = $connt->prepare("UPDATE members SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
        
        if ($stmt->execute()) {
            error_log("Mise à jour réussie pour l'ID $id");
            echo "success";
        } else {
            $error = $connt->error;
            error_log("Erreur SQL: " . $error);
            echo "Erreur SQL: " . $error;
        }
        $stmt->close();
    } else {
        error_log("Champs vides détectés");
        echo "Tous les champs sont requis";
    }
} else {
    error_log("Requête invalide - edit_user non défini ou méthode incorrecte");
    echo "Requête invalide";
}
?>