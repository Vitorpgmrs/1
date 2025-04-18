<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: /Estofaria/public/login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Estofaria em Porto Alegre especializada em reforma e criações sob medida." />
  <meta name="keywords" content="estofaria, reforma de estofados, estofados sob medida, Porto Alegre" />
  <meta name="author" content="Estofaria Master" />
  <title>Estofaria Master | Reforma e Criação Sob Medida</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <script src="assets/js/main.js" defer></script>
</head>
<body>

  <?php include_once 'partials/header.php'; ?>

  <main>
    <section id="banner">
      <div class="container">
        <h2>Reforma e Criação de Estofados Personalizados</h2>
        <p>Atendimento em Porto Alegre e região</p>
        <a href="#contato" class="btn">Solicite um Orçamento</a>
      </div>
    </section>

    <section id="servicos">
      <div class="container">
        <h2>Serviços</h2>
        <div class="cards">
          <div class="card">
            <h3>Reforma de Estofados</h3>
            <p>Renovamos sofás, cadeiras, poltronas e muito mais.</p>
          </div>
          <div class="card">
            <h3>Criações Sob Medida</h3>
            <p>Projetamos e criamos peças exclusivas para sua casa ou comércio.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="sobre">
      <div class="container">
        <h2>Sobre Nós</h2>
        <p>Somos uma estofaria com mais de 15 anos de experiência, focada na qualidade e personalização dos nossos serviços.</p>
      </div>
    </section>

    <section id="projetos">
      <div class="container">
        <h2>Projetos Realizados</h2>
        <div class="cards">
          <div class="card">
            <img src="assets/img/projeto1.jpg" alt="Projeto 1" style="width:100%;border-radius:8px;">
            <p>Sofá reformado em couro ecológico.</p>
          </div>
          <div class="card">
            <img src="assets/img/projeto2.jpg" alt="Projeto 2" style="width:100%;border-radius:8px;">
            <p>Poltrona sob medida para escritório.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="contato">
      <div class="container">
        <h2>Contato</h2>
        <p>Entre em contato pelo WhatsApp ou preencha nosso formulário para orçamento.</p>
        <form action="enviar.php" method="POST">
          <input type="text" name="nome" placeholder="Seu nome" required />
          <input type="email" name="email" placeholder="Seu e-mail" required />
          <textarea name="mensagem" rows="5" placeholder="Sua mensagem" required></textarea>
          <button type="submit">Enviar</button>
        </form>
      </div>
    </section>
  </main>

  <?php include_once 'partials/footer.php'; ?>
</body>
</html>
