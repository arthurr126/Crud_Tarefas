<?php
require_once 'funcoes.php';
$usuario_id = exigir_login();

$resultado = listar_t($conn, $usuario_id);
$nome      = $_SESSION['usuario_nome'] ?? '';

$cores_status = [
    'PENDENTE'     => 'text-bg-warning',
    'EM ANDAMENTO' => 'text-bg-info',
    'CONCLUÍDA'    => 'text-bg-success',
    'CANCELADA'    => 'text-bg-secondary',
];
$rotulos_prioridade = ['BAIXA' => 'Baixa', 'MEDIA' => 'Média', 'ALTA' => 'Alta'];

$avisos = [
    'sucesso'  => 'Tarefa criada.',
    'editada'  => 'Tarefa atualizada.',
    'excluida' => 'Tarefa excluída.',
];
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas tarefas · Organizador de tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>

<main class="container my-4">

    <header class="topo d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-0">Minhas tarefas</h1>
            <small class="text-secondary">Olá, <?= e($nome) ?></small>
        </div>
        <div class="d-flex gap-2">
            <a href="criar_t.php" class="btn btn-primary">＋ Nova tarefa</a>
            <a href="logout.php" class="btn btn-outline-secondary">Sair</a>
        </div>
    </header>

    <?php foreach ($avisos as $chave => $texto): ?>
        <?php if (isset($_GET[$chave])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= e($texto) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($resultado->num_rows === 0): ?>
        <div class="vazio text-center py-5">
            <p class="fs-5 mb-3">Você ainda não tem tarefas.</p>
            <a href="criar_t.php" class="btn btn-primary">Criar a primeira tarefa</a>
        </div>
    <?php else: ?>
        <section class="grade-tarefas">
            <?php while ($tarefa = $resultado->fetch_assoc()): ?>
                <article class="card tarefa prio-borda-<?= strtolower(e($tarefa['prioridade'])) ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <h2 class="h5 mb-0"><?= e($tarefa['titulo']) ?></h2>
                            <span class="badge <?= $cores_status[$tarefa['status']] ?? 'text-bg-secondary' ?>">
                                <?= e($tarefa['status']) ?>
                            </span>
                        </div>

                        <p class="text-secondary small mb-2">
                            Prioridade <?= e($rotulos_prioridade[$tarefa['prioridade']] ?? $tarefa['prioridade']) ?>
                        </p>

                        <?php if (!empty($tarefa['descricao'])): ?>
                            <p class="card-text"><?= nl2br(e($tarefa['descricao'])) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <small class="text-secondary">
                            📅 <?= $tarefa['data_tarefa'] ? date('d/m/Y', strtotime($tarefa['data_tarefa'])) : 'Sem data' ?>
                            <?php if ($tarefa['hora_tarefa']): ?>
                                · 🕑 <?= e(substr($tarefa['hora_tarefa'], 0, 5)) ?>
                            <?php endif; ?>
                        </small>

                        <div class="d-flex gap-2">
                            <a href="editar_tarefa.php?id=<?= (int) $tarefa['id'] ?>"
                               class="btn btn-outline-primary btn-sm">Editar</a>

                            <form action="deletar.php" method="POST"
                                  onsubmit="return confirm('Excluir esta tarefa?');">
                                <?= csrf_campo() ?>
                                <input type="hidden" name="id" value="<?= (int) $tarefa['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Excluir</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </section>
    <?php endif; ?>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>
