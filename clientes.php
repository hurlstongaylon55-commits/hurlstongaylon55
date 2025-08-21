<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

// Cambiar el estado si se envían parámetros y validar entrada
if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id = intval($_GET['id']);
    $estado = intval($_GET['estado']);

    // Preparar statement para mayor seguridad
    $stmt = $conexion->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
    $stmt->bind_param("ii", $estado, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: clientes.php");
    exit();
}

// Traer lista de clientes con estado
$resultado = $conexion->query("SELECT id, usuario, rol, estado FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clientes - Admin</title>
  <link rel="stylesheet" href="estilo-admin.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #fff;
      text-align: center;
      padding: 50px;
    }
    h1 {
      color: #ff8800ff;
      margin-bottom: 30px;
    }
    table {
      margin: auto;
      border-collapse: collapse;
      width: 80%;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(6px);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(255, 174, 0, 0.2);
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
    a.btn {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 25px;
      background: #ff8800ff;
      color: #000;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    a.btn:hover {
      background: #ffaa00;
    }
    .estado-btn {
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      user-select: none;
    }
    .activado {
      background-color: #00ff88;
      color: #000;
    }
    .desactivado {
      background-color: #ff4444;
      color: #fff;
    }
  </style>
</head>
<body>

  <h1>👥 Lista de Clientes Registrados</h1>

  <table border="1">
    <tr>
      <th>ID</th>
      <th>Usuario</th>
      <th>Rol</th>
      <th>Estado</th>
      <th>Acción</th>
    </tr>
    <?php while($fila = $resultado->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($fila['id']); ?></td>
        <td><?php echo htmlspecialchars($fila['usuario']); ?></td>
        <td><?php echo htmlspecialchars($fila['rol']); ?></td>
        <td><?php echo $fila['estado'] ? '✅ Activado' : '❌ Desactivado'; ?></td>
        <td>
          <a 
            href="clientes.php?id=<?php echo $fila['id']; ?>&estado=<?php echo $fila['estado'] ? 0 : 1; ?>" 
            class="estado-btn <?php echo $fila['estado'] ? 'desactivado' : 'activado'; ?>">
            <?php echo $fila['estado'] ? 'Desactivar' : 'Activar'; ?>
          </a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <a href="nuevo_cliente.php" class="btn">➕ Agregar Nuevo Cliente</a>
  <a href="dashboard.php" class="btn">← Volver al Panel</a>

</body>
</html>

