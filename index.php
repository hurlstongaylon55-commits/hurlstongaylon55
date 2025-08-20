<?php
session_start();

// Si ya ha iniciado sesión y es admin, redirige al dashboard
if (isset($_SESSION['usuario']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio | KATANA GYM</title>
  <link rel="stylesheet" href="estilo.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-image: url("fondo.jpg");
      background-size: cover;
      background-position: center;
      color: white;
      text-align: center;
      padding-top: 100px;
      margin: 0;
    }

    .card {
      background: rgba(0, 0, 0, 0.7);
      padding: 40px;
      margin: auto;
      width: 350px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(255, 174, 0, 0.3);
    }

    input, button, .btn {
      display: block;
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      font-size: 16px;
    }

    button {
      background-color: #ff8800ff;
      color: #000;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #ff9900;
    }

    .btn {
      background-color: #00ffaa;
      color: #000;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
      line-height: 1.5em;
    }

    .btn:hover {
      background-color: #00cc88;
    }

    .link-bienvenida {
      margin-top: 15px;
    }

    .link-bienvenida a {
      color: #ff8800ff;
      text-decoration: none;
      font-weight: bold;
    }

    .link-bienvenida a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      margin-top: 15px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="card">
    <h2>Bienvenido a <span style="color:#00ffaa;">KATANA GYM</span></h2>

    <form action="validar.php" method="POST">
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit">Iniciar sesión</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
      <p class="error">⚠️ Usuario o contraseña incorrectos. Inténtalo nuevamente.</p>
    <?php endif; ?>

    <h3>Credenciales de prueba:</h3>
    <p>Usuario: <b>admin</b></p>
    <p>Contraseña: <b>1234</b></p>

  </div>

</body>
</html>
