<?php
include(__DIR__ . '/conexao.php');
$result = $conn->query("SELECT * FROM trabalhe ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Candidatos - Mural Brielo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
<h2>📄 Candidatos Recebidos</h2>
<table class="table table-striped mt-3">
<tr><th>ID</th><th>Nome</th><th>Email</th><th>Cargo</th><th>Mensagem</th><th>Currículo</th><th>Data</th></tr>
<?php while($r = $result->fetch_assoc()): ?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= htmlspecialchars($r['nome']) ?></td>
<td><?= htmlspecialchars($r['email']) ?></td>
<td><?= htmlspecialchars($r['cargo']) ?></td>
<td><?= htmlspecialchars($r['mensagem']) ?></td>
<td><a href="uploads/curriculos<?= $r['arquivo'] ?>" target="_blank">Ver arquivo</a></td>
<td><?= $r['data_envio'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
