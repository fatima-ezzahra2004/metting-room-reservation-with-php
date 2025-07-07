<?php
require_once(__DIR__ . '/../dbConfig.php');

// Nombre total d'utilisateurs
$resUsers = mysqli_query($connt, "SELECT COUNT(*) AS total_users FROM members");
$totalUsers = mysqli_fetch_assoc($resUsers)['total_users'] ?? 0;

// Nombre total de salles
$resRooms = mysqli_query($connt, "SELECT COUNT(*) AS total_rooms FROM rooms");
$totalRooms = mysqli_fetch_assoc($resRooms)['total_rooms'] ?? 0;

// Nombre total de réservations
$resReservations = mysqli_query($connt, "SELECT COUNT(*) AS total_reservations FROM reservs");
$totalReservations = mysqli_fetch_assoc($resReservations)['total_reservations'] ?? 0;

// Données pour le graphique : nombre de réservations par salle
$resByRoom = mysqli_query($connt, "
    SELECT r.name, COUNT(res.id) AS nb_reservations
    FROM rooms r
    LEFT JOIN reservs res ON r.id = res.room_id
    GROUP BY r.id, r.name
");

$chartLabels = [];
$chartData = [];

while ($row = mysqli_fetch_assoc($resByRoom)) {
    $chartLabels[] = $row['name'];
    $chartData[] = (int)$row['nb_reservations'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    /* Animation d'apparition en fondu et légère translation vers le haut */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    /* Animation d'apparition en fondu simple */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    body {
      background-color: rgb(22, 24, 27);
      padding: 20px;
      color: white;
      font-family: Arial, sans-serif;
    }
    /* Titres */
    h3.text-warning.mb-4 {
      animation: fadeIn 1s ease forwards;
      font-size: 15px;
      color: #ff7f0e;
    }
    /* Cards */
    .card {
      opacity: 0;
      animation: fadeInUp 0.8s ease forwards;
      animation-delay: 0.2s;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background-color: #222 !important;
      color: white;
      border: none;
      border-radius: 10px;
    }
    /* Cascade animation delays */
    .row.text-center .col-md-4:nth-child(1) .card { animation-delay: 0.3s; }
    .row.text-center .col-md-4:nth-child(2) .card { animation-delay: 0.5s; }
    .row.text-center .col-md-4:nth-child(3) .card { animation-delay: 0.7s; }
    /* Hover effet */
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(255, 127, 14, 0.5);
    }
    /* Container graphique */
    .card.bg-dark.p-3 {
      opacity: 0;
      animation: fadeInUp 0.8s ease forwards;
      animation-delay: 1s;
    }
  </style>
</head>
<body>

<h3 class="text-warning mb-4">General statistics</h3>
<div class="row text-center">
  <div class="col-md-4 mb-4">
    <div class="card p-3">
      <h5>Users</h5>
      <p class="fs-2"><?= $totalUsers ?></p>
    </div>
  </div>
  <div class="col-md-4 mb-4">
    <div class="card p-3">
      <h5>Rooms</h5>
      <p class="fs-2"><?= $totalRooms ?></p>
    </div>
  </div>
  <div class="col-md-4 mb-4">
    <div class="card p-3">
      <h5>Reservations</h5>
      <p class="fs-2"><?= $totalReservations ?></p>
    </div>
  </div>
</div>

<div class="card bg-dark p-3">
  <h5 class="text-warning">Reservations by room</h5>
  <canvas id="reservationsChart" style="width:100%;max-width:700px;height:350px;"></canvas>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('reservationsChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($chartLabels) ?>,
      datasets: [{
        label: 'Nombre de réservations',
        data: <?= json_encode($chartData) ?>,
        backgroundColor: 'rgba(255, 127, 14, 0.7)',
        borderColor: 'rgba(255, 127, 14, 1)',
        borderWidth: 1,
        borderRadius: 5
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          precision: 0
        }
      },
      plugins: {
        legend: {
          labels: {
            color: 'white'
          }
        }
      }
    }
  });
</script>
</body>
</html>
