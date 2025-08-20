<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verificar si se recibió el plan
if (isset($_GET['plan'])) {
    $plan = $_GET['plan'];

    // Validar el plan
    $planes_validos = ['basico', 'intermedio', 'pro', 'vip'];
    if (!in_array($plan, $planes_validos)) {
        die("❌ Plan no válido.");
    }

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "gym_db", 3306);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $usuario = $_SESSION['usuario'];

    // Actualizar el plan
    $sql = "UPDATE usuarios SET plan_actual = '$plan' WHERE usuario = '$usuario'";
    if ($conexion->query($sql) === TRUE) {
        header("Location: bienvenida.php?mensaje=plan_comprado&plan=$plan");
        exit();
    } else {
        die("❌ Error al actualizar el plan: " . $conexion->error);
    }
} else {
    die("⚠️ No se ha recibido ningún plan.");
}
?>
