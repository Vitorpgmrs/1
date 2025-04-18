<?php
/**
 * Configuração segura do banco de dados
 * Caminho: Estofaria/config/database.php
 */

// Definindo constantes de conexão
define('DB_HOST', 'localhost');
define('DB_NAME', 'estofaria');
define('DB_USER', 'root');       // Substitua se necessário
define('DB_PASS', '');           // Substitua pela sua senha

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Registra o erro no log do servidor
            error_log("Erro de conexão: " . $e->getMessage());
            
            // Mensagem amigável para o usuário
            die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}

/**
 * Função auxiliar para obter conexão
 * @return PDO
 */
function getDBConnection() {
    return Database::getInstance();
}

// Teste de conexão automático (remova em produção)
try {
    $testConn = getDBConnection();
    error_log("Conexão com o banco estabelecida com sucesso");
} catch (Exception $e) {
    error_log("Falha na conexão com o banco: " . $e->getMessage());
}
?>