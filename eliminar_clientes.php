<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: clientes.php");
    exit();
}

$id = $_GET['id'];

// Primero eliminar las citas y deudas relacionadas para evitar errores de FK (si tienes relaciones)
// Opcional: puedes hacerlo con ON DELETE CASCADE en la base de datos, pero aquí te lo dejo explícito:

$conexion->query("DELETE FROM citas WHERE id_usuario = $id");
$conexion->query("DELETE FROM deudas WHERE id_usuario = $id");

// Luego eliminar el usuario
$conexion->query("DELETE FROM usuarios WHERE id = $id");

header("Location: clientes.php");
exit();
