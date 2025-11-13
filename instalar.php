<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('conexao.php');

$senhaHash = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO usuarios (usuario, senha) VALUES ('admin', '$senhaHash')");
echo "Administrador criado com sucesso! Usuário: admin | Senha: admin123";
?>
