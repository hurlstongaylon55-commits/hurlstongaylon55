<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "gym_db", 3306);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$usuario = $_POST['usuario'];
$contrasena = md5($_POST['contrasena']);

// Consultar la base de datos
$consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contrasena='$contrasena'";
$resultado = $conexion->query($consulta);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $_SESSION['usuario'] = $fila['usuario'];
    $_SESSION['rol'] = $fila['rol'];

    if ($fila['rol'] === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: bienvenido.php");
    }
    exit();
} else {
    // Redirigir al index con un mensaje de error
    header("Location: index.php?error=1");
    exit();
}

