<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: clientes.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];

    $conexion->query("UPDATE usuarios SET usuario='$usuario', rol='$rol' WHERE id=$id");
    header("Location: clientes.php");
    exit();
}

$cliente = $conexion->query("SELECT * FROM usuarios WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Cliente</title>
  <link rel="stylesheet" href="estilo-admin.css">
</head>
<body>
  <h1>✏️ Editar Cliente</h1>
  <form method="POST" style="text-align:center;">
    <label>Nombre de usuario:
      <input type="text" name="usuario" value="<?= $cliente['usuario'] ?>" required>
    </label><br><br>

    <label>Rol:
      <select name="rol" required>
        <option value="cliente" <?= $cliente['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
        <option value="admin" <?= $cliente['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
      </select>
    </label><br><br>

    <button type="submit" class="btn">Guardar Cambios</button>
  </form>
  <br>
  <a href="clientes.php" class="btn">← Volver a Clientes</a>
</body>
</html>
