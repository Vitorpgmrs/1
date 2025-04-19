<?php
// Estofaria/config/session.php

/**
 * Configurações seguras de sessão
 */

// Verifica se a sessão não está ativa
if (session_status() === PHP_SESSION_NONE) {
    // Configurações recomendadas para sessão segura
    session_start([
        'cookie_lifetime' => 86400,       // 1 dia
        'cookie_secure'   => false,       // Mude para true em produção com HTTPS
        'cookie_httponly' => true,        // Impede acesso via JavaScript
        'use_strict_mode' => true         // Prevenção contra fixation
    ]);
    
    // Regenera o ID da sessão periodicamente
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > 1800) { // 30 minutos
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}