<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure'   => true,
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /Estofaria/public/login.php");
    exit;
}

include_once __DIR__ . '/../../partials/header.php';

// Conexão com o banco
require_once __DIR__ . '/../../config/database.php';
$db = new Database();
$pdo = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Verifica se a senha atual está correta
    $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario']['id']]);
    $usuario = $stmt->fetch();
    
    if (password_verify($senha_atual, $usuario['senha'])) {
        if ($nova_senha === $confirmar_senha) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt->execute([$nova_senha_hash, $_SESSION['usuario']['id']]);
            
            $success = "Senha alterada com sucesso!";
        } else {
            $error = "As novas senhas não coincidem";
        }
    } else {
        $error = "Senha atual incorreta";
    }
}
?>

<div class="change-password-container">
    <h2>Alterar Senha</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="POST" class="change-password-form">
        <div class="form-group">
            <label for="senha_atual">Senha Atual</label>
            <input type="password" id="senha_atual" name="senha_atual" required>
        </div>
        
        <div class="form-group">
            <label for="nova_senha">Nova Senha</label>
            <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="confirmar_senha">Confirmar Nova Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn-save">Alterar Senha</button>
            <a href="/Estofaria/public/minha-conta/" class="btn-cancel">Voltar</a>
        </div>
    </form>
</div>

<?php include_once __DIR__ . '/../../partials/footer.php'; ?>