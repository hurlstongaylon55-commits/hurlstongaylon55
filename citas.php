<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

/* Asegurar columna `estado` en la tabla citas */
$conexion->query("
  ALTER TABLE citas
  ADD COLUMN IF NOT EXISTS estado
  ENUM('programada','realizada','perdida','cancelada','reprogramada')
  NOT NULL DEFAULT 'programada'
  AFTER motivo
");

/* Actualizar cita (editar) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id      = (int)$_POST['update_id'];
    $fecha   = $_POST['fecha'] ?? '';
    $hora    = $_POST['hora'] ?? '';
    $motivo  = trim($_POST['motivo'] ?? '');
    $estado  = $_POST['estado'] ?? 'programada';

    // Validar estado permitido
    $permitidos = ['programada','realizada','perdida','cancelada','reprogramada'];
    if (!in_array($estado, $permitidos, true)) $estado = 'programada';

    $stmt = $conexion->prepare("UPDATE citas SET fecha = ?, hora = ?, motivo = ?, estado = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $fecha, $hora, $motivo, $estado, $id);

    if ($stmt->execute()) {
        header("Location: citas.php?ok=1");
        exit();
    } else {
        $error = "No se pudo actualizar la cita: " . $conexion->error;
    }
}

/* Listado */
$query = "SELECT c.id, cl.nombre AS cliente, c.fecha, c.hora, c.motivo, c.estado
          FROM citas c
          INNER JOIN clientes2 cl ON c.cliente_id = cl.id
          ORDER BY c.fecha, c.hora";
$resultado = $conexion->query($query);
if (!$resultado) {
  die('Error en la consulta: ' . $conexion->error);
}

function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas - Admin</title>
  <link rel="stylesheet" href="estilo-admin.css">
  <style>
    body { color:#fff; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    h1 { text-align:center; }
    .top-actions { text-align:center; }
    a.btn {
      display: inline-block;
      margin: 20px auto;
      padding: 10px 25px;
      background: #ff8800ff;
      color: #000;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: all 0.3s ease;
    }
    a.btn:hover { background:#ff9900; }
    table {
      margin: 10px auto 30px;
      border-collapse: collapse;
      width: 95%;
      max-width: 1100px;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(6px);
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(255, 174, 0, 0.2);
      overflow: hidden;
    }
    th, td { padding: 12px; text-align: center; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.06); }
    th { background-color: #ff8800ff; color: #000; }
    tr:nth-child(even) { background-color: rgba(255, 255, 255, 0.02); }
    .row-form input[type="date"],
    .row-form input[type="time"],
    .row-form input[type="text"],
    .row-form select {
      width: 100%;
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      background: rgba(0,0,0,0.2);
      color: #fff;
    }
    .row-actions { display:flex; gap:8px; justify-content:center; }
    .btn-small {
      background:#2563eb; color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;
    }
    .status-chip {
      display:inline-block; padding:4px 8px; border-radius:999px; font-weight:600;
      background:#222; color:#ddd;
    }
    .status-realizada { background:#16a34a; color:#fff; }
    .status-perdida { background:#b91c1c; color:#fff; }
    .status-cancelada { background:#6b7280; color:#fff; }
    .status-reprogramada { background:#0ea5e9; color:#000; }
    .status-programada { background:#f59e0b; color:#000; }
    .msg { text-align:center; margin:10px auto; }
    .ok { color:#22c55e; }
    .err { color:#ef4444; }
  </style>
</head>
<body>
  <h1>📅 Citas Agendadas</h1>

  <div class="top-actions">
    <a href="nueva_cita.php" class="btn">➕ Agregar Nueva Cita</a>
  </div>

  <?php if (isset($_GET['ok'])): ?>
    <div class="msg ok">✅ Cita actualizada correctamente.</div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="msg err"><?= h($error) ?></div>
  <?php endif; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Motivo</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>

    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <form method="POST" class="row-form">
            <td><?= (int)$fila['id']; ?></td>
            <td><?= h($fila['cliente']); ?></td>
            <td><input type="date" name="fecha" value="<?= h($fila['fecha']); ?>" required></td>
            <td><input type="time" name="hora" value="<?= h($fila['hora']); ?>" required></td>
            <td><input type="text" name="motivo" value="<?= h($fila['motivo']); ?>" required></td>
            <td>
              <?php
                $estado = $fila['estado'] ?: 'programada';
                $opts = ['programada'=>'Programada','realizada'=>'Realizada','perdida'=>'Perdida','cancelada'=>'Cancelada','reprogramada'=>'Reprogramada'];
              ?>
              <select name="estado" required>
                <?php foreach ($opts as $val=>$label): ?>
                  <option value="<?= $val ?>" <?= $estado === $val ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
              <div style="margin-top:6px">
                <span class="status-chip status-<?= h($estado) ?>"><?= ucfirst($estado) ?></span>
              </div>
            </td>
            <td class="row-actions">
              <input type="hidden" name="update_id" value="<?= (int)$fila['id']; ?>">
              <button type="submit" class="btn-small">💾 Guardar</button>
            </td>
          </form>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" style="color:#999;">No hay citas registradas.</td></tr>
    <?php endif; ?>
  </table>

  <div class="top-actions">
    <a href="dashboard.php" class="btn">← Volver al Panel</a>
  </div>
</body>
</html>
