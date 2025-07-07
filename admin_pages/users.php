<?php 
require_once('../dbConfig.php');

// Récupérer les utilisateurs
$users = mysqli_query($connt, "SELECT * FROM members ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gestion des utilisateurs</title>
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

<h2>User management</h2>

<div id="alertMessage"></div>

<!-- Tableau -->
<div class="table-responsive">
  <table class="table table-dark table-hover align-middle shadow-sm" style="border-radius: 10px; overflow: hidden;">
    <thead class="bg-warning text-dark text-uppercase">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $delay = 0.1; ?>
      <?php while($user = mysqli_fetch_assoc($users)) : ?>
      <tr class="fade-item" style="animation-delay: <?= $delay ?>s;">
        <td><i class="fas fa-user text-warning me-1"></i><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span></td>
        <td class="text-center">
          <div class="d-flex justify-content-center gap-2">
            <button 
              class="btn btn-sm btn-warning edit-user d-flex align-items-center gap-1 px-2"
              data-id="<?= $user['id'] ?>"
              data-username="<?= htmlspecialchars($user['username']) ?>"
              data-email="<?= htmlspecialchars($user['email']) ?>"
              data-role="<?= $user['role'] ?>"
            ><i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span></button>

            <button 
              class="btn btn-sm btn-danger delete-user d-flex align-items-center gap-1 px-2"
              data-id="<?= $user['id'] ?>"
            ><i class="fas fa-trash-alt"></i> <span class="d-none d-md-inline">Delete</span></button>
          </div>
        </td>
      </tr>
      <?php $delay += 0.1; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editUserForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit user</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_user_id" name="id">
          <input type="hidden" name="edit_user" value="1">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" id="edit_username" name="username" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" id="edit_email" name="email" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select class="form-select" id="edit_role" name="role" required>
              <option value="utilisateur">User</option>
              <option value="admin">Administrator</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function() {
  // Afficher la modal avec les données
  $('.edit-user').on('click', function() {
    $('#edit_user_id').val($(this).data('id'));
    $('#edit_username').val($(this).data('username'));
    $('#edit_email').val($(this).data('email'));
    $('#edit_role').val($(this).data('role'));
    $('#editUserModal').modal('show');
  });

  // Modifier utilisateur
  $('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    $.post('edit_user.php', $(this).serialize(), function(response) {
      if (response.includes("success")) {
        showAlert("Utilisateur modifié avec succès", "success");
        $('#editUserModal').modal('hide');
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert("Erreur lors de la modification", "danger");
      }
    });
  });

  // Supprimer utilisateur
  $('.delete-user').on('click', function() {
    const id = $(this).data('id');
    if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
      $.post('delete_user.php', { id }, function(response) {
        if (response.includes("success")) {
          showAlert("Utilisateur supprimé", "success");
          setTimeout(() => location.reload(), 1000);
        } else {
          showAlert("Erreur lors de la suppression", "danger");
        }
      });
    }
  });

  function showAlert(msg, type) {
    $('#alertMessage').html(`<div class="alert alert-${type}">${msg}</div>`);
    setTimeout(() => $('#alertMessage').html(''), 4000);
  }
});
</script>

</body>
</html>
