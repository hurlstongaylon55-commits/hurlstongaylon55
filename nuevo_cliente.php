<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = md5($_POST['contrasena']);  // Recuerda usar hash más seguro en producción
    $rol = $_POST['rol'];

    // Verificar si usuario ya existe
    $check = $conexion->query("SELECT * FROM usuarios WHERE usuario='$usuario'");
    if ($check->num_rows > 0) {
        $error = "El usuario ya existe.";
    } else {
        $conexion->query("INSERT INTO usuarios (usuario, contrasena, rol) VALUES ('$usuario', '$contrasena', '$rol')");
        header("Location: clientes.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nuevo Cliente</title>
  <link rel="stylesheet" href="estilo-admin.css">
</head>
<body>
  <h1>➕ Agregar Nuevo Cliente</h1>
  <?php if (isset($error)): ?>
    <p style="color:red; text-align:center;"><?= $error ?></p>
  <?php endif; ?>
  <form method="POST" style="text-align:center;">
    <label>Nombre de usuario:
      <input type="text" name="usuario" required>
    </label><br><br>

    <label>Contraseña:
      <input type="password" name="contrasena" required>
    </label><br><br>

    <label>Rol:
      <select name="rol" required>
        <option value="cliente">Cliente</option>
        <option value="admin">Administrador</option>
      </select>
    </label><br><br>

    <button type="submit" class="btn">Guardar</button>
  </form>
  <br>
  <a href="clientes.php" class="btn">← Volver a Clientes</a>
</body>
</html>
