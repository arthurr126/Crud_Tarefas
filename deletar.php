<?php
require_once 'funcoes.php';
$usuario_id = exigir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: index.php');
    exit;
}
csrf_validar();

excluir_tarefa($conn, (int) $_POST['id'], $usuario_id);

header('Location: index.php?excluida=1');
exit;
