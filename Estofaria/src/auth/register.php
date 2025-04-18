<?php
require_once __DIR__ . '/../../database/connect.php';

class Register {
    public static function cadastrar(array $dados): bool {
        global $conn;

        // Verifica se e-mail ou celular já existem
        $sql = "SELECT id FROM usuarios WHERE email = ? OR celular = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dados['email'], $dados['celular']);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("E-mail ou celular já cadastrado");
        }

        // Insere no banco
        $sql = "INSERT INTO usuarios (nome, sobrenome, data_nascimento, email, celular, senha) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $senha_hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $stmt->bind_param(
            "ssssss", 
            $dados['nome'],
            $dados['sobrenome'],
            $dados['data_nascimento'],
            $dados['email'],
            $dados['celular'],
            $senha_hash
        );
        
        return $stmt->execute();
    }
}