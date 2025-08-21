<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'plan_actualizado') {
    echo "<p style='text-align:center; color:lightgreen; font-weight:bold;'>✅ ¡Tu plan fue actualizado con éxito!</p>";
}
?>
<?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'plan_comprado' && isset($_GET['plan'])) {
    $nombres_planes = [
        'basico' => 'Plan Básico',
        'intermedio' => 'Plan Intermedio',
        'pro' => 'Plan Pro',
        'vip' => 'Plan VIP Anual'
    ];

    $plan_clave = $_GET['plan'];
    $nombrePlan = isset($nombres_planes[$plan_clave]) ? $nombres_planes[$plan_clave] : strtoupper($plan_clave);

    echo "<div style='background:#28a745; padding:15px; border-radius:8px; color:white; text-align:center; font-weight:bold; margin-bottom:20px;'>
            ✅ ¡Has comprado el <strong>$nombrePlan</strong> exitosamente!
          </div>";

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido a KATANA GYM</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: url(KATANA.jpg) no-repeat center center fixed;
      background-size: cover;
      color: #fff;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.85);
      width: 100%;
      min-height: 100vh;
      padding: 40px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .container {
      max-width: 1000px;
      width: 100%;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 0 30px rgba(255, 136, 0, 0.3);
      backdrop-filter: blur(10px);
    }

    h1 {
      font-size: 38px;
      font-weight: 800;
      text-align: center;
      color: #ff8800;
      margin-bottom: 20px;
    }

    p.bienvenida {
      font-size: 18px;
      text-align: center;
      margin-bottom: 30px;
      color: #ccc;
    }

    h2 {
      font-size: 24px;
      color: #ff8800;
      margin-top: 30px;
      margin-bottom: 15px;
      border-bottom: 2px solid #ff8800;
      display: inline-block;
    }

    .planes {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .plan {
      background-color: rgba(255, 255, 255, 0.07);
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      border: 2px solid #ff8800;
      transition: transform 0.3s;
    }

    .plan:hover {
      transform: scale(1.03);
    }

    .plan h3 {
      font-size: 20px;
      color: #ffbb00;
      margin-bottom: 10px;
    }

    .plan p {
      font-size: 16px;
      color: #eee;
    }

    .plan .price {
      font-size: 22px;
      color: #fff;
      margin: 10px 0;
    }

    .btn-plan {
      margin-top: 15px;
      padding: 10px 20px;
      background: #ff8800;
      color: black;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }

    ul {
      list-style: none;
      padding-left: 0;
      margin-bottom: 20px;
    }

    ul li {
      padding: 8px 0;
      border-bottom: 1px dashed rgba(255, 255, 255, 0.2);
      padding-left: 25px;
      position: relative;
      color: #eee;
    }

    ul li::before {
      content: "✔";
      position: absolute;
      left: 0;
      color: #ff8800;
    }

    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 14px;
      color: #aaa;
    }

    .footer strong {
      color: #fff;
    }

  </style>
</head>
<body>
  <div class="overlay">
    <div class="container">
      <h1>🏋️‍♂️ Bienvenido a KATANA GYM 🥷</h1>
      <p class="bienvenida">Hola <strong><?php echo $_SESSION['usuario']; ?></strong>, elige el plan ideal para ti y empieza tu transformación 💪</p>

      <h2>📦 Elige tu Plan</h2>
      <div class="planes">
        <div class="plan">
          <h3>Plan Básico</h3>
          <p>Ideal para quienes quieren empezar</p>
          <div class="price">L.500 / mes</div>
          <a href="elegir_plan.php?plan=basico" class="btn-plan">Elegir Plan</a>
        </div>
        <div class="plan">
          <h3>Plan Intermedio</h3>
          <p>Entrena con disciplina y constancia</p>
          <div class="price">L.1,300 / 3 meses</div>
          <a href="elegir_plan.php?plan=intermedio" class="btn-plan">Elegir Plan</a>
        </div>
        <div class="plan">
          <h3>Plan Pro</h3>
          <p>Para atletas y usuarios frecuentes</p>
          <div class="price">L.2,400 / 6 meses</div>
          <a href="elegir_plan.php?plan=pro" class="btn-plan">Elegir Plan</a>
        </div>
        <div class="plan">
          <h3>Plan VIP Anual</h3>
          <p>Accede a todo sin límites</p>
          <div class="price">L.4,500 / año</div>
          <a href="elegir_plan.php?plan=vip" class="btn-plan">Elegir Plan</a>
        </div>
      </div>

      <h2>🏅 Nuestros Entrenadores</h2>
      <ul>
        <li>Coach Alex – Fisicoculturismo</li>
        <li>Coach Ana – Entrenamiento Funcional</li>
        <li>Coach Mario – Box y Defensa Personal</li>
        <li>Coach Carla – Nutrición Deportiva</li>
      </ul>

      <h2>💥 Ventajas de Entrenar en KATANA GYM</h2>
      <ul>
        <li>Acceso 24/7 con huella digital</li>
        <li>Clases grupales GRATIS con instructores certificados</li>
        <li>Áreas de cardio, musculación, box y yoga</li>
        <li>Planificación de entrenamiento personalizada</li>
        <li>Wi-Fi gratis, lockers y duchas</li>
      </ul>

      <div class="footer">
        <p><strong>Ubicación:</strong> La Ceiba, Atlántida 🌴</p>
        <p><strong>Propietario:</strong> Dylan 🧔</p>
        <a href="solicitar_union.php">
  <button style="padding: 10px 20px; background: #ff8800; color: white; font-weight: bold; border: none; border-radius: 8px; cursor: pointer;">
    💪 Quiero unirme a KATANA GYM
  </button>
</a>

        <p>Gracias por formar parte de la familia <strong>KATANA GYM</strong></p>
      </div>
    </div>
  </div>
</body>
</html>
