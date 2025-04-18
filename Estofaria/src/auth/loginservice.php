<?php
require_once __DIR__ . '/../../database/connect.php';

class LoginService {
    public static function login($login, $senha) {
        global $conn;
        
        // Verifica se é e-mail ou telefone
        $campo = filter_var($login, FILTER_VALIDATE_EMAIL) ? "email" : "telefone";
        
        $sql = "SELECT * FROM usuarios WHERE $campo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario; // Sucesso
        }
        return false; // Falha
    }
}
?>