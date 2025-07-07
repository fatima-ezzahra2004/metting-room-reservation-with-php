<?php
require_once('../dbConfig.php');



$message = '';

// Traitement ajout salle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $name = mysqli_real_escape_string($connt, $_POST['name']);
    $capacity = (int)$_POST['capacity'];
    $location = mysqli_real_escape_string($connt, $_POST['location']);
    $description = mysqli_real_escape_string($connt, $_POST['description']);

    if (empty($name)) {
        $message = "Le nom est requis.";
    } elseif ($capacity <= 0) {
        $message = "Capacité invalide.";
    } else {
        $sql = "INSERT INTO rooms (name, capacity, location, description)
                VALUES ('$name', $capacity, '$location', '$description')";
        if (mysqli_query($connt, $sql)) {
            header("Location: rooms.php?msg=added");
            exit();
        } else {
            $message = "Erreur: " . mysqli_error($connt);
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'added') {
    $message = "Salle ajoutée avec succès.";
}

// Récupération des salles
$rooms = mysqli_query($connt, "SELECT * FROM rooms ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gestion des salles</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      animation: fadeInUp 0.8s ease forwards;
      margin-bottom: 1.5rem;
      font-size:16px;
    }

    .fade-item {
      opacity: 0;
      transform: translateY(10px);
      animation: fadeInUp 0.6s ease forwards;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .table th, .table td {
      vertical-align: middle;
      border-color: rgba(255,255,255,0.1);
      font-size:14px;
    }

    .table td .btn {
      transition: transform 0.2s ease;
    }

    .table td .btn:hover {
      transform: scale(1.1);
    }

    .btn-warning, .btn-danger {
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-warning:hover {
      background-color: #cc7000;
    }

    .btn-danger:hover {
      background-color: #b32424;
    }

    .modal-content {
      background-color: #2b2b2b;
      color: white;
    }

    .modal-title {
      color: #ff7f0e;
    }
    .modal.fade .modal-dialog {
  transform: translateY(-20px);
  transition: transform 0.3s ease-out;
}
.modal.show .modal-dialog {
  transform: translateY(0);
}

  </style>
</head>
<body>

<h2>Room management</h2>

<?php if ($message): ?>
  <div class="alert alert-<?= str_starts_with($message, 'Erreur') ? 'danger' : 'success' ?>">
    <?= htmlspecialchars($message) ?>
  </div>
<?php endif; ?>

<!-- Bouton ajouter -->
<button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#addRoomModal">
  <i class="fas fa-plus"></i> Add a room
</button>

<!-- Modal ajout -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="addRoomModalLabel">Add a room</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Ability</label>
            <input type="number" class="form-control" name="capacity" required />
          </div>
          <div class="mb-3">
            <label class="form-label">Localisation</label>
            <input type="text" class="form-control" name="location" />
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_room" class="btn btn-warning">Add</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Tableau moderne -->
<!-- Tableau moderne sans colonne # mais avec icônes -->
<div class="table-responsive">
  <table class="table table-dark table-hover align-middle shadow-sm" style="border-radius: 10px; overflow: hidden;">
    <thead class="bg-warning text-dark text-uppercase">
      <tr>
        <!-- ❌ Supprimé <th>#</th> -->
        <th>Name</th>
        <th>Ability</th>
        <th>Localisation</th>
        <th>Description</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $delay = 0.1; ?>
      <?php while ($room = mysqli_fetch_assoc($rooms)) : ?>
        <tr class="fade-item" style="animation-delay: <?= $delay ?>s;">
          <!-- ❌ Supprimé <td>ID</td> -->
          <td> <?= htmlspecialchars($room['name']) ?></td>
          <td><span class="badge bg-secondary"><?= htmlspecialchars($room['capacity']) ?> places</span></td>
          <td><i class="fas fa-map-marker-alt text-warning me-1"></i><?= htmlspecialchars($room['location']) ?></td>
          <td><?= htmlspecialchars($room['description']) ?></td>
          <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
              <a href="edit_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-warning d-flex align-items-center gap-1 px-2" title="Modifier">
                <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
              </a>
              <a href="delete_room.php?id=<?= $room['id'] ?>" class="btn btn-sm btn-danger d-flex align-items-center gap-1 px-2" title="Supprimer"
                 onclick="return confirm('Supprimer cette salle ?');">
                <i class="fas fa-trash-alt"></i> <span class="d-none d-md-inline">SDelete</span>
              </a>
            </div>
          </td>
        </tr>
        <?php $delay += 0.1; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
