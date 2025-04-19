<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/database.php';

// Verifica autenticação
if (!isset($_SESSION['usuario'])) {
    header("Location: /Estofaria/public/login.php");
    exit;
}

// Obtém dados do usuário
try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $stmt = $pdo->prepare("SELECT nome, email, telefone, data_cadastro FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario']['id']]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        throw new Exception("Usuário não encontrado");
    }
    
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao carregar dados: " . $e->getMessage();
    header("Location: /Estofaria/public/erro.php");
    exit;
}

// Inclui o header
include_once __DIR__ . '/../../partials/header.php';
?>

<div class="container minha-conta">
    <h1 class="titulo-pagina">Minha Conta</h1>
    
    <!-- Mensagens de feedback -->
    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
        <?php unset($_SESSION['sucesso']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['erro']) ?></div>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>
    
    <div class="card perfil">
        <div class="card-header">
            <h2>Informações Pessoais</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="avatar">
                        <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="info-item">
                        <span class="info-label">Nome:</span>
                        <span class="info-value"><?= htmlspecialchars($usuario['nome']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">E-mail:</span>
                        <span class="info-value"><?= htmlspecialchars($usuario['email']) ?></span>
                    </div>
                    <?php if ($usuario['telefone']): ?>
                    <div class="info-item">
                        <span class="info-label">Telefone:</span>
                        <span class="info-value"><?= htmlspecialchars($usuario['telefone']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <span class="info-label">Membro desde:</span>
                        <span class="info-value"><?= date('d/m/Y', strtotime($usuario['data_cadastro'])) ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="/Estofaria/public/minha-conta/editar.php" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
            <a href="/Estofaria/public/minha-conta/senha.php" class="btn btn-secondary">
                <i class="fas fa-lock"></i> Alterar Senha
            </a>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../partials/footer.php'; ?>