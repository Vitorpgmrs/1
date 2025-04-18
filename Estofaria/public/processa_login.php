<?php
session_start();

// Configuração do banco de dados (ATUALIZE COM SEUS DADOS)
$host = 'localhost';
$dbname = 'estofaria';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email']
            ];
            header("Location: /Estofaria/public/");
            exit;
        }
    }

    $_SESSION['login_error'] = "E-mail ou senha incorretos";
    header("Location: /Estofaria/public/login.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['login_error'] = "Erro no servidor: " . $e->getMessage();
    header("Location: /Estofaria/public/login.php");
    exit;
}
?>