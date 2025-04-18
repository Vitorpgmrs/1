<?php
// Configurações básicas
define('APP_ENV', 'development'); // production/development
define('BASE_URL', '/Estofaria/public');
// Banco de dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'estofaria_prod');

// Segurança
define('ENCRYPTION_KEY', 'sua-chave-secreta-aqui');
define('CSRF_TOKEN_LIFETIME', 1800); // 30 minutos

// E-mail
define('MAIL_HOST', 'smtp.seuprovedor.com');
define('MAIL_USER', 'contato@estofaria.com');
define('MAIL_PASS', 'sua-senha');
define('MAIL_PORT', 587);
