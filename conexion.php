<?php
// Datos de conexión
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_de_datos = "gym_db";
$puerto = 3306; // Puerto por defecto de MySQL en XAMPP

// Crear conexión
$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos, $puerto);

// Verificar conexión
if ($conexion->connect_error) {
    die("❌ Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Configurar el charset a UTF-8 para evitar problemas con acentos y caracteres especiales
if (!$conexion->set_charset("utf8")) {
    die("❌ Error configurando el charset UTF-8: " . $conexion->error);
}

// Mensaje opcional de éxito (puedes comentar esta línea si no quieres que aparezca)
// echo "✅ Conexión exitosa a la base de datos.";
?>
