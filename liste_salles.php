<?php
session_start();
include('dbConfig.php');

// Récupérer toutes les salles avec leurs équipements
$query = "
    SELECT r.id AS room_id, r.name, r.location, r.capacity, e.name AS equipment
    FROM rooms r
    LEFT JOIN equipements e ON r.id = e.room_id
    ORDER BY r.name
";

$result = mysqli_query($connt, $query);

// Organiser les résultats par salle
$salles = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['room_id'];
    if (!isset($salles[$id])) {
        $salles[$id] = [
            'name' => $row['name'],
            'location' => $row['location'],
            'capacity' => $row['capacity'],
            'equipments' => [],
        ];
    }
    if ($row['equipment']) {
        $salles[$id]['equipments'][] = $row['equipment'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>List of available rooms</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #1e1e1e;
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
        }

        h2 {
            color: #ff7f0e;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            text-transform: uppercase;
            position: relative;
            letter-spacing: 1px;
            font-size: 16px;
        }

        h2::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 3px;
            background: #ff7f0e;
            left: 50%;
            transform: translateX(-50%);
            bottom: -10px;
        }

        .table-container {
            background-color: #2e2e2e;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(255, 127, 14, 0.3);
        }

        .table {
            color: white;
            margin: 0;
        }

        .table thead {
            background-color: #ff7f0e;
        }

        .table th {
            color: white;
            text-transform: uppercase;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 127, 14, 0.2);
        }

        .btn-primary {
            background-color: #ff7f0e;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #ffa346;
            transform: scale(1.05);
            color: black;
        }

        ul.equipment-list {
            margin: 0;
            padding-left: 20px;
            font-size: 14px;
            color: #ffa94d;
        }

        .equip-label {
            font-weight: bold;
            color: #ffa94d;
        }

        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 15px;
                border: 1px solid #444;
                border-radius: 10px;
                padding: 15px;
                background-color: #1f1f1f;
            }

            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                font-weight: bold;
                text-transform: uppercase;
                color: #ff7f0e;
            }

            .btn-primary {
                background-color: transparent;
                color: #ff7f0e;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                font-weight: bold;
                transition: background-color 0.3s, color 0.3s;
                font-size: 18px;
                margin-bottom: 20px;
            }

            .btn-primary:hover {
                background-color: #ff7f0e;
                color: white;
            }
        }
    </style>
</head>
<body>

    <h2>List of available rooms</h2>

    <div class="container table-container">
        <a class="btn btn-primary mb-4" href="accueil.php" role="button">Back</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Localisation</th>
                    <th>Ability</th>
                    <th>Equipments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salles as $id => $room) : ?>
                <tr>
                    <td data-label="Nom"><?= htmlspecialchars($room['name']) ?></td>
                    <td data-label="Localisation"><?= htmlspecialchars($room['location']) ?></td>
                    <td data-label="Capacité"><?= (int)$room['capacity'] ?></td>
                    <td data-label="Équipements">
                        <?php if (!empty($room['equipments'])): ?>
                            <ul class="equipment-list">
                                <?php foreach ($room['equipments'] as $equip): ?>
                                    <li><?= htmlspecialchars($equip) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <span style="color: #999;">None</span>
                        <?php endif; ?>
                    </td>
                    <td data-label="Action">
                        <a href="form.php?room_id=<?= $id ?>" class="btn btn-primary btn-sm">to reserve</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
