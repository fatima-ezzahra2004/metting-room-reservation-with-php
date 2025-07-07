<?php
session_start();
require_once('../dbConfig.php');

// Vérification admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$roomId = (int)$_GET['id'];
$error = '';
$success = '';

// Récupérer la salle existante
$result = mysqli_query($connt, "SELECT * FROM rooms WHERE id = $roomId");
$room = mysqli_fetch_assoc($result);

if (!$room) {
    header("Location: rooms.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer données du formulaire
    $name = mysqli_real_escape_string($connt, $_POST['name']);
    $capacity = (int)$_POST['capacity'];
    $location = mysqli_real_escape_string($connt, $_POST['location']);
    $description = mysqli_real_escape_string($connt, $_POST['description']);

    if (empty($name)) {
        $error = "Le nom de la salle est obligatoire.";
    } elseif ($capacity <= 0) {
        $error = "La capacité doit être un nombre positif.";
    } else {
        // Mise à jour dans la BDD
        $sql = "UPDATE rooms SET
                name = '$name',
                capacity = $capacity,
                location = '$location',
                description = '$description'
                WHERE id = $roomId";

        if (mysqli_query($connt, $sql)) {
            $success = "Salle mise à jour avec succès.";
            // Recharger les données
            $result = mysqli_query($connt, "SELECT * FROM rooms WHERE id = $roomId");
            $room = mysqli_fetch_assoc($result);
        } else {
            $error = "Erreur lors de la mise à jour : " . mysqli_error($connt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Modifier Salle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="background-color: #16181B; color: white; padding: 20px;">

  <div class="container">
    <h2 style="color:#ff7f0e;">Edit the room</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($room['name']) ?>" required />
      </div>

      <div class="mb-3">
        <label for="capacity" class="form-label">ability</label>
        <input type="number" id="capacity" name="capacity" class="form-control" value="<?= htmlspecialchars($room['capacity']) ?>" min="1" required />
      </div>

      <div class="mb-3">
        <label for="location" class="form-label">Localisation</label>
        <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($room['location']) ?>" />
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($room['description']) ?></textarea>
      </div>

      <button type="submit" class="btn btn-warning">To update</button>
      <a href="rooms.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
  </div>

</body>
</html>
