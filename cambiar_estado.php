<?php
$conexion = new mysqli("localhost", "root", "", "gym_db", 3306);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_GET['id'];
$estado = $_GET['estado'];

$sql = "UPDATE clientes SET estado = $estado WHERE id = $id";
if ($conexion->query($sql) === TRUE) {
    header("Location: dashboard.php"); // Redirige de vuelta al panel
} else {
    echo "Error: " . $conexion->error;
}

$conexion->close();
?>
