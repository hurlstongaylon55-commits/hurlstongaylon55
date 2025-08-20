<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: citas.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $motivo = $_POST['motivo'];

    $conexion->query("UPDATE citas SET id_usuario='$id_usuario', fecha='$fecha', hora='$hora', motivo='$motivo' WHERE id=$id");
    header("Location: citas.php");
    exit();
}

$cita = $conexion->query("SELECT * FROM citas WHERE id=$id")->fetch_assoc();
$usuarios = $conexion->query("SELECT * FROM usuarios WHERE rol='cliente'");

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cita</title>
  <link rel="stylesheet" href="estilo-admin.css">
</head>
<body>
  <h1>✏️ Editar Cita</h1>
  <form method="POST" style="text-align:center;">
    <label>Cliente:
      <select name="id_usuario" required>
        <?php while($u = $usuarios->fetch_assoc()): ?>
          <option value="<?= $u['id'] ?>" <?= $u['id'] == $cita['id_usuario'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['usuario']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </label><br><br>

    <label>Fecha:
      <input type="date" name="fecha" value="<?= $cita['fecha'] ?>" required>
    </label><br><br>

    <label>Hora:
      <input type="time" name="hora" value="<?= $cita['hora'] ?>" required>
    </label><br><br>

    <label>Motivo:
      <input type="text" name="motivo" value="<?= htmlspecialchars($cita['motivo']) ?>" required>
    </label><br><br>

    <button type="submit" class="btn">Guardar Cambios</button>
  </form>
  <br>
  <a href="citas.php" class="btn">← Volver a Citas</a>
</body>
</html>
