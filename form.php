<?php
session_start();
include('dbConfig.php');
require_once('libs/phpqrcode/qrlib.php'); // Assure-toi que le chemin est correct

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['room_id']) || !is_numeric($_GET['room_id'])) {
    die("Salle invalide.");
}

$room_id = (int)$_GET['room_id'];

// Récupérer les infos de la salle
$stmt = $connt->prepare("SELECT name FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$stmt->bind_result($room_name);
if (!$stmt->fetch()) {
    die("Salle introuvable.");
}
$stmt->close();

$message = "";
$qrImagePath = "";

if (isset($_POST['save-event'])) {
    $member_id = $_SESSION['id'];
    $title = $_POST['title'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt_check = $connt->prepare("SELECT id FROM reservs WHERE room_id = ? AND (
        (start <= ? AND end > ?) OR 
        (start < ? AND end >= ?) OR 
        (start >= ? AND end <= ?)
    )");
    $stmt_check->bind_param("issssss", $room_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $message = '<div class="alert alert-warning text-center">Cette plage horaire est déjà réservée pour cette salle.</div>';
    } else {
        $stmt_insert = $connt->prepare("INSERT INTO reservs (title, start, end, member_id, room_id) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("sssii", $title, $start_date, $end_date, $member_id, $room_id);
        if ($stmt_insert->execute()) {
            $reservation_id = $stmt_insert->insert_id;

            // Générer le contenu du QR code
            $qrData = "Réservation ID: $reservation_id\nSalle: $room_name\nTitre: $title\nDébut: $start_date\nFin: $end_date";

            // Dossier pour stocker les QR codes
            $qrDir = 'qr_codes/';
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            // Chemin du fichier QR
            $qrImagePath = $qrDir . 'reservation_' . $reservation_id . '.png';

            // Génération du QR code
            QRcode::png($qrData, $qrImagePath, QR_ECLEVEL_H, 6);

            $message = '<div class="alert alert-success text-center">Réservation réussie. Le QR code est généré ci-dessous.</div>';
        } else {
            $message = '<div class="alert alert-danger text-center">Erreur lors de la réservation.</div>';
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver une salle</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(120deg, #1e1e1e, #2b2b2b);
            color: white;
            font-family: 'Arial', sans-serif;
            padding: 40px 0;
        }

        h3 {
            font-family: 'Playfair Display', serif;
            color: #ff7f0e;
            text-align: center;
            font-size: 22px;
            position: relative;
            margin-bottom: 40px;
        }

        h3::before, h3::after {
            content: "";
            position: absolute;
            bottom: -10px;
            height: 2px;
            background: #ff7f0e;
            width: 60px;
        }

        h3::before {
            left: 50%;
            transform: translateX(-60px);
        }

        h3::after {
            right: 50%;
            transform: translateX(60px);
        }

        .container {
            background-color: #333;
            padding: 35px;
            border-radius: 15px;
            max-width: 700px;
            box-shadow: 0 0 25px rgba(255, 127, 14, 0.4);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 2px #ff7f0e;
        }

        label {
            font-weight: bold;
            color: #ffa94d;
        }

        .btn-success {
            background-color: #ff7f0e;
            border: none;
            padding: 12px 25px;
            font-weight: bold;
            border-radius: 30px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-success:hover {
            background-color: #ffa94d;
            transform: scale(1.05);
        }

        .btn-back {
            color: #ff7f0e;
            font-weight: bold;
            margin-bottom: 25px;
            display: inline-block;
            transition: color 0.3s ease;
        }

        .btn-back:hover {
            color: #ffa94d;
        }

        .modal-content {
            background-color: #fff;
            color: black;
            border-radius: 15px;
        }

        .alert {
            font-size: 15px;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <a class="btn-back" href="liste_salles.php">← Back to the list of rooms</a>

        <h3>Book the room: <?= htmlspecialchars($room_name) ?></h3>

        <?= $message ?>

        <form method="post">
            <div class="form-group">
                <label for="title">Meeting title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Ex : Réunion projet Dev" required>
            </div>

            <div class="form-group">
                <label for="start_date">Start date</label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="end_date">End date</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" name="save-event" class="btn btn-success">Book now</button>
            </div>
        </form>
        <?php if (!empty($qrImagePath)) : ?>
    <div class="text-center mt-4">
        <h5>QR Code of your reservation</h5>
        <img src="<?= htmlspecialchars($qrImagePath) ?>" alt="QR Code" style="width: 200px; height: auto;">
        <br><br>
        <a href="<?= htmlspecialchars($qrImagePath) ?>" download class="btn btn-success"> Download the QR Code</a>
    </div>
<?php endif; ?>

    </div>

</body>
</html>
