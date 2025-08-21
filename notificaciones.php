<?php
session_start();

// Verificar que el usuario esté logueado y sea admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once("conexion.php");

// Borrar notificaciones con más de 14 días
$conexion->query("DELETE FROM notificaciones WHERE fecha < NOW() - INTERVAL 14 DAY");


// Consulta de notificaciones ordenadas por fecha descendente
$notis = $conexion->query("SELECT * FROM notificaciones ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Notificaciones | Katana Gym</title>
  <link rel="stylesheet" href="estilo-admin.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1a1a1a, #111);
      color: white;
      padding: 40px;
      text-align: center;
    }

    h1 {
      color: #ff8800;
      margin-bottom: 30px;
    }

    table {
      width: 90%;
      margin: auto;
      border-collapse: collapse;
      background: #222;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(255, 136, 0, 0.2);
      overflow: hidden;
    }

    th, td {
      padding: 15px;
      border-bottom: 1px solid #333;
    }

    th {
      background: #ff8800;
      color: #000;
    }

    tr:hover {
      background-color: #2a2a2a;
    }

    a {
      color: #ff8800;
      font-weight: bold;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h1>📬 Notificaciones de Inscripción</h1>

  <?php if ($notis->num_rows > 0): ?>
    <table>
      <tr>
        <th>Usuario</th>
        <th>Mensaje</th>
        <th>Fecha</th>
      </tr>
      <?php while ($n = $notis->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($n['usuario']) ?></td>
        <td><?= htmlspecialchars($n['mensaje']) ?></td>
        <td><?= htmlspecialchars($n['fecha']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No hay notificaciones registradas.</p>
  <?php endif; ?>

  <br><br>
  <a href="dashboard.php">← Volver al panel</a>

</body>
</html>
