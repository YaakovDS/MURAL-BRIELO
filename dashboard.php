<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include(__DIR__ . '/conexao.php');


// Proteção de login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Upload de imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
    $posicao = $_POST['posicao'];
    $arquivo = $_FILES['imagem']['name'];
    $temp = $_FILES['imagem']['tmp_name'];
    $destino = 'uploads/' . basename($arquivo);

    if (move_uploaded_file($temp, $destino)) {
        $stmt = $conn->prepare("INSERT INTO imagens (nome_arquivo, posicao) VALUES (?, ?)");
        $stmt->bind_param('ss', $arquivo, $posicao);
        $stmt->execute();
        $mensagem = "Imagem enviada com sucesso!";
    } else {
        $mensagem = "Erro ao enviar imagem.";
    }
}

// Excluir imagem
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "SELECT nome_arquivo FROM imagens WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        unlink('uploads/' . $row['nome_arquivo']);
    }
    $conn->query("DELETE FROM imagens WHERE id = $id");
    header('Location: dashboard.php');
    exit;
}

// Lista de imagens
$result = $conn->query("SELECT * FROM imagens ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel - Mural Brielo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="mb-4">Painel Administrativo</h3>
    <a href="logout.php" class="btn btn-danger mb-3">Sair</a>
	<br> <a href="dashboard_curriculos.php" class="btn btn-primary mb-3">📄 Ver Currículos</a> <br>


    <?php if(isset($mensagem)): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-3 mb-4">
        <label class="form-label">Escolher imagem:</label>
        <input type="file" name="imagem" class="form-control mb-3" required>

        <label class="form-label">Escolher posição no site:</label>
        <select name="posicao" class="form-select mb-3" required>
            <optgroup label="Carrossel">
                <option value="carrossel_1">Carrossel - Imagem 1</option>
                <option value="carrossel_2">Carrossel - Imagem 2</option>
                <option value="carrossel_3">Carrossel - Imagem 3</option>
            </optgroup>
            <optgroup label="Seção 1">
                <option value="imagens1_1">Seção 1 - Imagem 1</option>
                <option value="imagens1_2">Seção 1 - Imagem 2</option>
            </optgroup>
            <optgroup label="Seção 2">
                <option value="imagens2_1">Seção 2 - Imagem 1</option>
            </optgroup>
            <optgroup label="Seção 3">
                <option value="imagens3_1">Seção 3 - Imagem 1</option>
                <option value="imagens3_2">Seção 3 - Imagem 2</option>
                <option value="imagens3_3">Seção 3 - Imagem 3</option>
                <option value="imagens3_4">Seção 3 - Imagem 4</option>
            </optgroup>
            <optgroup label="Seção 4">
                <option value="imagens4_1">Seção 4 - Imagem 1</option>
                <option value="imagens4_2">Seção 4 - Imagem 2</option>
            </optgroup>
        </select>

        <button type="submit" class="btn btn-dark">Enviar</button>
    </form>

    <h5>Imagens Enviadas</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Arquivo</th>
                <th>Posição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><img src="uploads/<?= $row['nome_arquivo'] ?>" width="100"></td>
                <td><?= $row['posicao'] ?></td>
                <td>
                    <a href="?excluir=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir esta imagem?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
