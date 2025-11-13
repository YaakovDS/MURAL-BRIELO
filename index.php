<?php
include('conexao.php');

// Função para buscar imagem por posição
function getImagem($conn, $posicao) {
    $sql = "SELECT nome_arquivo FROM imagens WHERE posicao = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $posicao);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return "uploads/" . $row['nome_arquivo'];
    } else {
        return "imagens/placeholder.jpg"; // imagem padrão
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mural Brielo</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        button {
            background-color: transparent;
            color: #333;
            border: 2px solid #333;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #333;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        #btnTopo {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 12px 16px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            display: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transition: background-color 0.3s;
        }
        #btnTopo:hover { background-color: #555; }

        #carrossel {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }
        #carrossel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
        }
        #carrossel img.ativa { opacity: 1; }

        #imagens1, #imagens2, #imagens3, #imagens4, #imagens5 {
            display: flex;
            margin: 20px 0;
            width: 100%;
        }
        #imagens1 img, #imagens4 img { width: 50%; height: 400px; object-fit: cover; }
        #imagens2 img { width: 100%; height: 400px; object-fit: cover; }
        #imagens3 img, #imagens5 img { width: 25%; height: 400px; object-fit: cover; }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <!-- CARROSSEL -->
    <div id="carrossel">
        <img src="<?= getImagem($conn, 'carrossel_1') ?>" class="ativa" alt="Imagem 1">
        <img src="<?= getImagem($conn, 'carrossel_2') ?>" alt="Imagem 2">
        <img src="<?= getImagem($conn, 'carrossel_3') ?>" alt="Imagem 3">
    </div>

    <!-- SEÇÕES -->
    <div id="imagens1">
        <img src="<?= getImagem($conn, 'imagens1_1') ?>" alt="Imagem 1">
        <img src="<?= getImagem($conn, 'imagens1_2') ?>" alt="Imagem 2">
    </div>

    <div id="imagens2">
        <img src="<?= getImagem($conn, 'imagens2_1') ?>" alt="Imagem 1">
    </div>

    <div id="imagens3">
        <img src="<?= getImagem($conn, 'imagens3_1') ?>" alt="Imagem 1">
        <img src="<?= getImagem($conn, 'imagens3_2') ?>" alt="Imagem 2">
        <img src="<?= getImagem($conn, 'imagens3_3') ?>" alt="Imagem 3">
        <img src="<?= getImagem($conn, 'imagens3_4') ?>" alt="Imagem 4">
    </div>

    <div id="imagens4">
        <img src="<?= getImagem($conn, 'imagens4_1') ?>" alt="Imagem 1">
        <img src="<?= getImagem($conn, 'imagens4_2') ?>" alt="Imagem 2">
    </div>

    <div id="imagens5">
        <img src="<?= getImagem($conn, 'imagens5_1') ?>" alt="Imagem 1">
        <img src="<?= getImagem($conn, 'imagens5_2') ?>" alt="Imagem 2">
        <img src="<?= getImagem($conn, 'imagens5_3') ?>" alt="Imagem 3">
        <img src="<?= getImagem($conn, 'imagens5_4') ?>" alt="Imagem 4">
    </div>

    <?php include('includes/footer.php'); ?>

    <button id="btnTopo" title="Voltar ao topo">↑</button>

    <script>
        // CARROSSEL AUTOMÁTICO
        const imagens = document.querySelectorAll('#carrossel img');
        let index = 0;
        function trocarImagem() {
            imagens[index].classList.remove('ativa');
            index = (index + 1) % imagens.length;
            imagens[index].classList.add('ativa');
        }
        setInterval(trocarImagem, 4000);

        // BOTÃO TOPO
        const btnTopo = document.getElementById('btnTopo');
        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                btnTopo.style.display = "block";
            } else {
                btnTopo.style.display = "none";
            }
        };
        btnTopo.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
