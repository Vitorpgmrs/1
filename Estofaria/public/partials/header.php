<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logout_redirect'])) {
    unset($_SESSION['logout_redirect']);
    
    echo '<div class="logout-overlay" id="logoutOverlay"></div>';
    echo '<script>
            // Mostra o overlay
            const overlay = document.getElementById("logoutOverlay");
            
            // Recarrega a página após 1 segundo
            setTimeout(() => {
                window.location.href = "/Estofaria/public/";
            }, 300);
          </script>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estofaria Master</title>
    <link rel="stylesheet" href="/Estofaria/assets/css/style.css">
    <style>
        /* Overlay de logout */
        .logout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
            z-index: 9999;
            pointer-events: none;
            animation: fadeIn 0.3s forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Seus estilos originais */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-direction: row;
        }
        
        /* ... (mantenha outros estilos) ... */
    </style>
</head>
<body>
<!-- Seu header original -->
<header>
    <div class="header-container">
        <div class="logo">
            <h1>Estofaria Master</h1>
        </div>
        
        <nav class="main-nav">
            <a href="/Estofaria/public/">Início</a>
            <a href="/Estofaria/public/servicos.php">Serviços</a>
            <a href="/Estofaria/public/sobre.php">Sobre</a>
            <a href="/Estofaria/public/contato.php">Contato</a>

            <?php if (isset($_SESSION['usuario'])): ?>
                <div class="user-menu">
                    <span class="username"><?= htmlspecialchars(explode(' ', $_SESSION['usuario']['nome'])[0]) ?></span>
                    <div class="avatar">
                        <?= strtoupper(substr(explode(' ', $_SESSION['usuario']['nome'])[0], 0, 1)) ?>
                    </div>
                    <ul class="dropdown">
                        <li><a href="/Estofaria/public/minha-conta/">Minha Conta</a></li>
                        <li><a href="/Estofaria/public/logout.php">Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="auth-buttons" style="display: flex;">
                    <a href="/Estofaria/public/login.php" class="btn-login">Login</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>