<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require_once "conexion.php";

// Forzar mysqli a reportar errores (útil en desarrollo; puedes quitarlo en prod)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // NUEVA consulta: tolerante a datos faltantes y compatible con ambos esquemas
    // - LEFT JOIN a clientes2 para que NO se pierdan citas si aún no existe el cliente en esa tabla
    // - LEFT JOIN a usuarios para soportar citas antiguas con id_usuario
    // - COALESCE para elegir el nombre disponible en orden de preferencia
    $sql = "
        SELECT 
            c.id,
            COALESCE(cl.nombre, u.usuario, CONCAT('Cliente #', c.cliente_id)) AS nombre,
            c.fecha,
            c.hora,
            c.motivo,
            c.estado
        FROM citas AS c
        LEFT JOIN clientes2 AS cl ON c.cliente_id = cl.id
        LEFT JOIN usuarios  AS u  ON c.id_usuario = u.id
        ORDER BY c.fecha ASC, c.hora ASC
    ";

    $resultado = $conexion->query($sql);
} catch (Throwable $e) {
    // Mensaje claro si hay un error de SQL
    http_response_code(500);
    echo "<pre style='color:#ff6b6b;background:#1b1b1b;padding:16px;border-radius:8px'>";
    echo "❌ Error al consultar las citas:\n";
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo "\n\nSQL ejecutado:\n" . htmlspecialchars($sql, ENT_QUOTES, 'UTF-8');
    echo "</pre>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas Programadas | Katana Gym</title>
  <link rel="stylesheet" href="../estilo-admin.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #fff;
      text-align: center;
      padding: 40px;
    }
    h1 {
      color: #ff8800ff;
      margin-bottom: 30px;
    }
    table {
      margin: auto;
      width: 90%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(255, 153, 0, 0.2);
    }
    th, td {
      padding: 12px;
      color: #fff;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    th {
      background-color: #ff8800ff;
      color: #000;
    }
    tr:nth-child(even) {
      background-color: rgba(255, 255, 255, 0.02);
    }
    .empty {
      padding: 18px;
      color: #bbb;
      text-align: center;
    }
    a.btn {
      display: inline-block;
      margin-top: 30px;
      padding: 10px 25px;
      background: #ff8800ff;
      color: #000;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    a.btn:hover {
      filter: brightness(1.05);
    }
  </style>
</head>
<body>

  <h1>📅 Citas Programadas</h1>

  <table>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Motivo</th>
      <th>Estado</th>
    </tr>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$fila['id'] ?></td>
          <td><?= htmlspecialchars($fila['nombre'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($fila['fecha'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($fila['hora'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($fila['motivo'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($fila['estado'] ?? 'Pendiente', ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td class="empty" colspan="6">No hay citas registradas.</td>
      </tr>
    <?php endif; ?>
  </table>

  <a href="dashboard.php" class="btn">← Volver al Panel</a>

</body>
</html>
