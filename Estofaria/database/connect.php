<?php
$host = "localhost";
$user = "root";   // Usuário padrão do XAMPP
$pass = "";       // Senha vazia no XAMPP
$db   = "estofaria_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>