<?php
session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

if (isset($_SESSION['usuario'])) {
    session_unset();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Marca que o logout ocorreu
    session_start();
    $_SESSION['logout_redirect'] = true;
}

header("Location: /Estofaria/public/");
exit;
?>
