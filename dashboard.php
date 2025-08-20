<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

$buscar = '';
$clientes = [];
$deudas = [];
$citas = [];

// Variables para mostrar alerta solo una vez
$mostrar_alerta = false;
if (!isset($_SESSION['bienvenida_mostrada'])) {
    $mostrar_alerta = true;
    $_SESSION['bienvenida_mostrada'] = true;
}

if (isset($_GET['buscar'])) {
    $buscar = $conexion->real_escape_string($_GET['buscar']);

    if ($buscar !== '') {
        // Buscar clientes
        $sql_clientes = "SELECT id, usuario, rol, estado FROM usuarios WHERE usuario LIKE '%$buscar%'";
        $res_clientes = $conexion->query($sql_clientes);
        if ($res_clientes) {
            while ($row = $res_clientes->fetch_assoc()) {
                $clientes[] = $row;
            }
        }

        // Buscar deudas (uniendo con usuarios para filtrar por nombre)
        $sql_deudas = "SELECT d.id, d.id_usuario, d.monto, d.descripcion, u.usuario 
               FROM deudas d 
               INNER JOIN usuarios u ON d.id_usuario = u.id 
               WHERE u.usuario LIKE '%$buscar%'";

        $res_deudas = $conexion->query($sql_deudas);
        if ($res_deudas) {
            while ($row = $res_deudas->fetch_assoc()) {
                $deudas[] = $row;
            }
        }

        // Buscar citas (uniendo con usuarios para filtrar por nombre)
        $sql_citas = "SELECT c.id, c.id_usuario, c.fecha, c.motivo, u.usuario 
              FROM citas c 
              INNER JOIN usuarios u ON c.id_usuario = u.id 
              WHERE u.usuario LIKE '%$buscar%'";

        $res_citas = $conexion->query($sql_citas);
        if ($res_citas) {
            while ($row = $res_citas->fetch_assoc()) {
                $citas[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración | Katana Gym</title>
  <link rel="stylesheet" href="estilo-admin.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-image: url('KATANA.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
    }
    .overlay {
      background-color: rgba(0,0,0,0.75);
      min-height: 100vh;
      padding: 50px;
    }
    h1 {
      color: #ff8800ff;
      margin-bottom: 40px;
    }
    .panel {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      max-width: 1000px;
      margin: 0 auto;
    }
    .card {
      background-color: #1b1b1b;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(255,145,0,0.3);
      transition: transform 0.2s;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .card h3 {
      margin-bottom: 15px;
      color: #ff8800ff;
    }
    .card a {
      display: block;
      color: #ff8800ff;
      margin: 5px 0;
      text-decoration: none;
      font-weight: bold;
    }
    .card a:hover {
      color: #ffaa00;
      text-decoration: underline;
    }

    .search-form {
      max-width: 600px;
      margin: 0 auto 40px auto;
      text-align: center;
    }
    .search-input {
      padding: 10px;
      width: 70%;
      font-size: 16px;
      border-radius: 8px 0 0 8px;
      border: none;
      outline: none;
    }
    .search-button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #ff8800;
      color: black;
      border: none;
      border-radius: 0 8px 8px 0;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .search-button:hover {
      background-color: #ffaa00;
    }
    table.search-results {
      margin: 0 auto 40px auto;
      border-collapse: collapse;
      width: 90%;
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(6px);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(255,174,0,0.2);
      color: white;
    }
    table.search-results th,
    table.search-results td {
      padding: 12px;
      text-align: center;
      color: white;
      border-bottom: 1px solid #ff8800aa;
    }
    table.search-results th {
      background-color: #ff8800ff;
      color: black;
    }
    table.search-results tr:nth-child(even) {
      background-color: rgba(255,255,255,0.1);
    }
  </style>
</head>
<body>
  <?php if ($mostrar_alerta): ?>
    <script>
      alert("Bienvenido <?php echo addslashes(htmlspecialchars($_SESSION['usuario'])); ?>");
    </script>
  <?php endif; ?>

  <div class="overlay">
    <h1>Panel de Control – KATANA GYM ⚔️</h1>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="dashboard.php" class="search-form" autocomplete="off">
      <input type="text" name="buscar" class="search-input" placeholder="Buscar clientes, deudas y citas" value="<?php echo htmlspecialchars($buscar); ?>">
      <button type="submit" class="search-button">Buscar</button>
    </form>

    <?php if ($buscar !== ''): ?>
      <!-- Resultados Clientes -->
      <h2 style="text-align:center; color:#ff8800;">Clientes encontrados</h2>
      <?php if (count($clientes) > 0): ?>
        <table class="search-results">
          <thead>
            <tr><th>ID</th><th>Usuario</th><th>Rol</th><th>Estado</th></tr>
          </thead>
          <tbody>
            <?php foreach ($clientes as $c): ?>
              <tr>
                <td><?php echo $c['id']; ?></td>
                <td><?php echo $c['usuario']; ?></td>
                <td><?php echo $c['rol']; ?></td>
                <td><?php echo $c['estado'] ? '✅ Activado' : '❌ Desactivado'; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align:center; color:#ff8800;">No se encontraron clientes.</p>
      <?php endif; ?>

      <!-- Resultados Deudas -->
      <h2 style="text-align:center; color:#ff8800;">Deudas encontradas</h2>
      <?php if (count($deudas) > 0): ?>
        <table class="search-results">
          <thead>
            <tr><th>ID</th><th>Cliente</th><th>Monto</th><th>Descripción</th></tr>
          </thead>
          <tbody>
            <?php foreach ($deudas as $d): ?>
              <tr>
                <td><?php echo $d['id']; ?></td>
                <td><?php echo $d['usuario']; ?></td>
                <td><?php echo $d['monto']; ?></td>
                <td><?php echo $d['descripcion']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align:center; color:#ff8800;">No se encontraron deudas.</p>
      <?php endif; ?>

      <!-- Resultados Citas -->
      <h2 style="text-align:center; color:#ff8800;">Citas encontradas</h2>
      <?php if (count($citas) > 0): ?>
        <table class="search-results">
          <thead>
            <tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Descripción</th></tr>
          </thead>
          <tbody>
            <?php foreach ($citas as $cita): ?>
              <tr>
                <td><?php echo $cita['id']; ?></td>
                <td><?php echo $cita['usuario']; ?></td>
                <td><?php echo $cita['fecha']; ?></td>
                <td><?php echo $cita['motivo']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align:center; color:#ff8800;">No se encontraron citas.</p>
      <?php endif; ?>

    <?php endif; ?>

    <!-- Panel original -->
    <div class="panel">
      <!-- CLIENTES -->
      <div class="card">
        <h3>👥 Clientes</h3>
        <a href="clientes.php">🔍 Ver Clientes</a>
        <a href="nuevo_cliente.php">➕ Agregar Cliente</a>
      </div>

      <!-- CITAS -->
      <div class="card">
        <h3>📅 Citas</h3>
        <a href="citas.php">🔍 Ver Citas</a>
        <a href="nueva_cita.php">➕ Agregar Cita</a>
      </div>

      <!-- DEUDAS -->
      <div class="card">
        <h3>💸 Deudas</h3>
        <a href="deudas.php">🔍 Ver Deudas</a>
        <a href="nueva_deuda.php">➕ Agregar Deuda</a>
      </div>

      <!-- NOTIFICACIONES -->
      <div class="card">
        <h3>📬 Solicitudes</h3>
        <a href="notificaciones.php">🔔 Ver Notificaciones</a>
      </div>
    </div>
  </div>
</body>
</html>
