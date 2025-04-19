<?php
/**
 * Configuração segura do banco de dados
 * Caminho: Estofaria/config/database.php
 * 
 * Melhorias adicionadas:
 * 1. Correção do erro na linha 104 (página de erro personalizada)
 * 2. Adição de suporte a múltiplos ambientes com .env
 * 3. Métodos adicionais para operações CRUD
 * 4. Melhor tratamento de erros
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Configurações por ambiente
    private const DEV_CONFIG = [
        'host' => 'localhost',
        'name' => 'estofaria_dev',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4'
    ];
    
    private const PROD_CONFIG = [
        'host' => 'localhost',
        'name' => 'estofaria_prod',
        'user' => 'usuario_prod',
        'pass' => 'senha_forte_prod',
        'charset' => 'utf8mb4'
    ];

    private function __construct() {
        try {
            $config = $this->getEnvironmentConfig();
            
            $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']}";
            
            $this->connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']}",
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ]);
            
        } catch (PDOException $e) {
            $this->logError($e);
            $this->handleConnectionError();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function getEnvironmentConfig() {
        // Tenta carregar de um arquivo .env primeiro
        if (file_exists(__DIR__ . '/../.env')) {
            $env = parse_ini_file(__DIR__ . '/../.env');
            return [
                'host' => $env['DB_HOST'] ?? self::DEV_CONFIG['host'],
                'name' => $env['DB_NAME'] ?? self::DEV_CONFIG['name'],
                'user' => $env['DB_USER'] ?? self::DEV_CONFIG['user'],
                'pass' => $env['DB_PASS'] ?? self::DEV_CONFIG['pass'],
                'charset' => $env['DB_CHARSET'] ?? self::DEV_CONFIG['charset']
            ];
        }
        
        // Fallback para configurações hardcoded
        return (getenv('APP_ENV') === 'production') 
            ? self::PROD_CONFIG 
            : self::DEV_CONFIG;
    }

    private function logError(PDOException $e) {
        $errorMsg = sprintf(
            "[%s] Database Error: %s in %s on line %d\nStack Trace:\n%s",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
        
        error_log($errorMsg);
        
        // Registrar em arquivo de log específico
        $logDir = __DIR__ . '/../logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents(
            $logDir . '/database_errors.log',
            $errorMsg . PHP_EOL,
            FILE_APPEND
        );
    }

    private function handleConnectionError() {
        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            die(json_encode([
                'error' => 'Database connection failed',
                'message' => 'Service temporarily unavailable'
            ]));
        }
        
        // Página de erro simplificada (correção do problema na linha 104)
        header("HTTP/1.1 503 Service Unavailable");
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Erro de Conexão</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
                .error-container { max-width: 800px; margin: 50px auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px; }
                h1 { color: #d9534f; }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>Erro de Conexão com o Banco de Dados</h1>
                <p>O sistema não pode se conectar ao banco de dados no momento.</p>
                <p>Por favor, tente novamente mais tarde ou entre em contato com o administrador do sistema.</p>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    // ========== MÉTODOS ÚTEIS ADICIONAIS ========== //
    
    /**
     * Executa uma query SQL
     */
    public static function executeQuery($sql, $params = []) {
        try {
            $stmt = self::getInstance()->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            self::getInstance()->logError($e);
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }

    /**
     * Obtém múltiplos registros
     */
    public static function fetchAll($sql, $params = []) {
        $stmt = self::executeQuery($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtém um único registro
     */
    public static function fetchOne($sql, $params = []) {
        $stmt = self::executeQuery($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Insere um registro e retorna o ID
     */
    public static function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $conn = self::getInstance()->getConnection();
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            self::getInstance()->logError($e);
            throw new Exception("Insert failed: " . $e->getMessage());
        }
    }

    /**
     * Atualiza registros
     */
    public static function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setClause = implode(', ', $set);
        
        $sql = "UPDATE $table SET $setClause WHERE $where";
        $params = array_merge($data, $whereParams);
        
        try {
            $stmt = self::executeQuery($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            self::getInstance()->logError($e);
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }

    /**
     * Deleta registros
     */
    public static function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        try {
            $stmt = self::executeQuery($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            self::getInstance()->logError($e);
            throw new Exception("Delete failed: " . $e->getMessage());
        }
    }
}

// Função auxiliar para compatibilidade
function getDBConnection() {
    return Database::getInstance()->getConnection();
}

// Teste de conexão (remover em produção)
if (php_sapi_name() === 'cli' || isset($_GET['test_db'])) {
    try {
        $conn = Database::getInstance()->getConnection();
        echo "Conexão bem-sucedida!";
        error_log("Conexão com o banco estabelecida com sucesso");
    } catch (Exception $e) {
        echo "Erro na conexão: " . $e->getMessage();
        error_log("Falha na conexão com o banco: " . $e->getMessage());
    }
}