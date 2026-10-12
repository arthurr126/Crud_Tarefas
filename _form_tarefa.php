<?php

$t = $tarefa ?? [];
$prioridade_atual = $t['prioridade'] ?? 'MEDIA';
$status_atual     = $t['status'] ?? 'PENDENTE';
$hora_atual       = isset($t['hora_tarefa']) ? substr((string) $t['hora_tarefa'], 0, 5) : '';
?>

<?php if (!empty($erros)): ?>
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            <?php foreach ($erros as $erro): ?>
                <li><?= e($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="card shadow-sm form-tarefa">
    <div class="card-body">
        <?= csrf_campo() ?>

        <div class="mb-3">
            <label for="titulo" class="form-label">Título *</label>
            <input type="text" id="titulo" name="titulo" class="form-control" required maxlength="150"
                   placeholder="Ex.: Entregar relatório" value="<?= e($t['titulo'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="3"
                      placeholder="Detalhes da tarefa (opcional)"><?= e($t['descricao'] ?? '') ?></textarea>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label for="data_tarefa" class="form-label">Data</label>
                <input type="date" id="data_tarefa" name="data_tarefa" class="form-control"
                       value="<?= e($t['data_tarefa'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
                <label for="hora_tarefa" class="form-label">Hora</label>
                <input type="time" id="hora_tarefa" name="hora_tarefa" class="form-control"
                       value="<?= e($hora_atual) ?>">
            </div>
        </div>

        <fieldset class="mb-3">
            <legend class="form-label fs-6">Prioridade *</legend>
            <div class="btn-group w-100" role="group" aria-label="Prioridade">
                <?php foreach (['BAIXA' => 'Baixa', 'MEDIA' => 'Média', 'ALTA' => 'Alta'] as $valor => $rotulo): ?>
                    <input type="radio" class="btn-check" name="prioridade" id="prio<?= $valor ?>"
                           value="<?= $valor ?>" <?= $prioridade_atual === $valor ? 'checked' : '' ?>>
                    <label class="btn btn-outline-secondary prio-<?= strtolower($valor) ?>"
                           for="prio<?= $valor ?>"><?= $rotulo ?></label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <div class="mb-4">
            <label for="status" class="form-label">Status *</label>
            <select id="status" class="form-select" name="status" required>
                <?php foreach (STATUS_VALIDOS as $s): ?>
                    <option value="<?= e($s) ?>" <?= $status_atual === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><?= e($texto_botao) ?></button>
            <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</form>
