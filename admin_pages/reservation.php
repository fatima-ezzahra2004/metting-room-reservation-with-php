<?php
require_once('../dbConfig.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Requête SQL pour récupérer les réservations avec salle et utilisateur
$reservations = mysqli_query($connt, "
    SELECT res.id, res.title, res.start, res.end, 
           m.username, m.email, 
           r.name AS room_name
    FROM reservs res
    JOIN members m ON res.member_id = m.id
    JOIN rooms r ON res.room_id = r.id
    ORDER BY res.start DESC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des réservations</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
  </style>
</head>
<body>

<h2>List of reservations</h2>

<div class="table-responsive">
  <table class="table table-dark table-hover align-middle shadow-sm" style="border-radius: 10px; overflow: hidden;">
    <thead class="bg-warning text-dark text-uppercase">
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Email</th>
        <th>Title</th>
        <th>Room</th>
        <th>Start date</th>
        <th>End date</th>
      </tr>
    </thead>
    <tbody>
      <?php $delay = 0.1; ?>
      <?php while ($row = mysqli_fetch_assoc($reservations)) : ?>
      <tr class="fade-item" style="animation-delay: <?= $delay ?>s;">
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['room_name']) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($row['start'])) ?></td>
        <td><?= date('d/m/Y H:i', strtotime($row['end'])) ?></td>
      </tr>
      <?php $delay += 0.1; ?>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
