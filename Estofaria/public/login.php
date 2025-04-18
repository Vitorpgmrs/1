<?php
session_start();

if (isset($_SESSION['usuario'])) {
  header("Location: /Estofaria/public/");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Estofaria Master</title>
  <link rel="stylesheet" href="/Estofaria/public/assets/css/style.css">
</head>
<body>
  <?php include 'partials/header.php'; ?>

  <main class="auth-container">
    <div class="auth-card">
      <?php if (isset($_SESSION['login_error'])): ?>
        <div class="error-message"><?= $_SESSION['login_error'] ?></div>
        <?php unset($_SESSION['login_error']); ?>
      <?php endif; ?>

      <h2>Login</h2>
      
      <form action="/Estofaria/public/processa_login.php" method="POST">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required>
        </div>
        
        <button type="submit" class="btn">Entrar</button>
      </form>
      
      <p class="auth-link">
        Não tem conta? <a href="/Estofaria/public/cadastro.php" class="register-link">Cadastre-se</a>
      </p>
    </div>
  </main>

  <?php include 'partials/footer.php'; ?>
</body>
</html>