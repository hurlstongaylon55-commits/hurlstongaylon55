<?php $usuario = htmlspecialchars($_SESSION['id_usuario']); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Bienvenida</title>
  <style>
    /* Estilo del mensaje de bienvenida */
    #bienvenida {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #ff8800;
      color: #fff;
      padding: 15px 25px;
      border-radius: 10px;
      font-weight: bold;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      box-shadow: 0 0 10px rgba(255, 136, 0, 0.7);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.5s ease-in-out;
      z-index: 9999;
    }
    #bienvenida.show {
      opacity: 1;
      pointer-events: auto;
    }
  </style>
</head>
<body>
  <div id="bienvenida">Bienvenido <?php echo $usuario; ?> 👋</div>

  <script>
    const bienvenida = document.getElementById('bienvenida');
    // Mostrar mensaje
    bienvenida.classList.add('show');
    // Ocultar mensaje después de 4 segundos
    setTimeout(() => {
      bienvenida.classList.remove('show');
    }, 4000);
  </script>
</body>
</html>
