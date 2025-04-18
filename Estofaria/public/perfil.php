<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Perfil do Usuário</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h2>Perfil de <?= $_SESSION['usuario']['nome'] ?></h2>
    <p><strong>Nome:</strong> <?= $_SESSION['usuario']['nome'] ?></p>
    <p><strong>Email:</strong> <?= $_SESSION['usuario']['email'] ?></p>
    <a href="index.php" class="btn">Voltar ao início</a>
  </div>
</body>
</html>
