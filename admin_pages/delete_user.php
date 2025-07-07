<?php
require_once('../dbConfig.php');

header('Content-Type: text/plain');
error_log("delete_user.php appelé - méthode: " . $_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    error_log("Tentative de suppression pour l'ID: " . $id);
    
    // Vérifier d'abord si l'utilisateur existe
    $check = $connt->prepare("SELECT id FROM members WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        error_log("Utilisateur trouvé - suppression en cours");
        $check->close();
        $stmt = $connt->prepare("DELETE FROM members WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            error_log("Suppression réussie pour l'ID $id");
            echo "success";
        } else {
            $error = $connt->error;
            error_log("Erreur SQL: " . $error);
            echo "Erreur SQL: " . $error;
        }
        $stmt->close();
    } else {
        error_log("Utilisateur non trouvé pour l'ID: " . $id);
        echo "Utilisateur non trouvé";
        $check->close();
    }
} else {
    error_log("Requête invalide - ID non défini ou méthode incorrecte");
    echo "Requête invalide";
}
?>