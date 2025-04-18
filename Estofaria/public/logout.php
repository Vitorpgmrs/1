<?php
// Inicia a sessão com configurações seguras
session_start([
    'cookie_lifetime' => 86400, // 1 dia
    'cookie_secure'   => true,  // Requer HTTPS
    'cookie_httponly' => true,  // Acessível apenas via HTTP
    'use_strict_mode' => true   // Prevenção contra fixation
]);

// Verifica se há sessão ativa antes de destruir
if (isset($_SESSION['usuario'])) {
    // Registra o logout para auditoria (opcional)
    error_log("Usuário ".$_SESSION['usuario']['email']." fez logout em ".date('Y-m-d H:i:s'));
    
    // Destrói a sessão de forma segura
    $_SESSION = array(); // Limpa todos os dados
    
    // Remove o cookie de sessão
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
    
    // Mensagem de feedback (opcional)
    $_SESSION['logout_message'] = "Você foi desconectado com sucesso";
} else {
    // Se não havia sessão ativa
    $_SESSION['logout_message'] = "Nenhuma sessão ativa para encerrar";
}

// Redirecionamento seguro
header("Location: https://".$_SERVER['HTTP_HOST']."/Estofaria/public/");
exit;
?>