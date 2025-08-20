<?php
session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit();
}

require_once "conexion.php"; // Debe crear $conexion = new mysqli(...)

// Fuerza UTF-8 y errores claros
if (method_exists($conexion, 'set_charset')) { $conexion->set_charset('utf8mb4'); }
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Evita que el navegador te sirva una versión cacheada (lista de clientes “vieja”)
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

$error = "";

// --- Guardar cita ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Acepta compatibilidad: si llega id_usuario, úsalo como cliente_id
    $cliente_id = isset($_POST['cliente_id']) ? $_POST['cliente_id'] : ($_POST['id_usuario'] ?? '');

    $fecha  = $_POST['fecha']  ?? '';
    $hora   = $_POST['hora']   ?? '';
    $motivo = trim($_POST['motivo'] ?? '');

    if ($cliente_id === '' || $fecha === '' || $hora === '' || $motivo === '') {
        $error = "Completa todos los campos.";
    } else {
        $cliente_id = (int)$cliente_id;

        // Verifica que el cliente exista en clientes2
        $check = $conexion->prepare("SELECT 1 FROM clientes2 WHERE id = ?");
        $check->bind_param("i", $cliente_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $error = "El cliente seleccionado no existe en clientes2.";
        } else {
            // Inserta en citas usando la FK cliente_id -> clientes2.id
            $stmt = $conexion->prepare(
                "INSERT INTO citas (cliente_id, fecha, hora, motivo, estado) VALUES (?, ?, ?, ?, 'Pendiente')"
            );
            $stmt->bind_param("isss", $cliente_id, $fecha, $hora, $motivo);
            $stmt->execute();

            // Redirige a la vista que estás usando (citas2.php en tu caso)
            header("Location: citas2.php");
            exit();
        }
        $check->close();
    }
}

// --- Obtener clientes para el <select> desde clientes2 (no 'usuarios') ---
$clientes = $conexion->query("
    SELECT id, nombre
    FROM clientes2
    WHERE nombre IS NOT NULL AND TRIM(nombre) <> ''
    ORDER BY id DESC        -- muestra primero los más recientes
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Cita</title>
  <link rel="stylesheet" href="estilo-admin.css">
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; background:#111827; color:#e5e7eb; }
    .wrap { max-width: 720px; margin: 32px auto; padding: 16px; background:#0b1220; border-radius:12px; }
    .btn { background:#ff8800; color:#000; border:none; padding:10px 16px; border-radius:8px; cursor:pointer; font-weight:600; }
    .btn.secondary { background:#1f2937; color:#e5e7eb; }
    .field { margin-bottom:16px; }
    label { display:block; font-weight:600; margin-bottom:6px; }
    input, select { width:100%; padding:10px; border:1px solid #374151; border-radius:8px; background:#0f172a; color:#e5e7eb; }
    .error { background:#7f1d1d; color:#fecaca; padding:10px; border-radius:8px; margin-bottom:16px; }
    h1 { margin-bottom:16px; color:#ff8800; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>➕ Agregar Nueva Cita</h1>

    <?php if (!empty($error)): ?>
      <div class="error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="nueva_cita.php" autocomplete="off">
      <div class="field">
        <label for="cliente_id">Cliente</label>
        <!-- OJO: ahora el name/id son cliente_id, alineado con la columna de la tabla 'citas' -->
        <select name="cliente_id" id="cliente_id" required>
          <option value="" disabled selected>— Selecciona un cliente —</option>
          <?php while ($c = $clientes->fetch_assoc()): ?>
            <option value="<?= (int)$c['id'] ?>"><?= h($c['nombre']) ?></option>
          <?php endwhile; ?>
        </select>
        <small style="color:#9ca3af">Si acabas de crear un cliente en otra pestaña, recarga esta página para verlo.</small>
      </div>

      <div class="field">
        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" name="fecha" required>
      </div>

      <div class="field">
        <label for="hora">Hora</label>
        <input type="time" id="hora" name="hora" required>
      </div>

      <div class="field">
        <label for="motivo">Motivo</label>
        <input type="text" id="motivo" name="motivo" placeholder="Ej. Evaluación inicial" required>
      </div>

      <button type="submit" class="btn">Guardar</button>
      <a href="citas2.php" class="btn secondary" style="margin-left:8px;">← Volver a Citas</a>
    </form>
  </div>
</body>
</html>


