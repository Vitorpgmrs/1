<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /Estofaria/public/login.php");
    exit;
}

// Obtém dados atuais
$db = Database::getInstance();
$pdo = $db->getConnection();

$stmt = $pdo->prepare("SELECT nome, email, telefone FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario']['id']]);
$usuario = $stmt->fetch();

// Processa formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
        
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $telefone, $_SESSION['usuario']['id']]);
        
        // Atualiza sessão
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['email'] = $email;
        
        $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
        header("Location: /Estofaria/public/minha-conta/");
        exit;
        
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao atualizar: " . $e->getMessage();
    }
}

include_once __DIR__ . '/../../partials/header.php';
?>

<div class="container ed