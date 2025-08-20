<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: deudas.php");
    exit();
}

$id = $_GET['id'];

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];
    $pagado = isset($_POST['pagado']) ? 1 : 0;

    $conexion->query("UPDATE deudas SET descripcion='$descripcion', monto='$monto', pagado='$pagado' WHERE id=$id");
    header("Location: deudas.php");
    exit();
}

$deuda = $conexion->query("SELECT * FROM deudas WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Deuda</title>
  <link rel="stylesheet" href="estilo-admin.css">
</head>
<body>
  <h1>✏️ Editar Deuda</h1>
  <form method="POST" style="text-align:center;">
    <label>Descripción:
      <input type="text" name="descripcion" value="<?= $deuda['descripcion'] ?>" required>
    </label><br><br>

    <label>Monto (L):
      <input type="number" step="0.01" name="monto" value="<?= $deuda['monto'] ?>" required>
    </label><br><br>

    <label>
      <input type="checkbox" name="pagado" <?= $deuda['pagado'] ? 'checked' : '' ?>> Marcar como pagado
    </label><br><br>

    <button type="submit" class="btn">Guardar Cambios</button>
  </form>
  <br>
  <a href="deudas.php" class="btn">← Volver a Deudas</a>
</body>
</html>
