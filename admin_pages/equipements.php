<?php
require_once('../dbConfig.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Traitement ajout équipement
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipement'])) {
    $name = mysqli_real_escape_string($connt, $_POST['name']);
    $description = mysqli_real_escape_string($connt, $_POST['description']);
    $room_id = (int)$_POST['room_id'];

    $sql = "INSERT INTO equipements (name, description, room_id)
            VALUES ('$name', '$description', $room_id)";
    if (mysqli_query($connt, $sql)) {
        $message = "Équipement ajouté avec succès.";
    } else {
        $message = "Erreur: " . mysqli_error($connt);
    }
}

// Récupérer les équipements avec le nom de la salle
$equipements = mysqli_query($connt, "
    SELECT e.*, r.name AS room_name
    FROM equipements e
    JOIN rooms r ON e.room_id = r.id
    ORDER BY r.name
");

// Récupérer les salles pour le <select>
$salles = mysqli_query($connt, "SELECT id, name FROM rooms");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des équipements</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: rgb(22, 24, 27);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
    }
    h2 {
      color: #ff7f0e;
      margin-bottom: 1.5rem;
      animation: fadeInDown 0.8s ease;
      font-size:16px;
    }
    .fade-item {
      opacity: 0;
      transform: translateY(10px);
      animation: fadeInUp 0.6s ease forwards;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .table th, .table td {
      vertical-align: middle;
      border-color: rgba(255,255,255,0.1);
      font-size:14px;
    }
    .modal-content {
      background-color: #2b2b2b;
      color: white;
    }
    .modal-title {
      color: #ff7f0e;
    }
  </style>
</head>
<body>

<h2><i class="fas fa-tools me-2"></i>Equipment management</h2>

<?php if ($message): ?>
  <div class="alert alert-<?= str_starts_with($message, 'Erreur') ? 'danger' : 'success' ?>">
    <?= htmlspecialchars($message) ?>
  </div>
<?php endif; ?>

<!-- Bouton ajouter -->
<button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#addEquipModal">
  <i class="fas fa-plus"></i> Add equipment
</button>

<!-- Modal ajout -->
<div class="modal fade" id="addEquipModal" tabindex="-1" aria-labelledby="addEquipModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="addEquipModalLabel">Add equipment</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Associated room</label>
            <select class="form-select" name="room_id" required>
              <option value="">-- Choose a room --</option>
              <?php while ($salle = mysqli_fetch_assoc($salles)) : ?>
                <option value="<?= $salle['id'] ?>"><?= htmlspecialchars($salle['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_equipement" class="btn btn-warning">Add</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Tableau des équipements -->
<div class="table-responsive">
  <table class="table table-dark table-hover align-middle shadow-sm" style="border-radius: 10px; overflow: hidden;">
    <thead class="bg-warning text-dark text-uppercase">
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Room</th>
      </tr>
    </thead>
    <tbody>
      <?php $delay = 0.1; ?>
      <?php while ($equip = mysqli_fetch_assoc($equipements)) : ?>
        <tr class="fade-item" style="animation-delay: <?= $delay ?>s;">
          <td><i class="fas fa-wrench text-warning me-1"></i> <?= htmlspecialchars($equip['name']) ?></td>
          <td><?= htmlspecialchars($equip['description']) ?></td>
          <td><i class="fas fa-door-open text-warning me-1"></i> <?= htmlspecialchars($equip['room_name']) ?></td>
        </tr>
        <?php $delay += 0.1; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
