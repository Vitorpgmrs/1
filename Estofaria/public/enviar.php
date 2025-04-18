<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars($_POST["nome"]);
    $email = htmlspecialchars($_POST["email"]);
    $mensagem = htmlspecialchars($_POST["mensagem"]);

    $destino = "seuemail@dominio.com";
    $assunto = "Nova mensagem do site Estofaria";

    $corpo = "Nome: $nome\\n";
    $corpo .= "Email: $email\\n";
    $corpo .= "Mensagem:\\n$mensagem";

    $headers = "From: $email";

    if (mail($destino, $assunto, $corpo, $headers)) {
        echo "<script>alert('Mensagem enviada com sucesso!');window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao enviar mensagem.');window.history.back();</script>";
    }
}
?>
