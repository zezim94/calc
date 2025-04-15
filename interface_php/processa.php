<?php
$resultado = null;
$erro = null;
$expressao = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expressao = $_POST['expressao'];
    $tipo = $_POST['tipo'];
    $valor_x = $_POST['valor_x'] ?? null;

    $apiUrl = 'http://localhost:5000/calcular';

    $dados = [
        'expressao' => $expressao,
        'tipo' => $tipo,
    ];

    if (!empty($valor_x) && $tipo === 'normal') {
        $dados['valor_x'] = floatval($valor_x);
    }

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    $resposta = curl_exec($ch);
    curl_close($ch);

    if ($resposta) {
        $resultado = json_decode($resposta, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $erro = "Erro ao decodificar resposta da API.";
        }
    } else {
        $erro = "Erro ao se comunicar com a API.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MathJax para LaTeX -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async
        src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
    </script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 shadow">
            <h3 class="text-center text-info">Resultado</h3>

            <?php if ($erro): ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($erro) ?></div>
            <?php elseif (isset($resultado['erro'])): ?>
                <div class="alert alert-danger mt-3"><?= nl2br(htmlspecialchars($resultado['erro'])) ?></div>
            <?php else: ?>
                <div class="alert alert-success mt-3">
                    <strong>Função inserida:</strong><br>
                    <div>\( <?= htmlspecialchars($expressao) ?> \)</div>
                    <hr>
                    <strong>Resultado Exato:</strong><br>
                    <div>\( <?= $resultado['resultado_exato'] ?> \)</div>
                    <?php if (isset($resultado['resultado_decimal'])): ?>
                        <strong>Aproximado:</strong><br>
                        <div>\( <?= $resultado['resultado_decimal'] ?> \)</div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
            <?php if (!empty($resultado['passos'])): ?>
                <div class="alert alert-info mt-4">
                    <strong>Passo a passo:</strong>
                    <ul>
                        <?php foreach ($resultado['passos'] as $passo): ?>
                            <li>\( <?= $passo ?> \)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>
</body>

</html>