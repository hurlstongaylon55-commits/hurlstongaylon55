<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];

    $conexion->query("INSERT INTO deudas (id_usuario, descripcion, monto) 
                      VALUES ('$id_usuario', '$descripcion', '$monto')");
    header("Location: deudas.php");
    exit();
}

$usuarios = $conexion->query("SELECT * FROM usuarios WHERE rol='cliente'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Deuda</title>
  <link rel="stylesheet" href="estilo-admin.css">
</head>
<body>
  <h1>➕ Registrar Nueva Deuda</h1>
  <form method="POST" style="text-align:center;">
    <label>Cliente:
      <select name="id_usuario" required>
        <?php while($u = $usuarios->fetch_assoc()): ?>
          <option value="<?= $u['id'] ?>"><?= $u['usuario'] ?></option>
        <?php endwhile; ?>
      </select>
    </label><br><br>

    <label>Descripción:
      <input type="text" name="descripcion" required>
    </label><br><br>

    <label>Monto (L):
      <input type="number" step="0.01" name="monto" required>
    </label><br><br>

    <button type="submit" class="btn">Guardar</button>
  </form>
  <br>
  <a href="deudas.php" class="btn">← Volver a Deudas</a>
</body>
</html>
