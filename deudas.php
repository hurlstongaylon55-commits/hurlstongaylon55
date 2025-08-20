<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include("conexion.php");

// Marcar como pagado
if (isset($_GET['pagar'])) {
    $id = intval($_GET['pagar']);
    $conexion->query("UPDATE deudas SET pagado = 1 WHERE id = $id");
    header("Location: deudas.php");
    exit();
}

// Eliminar deuda
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM deudas WHERE id = $id");
    header("Location: deudas.php");
    exit();
}

// Consulta para traer deudas con usuario relacionado
$sql = "SELECT deudas.id, usuarios.usuario, deudas.descripcion, deudas.monto, COALESCE(deudas.pagado, 0) as pagado
FROM deudas
JOIN usuarios ON deudas.id_usuario = usuarios.id";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Deudas - Admin</title>
  <link rel="stylesheet" href="estilo-admin.css">
  <style>
    table {
      margin: auto;
      border-collapse: collapse;
      width: 90%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
      backdrop-filter: blur(6px);
      overflow: hidden;
      box-shadow: 0 0 20px rgba(255, 187, 0, 0.2);
    }

    th, td {
      padding: 12px;
      text-align: center;
      color: #fff;
    }

    th {
      background-color: #ff8800ff;
      color: #000;
    }

    tr:nth-child(even) {
      background-color: rgba(255, 255, 255, 0.02);
    }

    .pagado { color: #00cc44; font-weight: bold; }
    .pendiente { color: #ff3b3b; font-weight: bold; }

    a.btn, a.op {
      display: inline-block;
      margin: 5px;
      padding: 6px 12px;
      background: #ff8800ff;
      color: #000;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: all 0.3s ease;
      user-select: none;
    }

    a.btn:hover, a.op:hover {
      background: #ffaa00;
    }
  </style>
</head>
<body>
  <h1>💰 Deudas de Clientes</h1>

  <a href="nueva_deuda.php" class="btn">➕ Agregar Nueva Deuda</a>

  <table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Descripción</th>
        <th>Monto</th>
        <th>Estado</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($fila = $resultado->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($fila['id']) ?></td>
        <td><?= htmlspecialchars($fila['usuario']) ?></td>
        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
        <td>L. <?= number_format($fila['monto'], 2) ?></td>
        <td class="<?= intval($fila['pagado']) ? 'pagado' : 'pendiente' ?>">
          <?= intval($fila['pagado']) ? 'Pagado' : 'Pendiente' ?>
        </td>
        <td>
          <?php if (!intval($fila['pagado'])): ?>
            <a href="deudas.php?pagar=<?= $fila['id'] ?>" class="op" onclick="return confirm('¿Marcar esta deuda como pagada?')">✔️ Marcar Pagado</a>
          <?php endif; ?>
          <a href="editar_deuda.php?id=<?= $fila['id'] ?>" class="op">✏️ Editar</a>
          <a href="deudas.php?eliminar=<?= $fila['id'] ?>" class="op" onclick="return confirm('¿Eliminar esta deuda?')">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="btn">← Volver al Panel</a>
</body>
</html>
