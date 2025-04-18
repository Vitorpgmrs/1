<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Verifica mensagem de logout
if (isset($_SESSION['logout_message'])) {
  echo '<div class="logout-notification">'.htmlspecialchars($_SESSION['logout_message']).'</div>';
  echo '<script>setTimeout(()=>document.querySelector(".logout-notification").remove(),3000);</script>';
  unset($_SESSION['logout_message']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estofaria Master</title>
  <link rel="stylesheet" href="/Estofaria/public/assets/css/style.css">
  <style>
    /* Estilos adicionais para o novo layout */
    .user-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-direction: row; /* Alterado para linha normal */
    }
    
    .username {
      order: 1; /* Nome primeiro */
      font-weight: 500;
      color: #fff;
    }
    
    .avatar {
      order: 2; /* Avatar depois */
      width: 40px;
      height: 40px;
      background: #f39c12;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.1rem;
    }
    
    .dropdown {
      right: 0; /* Mantém o dropdown alinhado à direita */
    }
  </style>
</head>
<body>
<header>
  <div class="header-container">
    <div class="logo">
      <h1>Estofaria Master</h1>
    </div>
    
    <nav class="main-nav">
      <a href="/Estofaria/public/">Início</a>
      <a href="#servicos">Serviços</a>
      <a href="#sobre">Sobre</a>
      <a href="#contato">Contato</a>

      <?php if (isset($_SESSION['usuario'])): ?>
        <div class="user-menu">
          <span class="username"><?= explode(' ', $_SESSION['usuario']['nome'])[0] ?></span>
          <div class="avatar">
            <?= strtoupper(substr(explode(' ', $_SESSION['usuario']['nome'])[0], 0, 1)) ?>
          </div>
          <ul class="dropdown">
            <li><a href="/Estofaria/public/minha-conta/editar-perfil.php">Meu Perfil</a></li>
            <li><a href="/Estofaria/public/logout.php">Sair</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="/Estofaria/public/login.php" class="btn-login">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>