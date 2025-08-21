<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, correo, telefono) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $correo, $telefono);

    if ($stmt->execute()) {
        header("Location: clientes.php?success=1");
    } else {
        echo "❌ Error al agregar cliente: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Cliente | KATANA GYM</title>
  <link rel="stylesheet" href="../estilo-admin.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #fff;
      text-align: center;
      padding: 60px;
    }

    h1 {
      color: #ff8800ff;
      margin-bottom: 30px;
    }

    form {
      background-color: #1e1e1e;
      padding: 30px;
      max-width: 500px;
      margin: auto;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(255, 166, 0, 0.2);
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: none;
      font-size: 16px;
    }

    button {
      padding: 12px 25px;
      background-color: #ff8800ff;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #ff8800ff;
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
    }

    a.btn:hover {
      background-color: #ff8800ff;
    }
  </style>
</head>
<body>

<h1>➕ Nuevo Cliente</h1>

<form method="POST" action="">
  <input type="text" name="nombre" placeholder="Nombre completo" required>
  <input type="email" name="correo" placeholder="Correo electrónico">
  <input type="text" name="telefono" placeholder="Teléfono">
  <button type="submit">Guardar Cliente</button>
</form>

<a href="clientes.php" class="btn">← Volver a Clientes</a>

</body>
</html>
