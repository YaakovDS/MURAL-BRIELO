<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Excluir currículo
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "SELECT arquivo FROM curriculos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        unlink('uploads/' . $row['arquivo']);
    }
    $conn->query("DELETE FROM curriculos WHERE id = $id");
    header('Location: dashboard_curriculos.php');
    exit;
}

// Buscar todos os currículos
$result = $conn->query("SELECT * FROM curriculos ORDER BY data_envio DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Currículos Recebidos - Painel Mural Brielo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="mb-4">📄 Currículos Recebidos</h3>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Voltar ao Painel</a>

    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Mensagem</th>
                <th>Arquivo</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['cargo']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['mensagem'])) ?></td>
                <td><a href="uploads/<?= htmlspecialchars($row['arquivo']) ?>" target="_blank">Baixar</a></td>
                <td><?= date('d/m/Y H:i', strtotime($row['data_envio'])) ?></td>
                <td><a href="?excluir=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este currículo?')">Excluir</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
