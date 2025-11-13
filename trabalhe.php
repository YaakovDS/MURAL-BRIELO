<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include(__DIR__ . '/conexao.php');

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $cargo = trim($_POST["cargo"]);
    $mensagemTxt = trim($_POST["mensagem"]);
    $arquivo = $_FILES["curriculo"];

    $uploadDir = __DIR__ . "/uploads/";
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);

    $nomeArquivo = uniqid() . "_" . basename($arquivo["name"]);
    $caminhoArquivo = $uploadDir . $nomeArquivo;

    $extensao = strtolower(pathinfo($arquivo["name"], PATHINFO_EXTENSION));
    $permitidos = ["pdf", "doc", "docx"];

    if (!in_array($extensao, $permitidos)) {
        $mensagem = "❌ Apenas arquivos PDF, DOC ou DOCX são permitidos.";
    } elseif ($arquivo["size"] > 3 * 1024 * 1024) { // 3MB
        $mensagem = "❌ O arquivo é muito grande (máx. 3MB).";
    } elseif (move_uploaded_file($arquivo["tmp_name"], $caminhoArquivo)) {
        // Salva no banco de dados
        $stmt = $conn->prepare("INSERT INTO curriculos (nome, email, cargo, mensagem, arquivo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $email, $cargo, $mensagemTxt, $nomeArquivo);
        $stmt->execute();
        $stmt->close();

        $mensagem = "✅ Currículo enviado com sucesso!";
    } else {
        $mensagem = "⚠️ Erro ao enviar o arquivo.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Trabalhe Conosco - Mural Brielo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f8f8;
}
header {
    background-color: #fff;
    padding: 20px 40px;
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 1px solid #eee;
}
header img { height: 70px; width: 70px; object-fit: contain; }
header h1 { font-size: 26px; color: #333; margin-right: auto; }
main {
    max-width: 650px;
    margin: 50px auto;
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
button {
    background: #333;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 500;
}
button:hover { background: #555; }
.mensagem { text-align:center; margin-bottom:20px; font-weight:600; }
footer { text-align:center; padding:20px; color:#888; }
</style>
</head>
<body>

<header>
    <img src="includes/LOGO.png" alt="Logo do Mural Brielo">
    <h1>MURAL BRIELO</h1>
</header>

<main>
    <h2 class="text-center mb-4">Trabalhe Conosco</h2>
    <?php if ($mensagem): ?>
        <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label class="form-label">Nome completo</label>
        <input type="text" name="nome" class="form-control" required>

        <label class="form-label mt-3">E-mail</label>
        <input type="email" name="email" class="form-control" required>

        <label class="form-label mt-3">Cargo desejado</label>
        <select name="cargo" class="form-select" required>
            <option value="">Selecione...</option>
            <option value="Fotógrafo">Fotógrafo</option>
            <option value="Editor de Imagens">Editor de Imagens</option>
            <option value="Social Media">Social Media</option>
            <option value="Outro">Outro</option>
        </select>

        <label class="form-label mt-3">Mensagem</label>
        <textarea name="mensagem" class="form-control" rows="4" required></textarea>

        <label class="form-label mt-3">Currículo (PDF, DOC ou DOCX)</label>
        <input type="file" name="curriculo" class="form-control" required>

        <button type="submit" class="btn w-100 mt-4">Enviar</button>
    </form>
</main>

<footer>
    &copy; <?= date('Y') ?> Mural Brielo - Todos os direitos reservados.
</footer>

</body>
</html>
