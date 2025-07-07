<?php
// ===== dashboard.php =====
session_start();
require_once('dbConfig.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
$admin_name = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body { background-color: #1e1e1e; color: white; margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .sidebar { width: 250px; background: #2b2b2b; height: 100vh; position: fixed; left: 0; top: 0; padding: 30px 20px; box-shadow: 3px 0 15px rgba(0, 0, 0, 0.3); }
    .sidebar h3 { color: #ff7f0e; text-align: center; margin-bottom: 30px; }
    .sidebar a { display: flex; align-items: center; gap: 10px; color: white; text-decoration: none; padding: 12px; border-radius: 10px; margin-bottom: 10px; transition: all 0.3s ease; }
    .sidebar a:hover, .sidebar a.active { background-color: #ff7f0e; color: #1e1e1e; transform: translateX(5px); }
    .header { margin-left: 250px; background-color: #2b2b2b; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }
    .main-content { margin-left: 250px; padding: 40px; animation: fadeIn 0.5s ease-in-out; min-height: calc(100vh - 70px); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  </style>
</head>
<body>
  <div class="header">
    <div><i class="fas fa-bell text-white me-4 fs-5"></i></div>
    <div><span><i class="fas fa-user-circle me-2"></i> <?= htmlspecialchars($admin_name) ?></span></div>
  </div>

  <div class="sidebar">
    <h3><i class="fas fa-cogs me-2"></i>Admin Panel</h3>
    <a href="#" class="nav-link active" data-page="home"><i class="fas fa-home"></i> Dashboard</a>
    <a href="#" class="nav-link" data-page="users"><i class="fas fa-users"></i> Manage users</a>
    <a href="#" class="nav-link" data-page="rooms"><i class="fas fa-door-open"></i> Manage rooms</a>
    <a href="#" class="nav-link" data-page="reservation"><i class="fas fa-calendar-check"></i> Reservations</a>
    <a href="#" class="nav-link" data-page="equipements"><i class="fas fa-tools"></i>Equipments</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main-content" id="main-content">
    <?php include('admin_pages/home.php'); ?>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function loadPage(page) {
      fetch('admin_pages/' + page + '.php')
        .then(res => res.text())
        .then(html => {
          document.getElementById('main-content').innerHTML = html;
          if (page === 'users') initUserPage();
        });
    }

    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        const page = this.getAttribute('data-page');
        loadPage(page);
      });
    });

    window.addEventListener('DOMContentLoaded', () => {
      loadPage("home");
    });

    function initUserPage() {
      $(document).on('click', '.edit-user', function() {
        $('#edit_user_id').val($(this).data('id'));
        $('#edit_username').val($(this).data('username'));
        $('#edit_email').val($(this).data('email'));
        $('#edit_role').val($(this).data('role'));
        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
      });

      $(document).on('submit', '#editUserForm', function(e) {
        e.preventDefault();
        $.post('edit_user.php', $(this).serialize(), function(response) {
          if (response.trim() === 'success') {
            alert("Utilisateur modifié");
            $('#editUserModal').modal('hide');
            setTimeout(() => loadPage('users'), 800);
          } else {
            alert("Erreur: " + response);
          }
        });
      });

      $(document).on('click', '.delete-user', function() {
        const id = $(this).data('id');
        if (confirm("Supprimer cet utilisateur ?")) {
          $.post('delete_user.php', { id: id }, function(response) {
            if (response.trim() === 'success') {
              alert("Utilisateur supprimé");
              setTimeout(() => loadPage('users'), 800);
            } else {
              alert("Erreur: " + response);
            }
          });
        }
      });
    }
  </script>
</body>
</html>


