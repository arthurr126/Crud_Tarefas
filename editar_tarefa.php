<?php
require_once 'funcoes.php';
$usuario_id = exigir_login();

$id = (int) ($_GET['id'] ?? 0);
$tarefa = $id > 0 ? buscar_tarefa($conn, $usuario_id, $id) : null;

if (!$tarefa) {
    http_response_code(404);
    die('Tarefa não encontrada. <a href="index.php">Voltar</a>');
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    [$dados, $erros] = validar_tarefa($_POST);

    if (!$erros) {

        atualizar_tarefa(
            $conn,
            $id,
            $usuario_id,
            $dados['titulo'],
            $dados['descricao'],
            $dados['data_tarefa'],
            $dados['hora_tarefa'],
            $dados['prioridade'],
            $dados['status']
        );
        header('Location: index.php?editada=1');
        exit;
    }
    $tarefa = array_merge($tarefa, $dados);
}

$titulo_pagina = 'Editar tarefa';
$texto_botao   = 'Salvar alterações';
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo_pagina) ?> · Organizador de tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>
    <main class="container pagina-estreita my-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0"><?= e($titulo_pagina) ?></h1>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">← Voltar</a>
        </header>

        <?php require '_form_tarefa.php'; ?>
    </main>
</body>
</html>
