<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include(__DIR__ . '/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha   = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = $usuario;
            header('Location: dashboard.php');
            exit;
        } else {
            $erro = 'Senha incorreta';
        }
    } else {
        $erro = 'Usuário não encontrado';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Login - Mural Brielo</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh;">
<div class="container text-center">
    <div class="card mx-auto shadow" style="max-width:400px;">
        <div class="card-body">
            <h4 class="mb-3">Painel Administrativo</h4>
            <?php if(isset($erro)) echo '<div class="alert alert-danger">'.$erro.'</div>'; ?>
            <form method="POST">
                <input type="text" name="usuario" class="form-control mb-2" placeholder="Usuário" required>
                <input type="password" name="senha" class="form-control mb-3" placeholder="Senha" required>
                <button class="btn btn-dark w-100">Entrar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
