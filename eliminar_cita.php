<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

include("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: citas.php");
    exit();
}

$id = $_GET['id'];

$conexion->query("DELETE FROM citas WHERE id = $id");

header("Location: citas.php");
exit();
