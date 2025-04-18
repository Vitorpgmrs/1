<?php
session_start();
require_once '../../src/auth/sessionsmanagers.php';
require_once '../../src/models/user.php';
require_once '../../config/env.php';

if (!isUserLoggedIn()) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$userId = getLoggedInUserId();
$user = getUserById($userId);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Usuário</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <main class="container">
        <h1>Bem-vindo, <?= htmlspecialchars($user['nome']) ?>!</h1>

        <section class="user-info">
            <p><strong>Nome:</strong> <?= htmlspecialchars($user['nome']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </section>

        <section class="user-actions">
            <a href="<?= BASE_URL ?>/minha-conta/editar-perfil.php" class="btn">Editar Perfil</a>
            <a href="<?= BASE_URL ?>/minha-conta/atualizar-senha.php" class="btn">Atualizar Senha</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" class="btn btn-danger">Sair</a>
        </section>
    </main>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
