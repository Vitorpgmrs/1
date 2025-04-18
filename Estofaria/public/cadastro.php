<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getDBConnection();
        
        // Validação básica (mantida)
        if ($_POST['senha'] !== $_POST['confirmar_senha']) {
            throw new Exception("As senhas não coincidem");
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios 
            (nome, sobrenome, email, telefone, senha, cep, endereco, numero, complemento, bairro, cidade, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_POST['nome'],
            $_POST['sobrenome'],
            $_POST['email'],
            $_POST['telefone'],
            password_hash($_POST['senha'], PASSWORD_BCRYPT),
            $_POST['cep'] ?? null,
            $_POST['endereco'] ?? null,
            $_POST['numero'] ?? null,
            $_POST['complemento'] ?? null,
            $_POST['bairro'] ?? null,
            $_POST['cidade'] ?? null,
            $_POST['estado'] ?? null
        ]);

        // --- NOVO: LOGIN AUTOMÁTICO ---
        $userId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $_SESSION['usuario'] = $stmt->fetch();
        
        // Redireciona para a página principal (alterado)
        header("Location: /Estofaria/public/index.php");
        exit;

    } catch (PDOException $e) {
        $error = $e->getCode() == 23000 ? "E-mail já cadastrado" : "Erro no cadastro";
        $_SESSION['error_message'] = $error;
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Estofaria Master</title>
    <link rel="stylesheet" href="/Estofaria/public/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <main class="auth-container">
        <div class="auth-card">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message"><?= $_SESSION['error_message'] ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <h2>Crie sua conta</h2>
            
            <form id="form-cadastro" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome*</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="sobrenome">Sobrenome*</label>
                        <input type="text" id="sobrenome" name="sobrenome" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail*</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone*</label>
                        <input type="tel" id="telefone" name="telefone" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="senha">Senha*</label>
                        <input type="password" id="senha" name="senha" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Senha*</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" class="cep-mask">
                    <button type="button" id="buscar-cep" class="btn-small">Buscar</button>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 3">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco">
                    </div>
                    <div class="form-group" style="flex: 1">
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero">
                    </div>
                </div>

                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro">
                    </div>
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                    <div class="form-group" style="flex: 0.5">
                        <label for="estado">UF</label>
                        <input type="text" id="estado" name="estado" maxlength="2">
                    </div>
                </div>

                <button type="submit" class="btn">Cadastrar</button>
            </form>
            
            <p class="auth-link">
                Já tem conta? <a href="/Estofaria/public/login.php">Faça login</a>
            </p>
        </div>
    </main>

    <?php include 'partials/footer.php'; ?>

    <script>
    $(document).ready(function() {
        // Máscara para CEP
        $('.cep-mask').on('input', function() {
            this.value = this.value.replace(/\D/g, '')
                                 .replace(/(\d{5})(\d)/, '$1-$2')
                                 .substr(0, 9);
        });

        // Máscara para telefone
        $('#telefone').on('input', function() {
            this.value = this.value.replace(/\D/g, '')
                                 .replace(/(\d{2})(\d)/, '($1) $2')
                                 .replace(/(\d{5})(\d)/, '$1-$2')
                                 .substr(0, 15);
        });

        // Busca de CEP via API
        $('#buscar-cep').click(function() {
            const cep = $('#cep').val().replace(/\D/g, '');
            if (cep.length !== 8) {
                alert('CEP inválido');
                return;
            }

            $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                if (data.erro) {
                    alert('CEP não encontrado');
                    return;
                }
                
                $('#endereco').val(data.logradouro);
                $('#bairro').val(data.bairro);
                $('#cidade').val(data.localidade);
                $('#estado').val(data.uf);
                $('#numero').focus();
            }).fail(function() {
                alert('Erro ao buscar CEP');
            });
        });
    });
    </script>
</body>
</html>