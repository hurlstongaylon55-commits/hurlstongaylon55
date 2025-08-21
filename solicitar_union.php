<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_SESSION['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $edad = $_POST['edad'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $mensaje = "$usuario quiere inscribirse al KATANA GYM. 
    \nNombre: $nombre 
    \nApellido: $apellido 
    \nEdad: $edad 
    \nEmail: $email 
    \nTeléfono: $telefono";

    $stmt = $conexion->prepare("INSERT INTO notificaciones (usuario, mensaje) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $mensaje);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('✅ Solicitud enviada con éxito.'); window.location='bienvenida.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitud de Unión | Katana Gym</title>
  <link rel="stylesheet" href="estilo.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-image: url('KATANA.jpg');
      background-size: cover;
      background-position: center;
      color: white;
      text-align: center;
      padding-top: 50px;
    }

    .form-container {
      background: rgba(0, 0, 0, 0.8);
      width: 400px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 255, 204, 0.2);
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      font-size: 16px;
    }

    button {
      background-color: #00ffaa;
      color: #000;
      font-weight: bold;
      padding: 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
      width: 100%;
    }

    button:hover {
      background-color: #00ddaa;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>📬 Solicitud de Unión a Katana Gym</h2>
    <form method="POST" action="">
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="number" name="edad" placeholder="Edad" required>
      <input type="email" name="email" placeholder="Correo Electrónico" required>
      <input type="tel" name="telefono" placeholder="Número de Teléfono" required>
      <button type="submit">Enviar Solicitud</button>
    </form>
  </div>
<div class="link-bienvenida">
      <a href="Bienvenido.php">👋 Ir a página de bienvenida</a>
    </div>
</body>
</html>

