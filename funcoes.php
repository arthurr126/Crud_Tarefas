<?php
require_once __DIR__ . '/conexao.php';

const PRIORIDADES    = ['BAIXA', 'MEDIA', 'ALTA'];
const STATUS_VALIDOS = ['PENDENTE', 'EM ANDAMENTO', 'CONCLUÍDA', 'CANCELADA'];

function iniciar_sessao(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function exigir_login(): int
{
    iniciar_sessao();
    if (empty($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
    return (int) $_SESSION['usuario_id'];
}

function valor_ou_null($valor): ?string
{
    $valor = trim((string) $valor);
    return $valor === '' ? null : $valor;
}



function csrf_token(): string
{
    iniciar_sessao();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_validar(): void
{
    iniciar_sessao();
    $enviado = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $enviado)) {
        http_response_code(403);
        die('Requisição inválida. Volte e tente novamente.');
    }
}



function email_existe(mysqli $conn, string $email): bool
{
    $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $existe = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $existe;
}

function registro($nome, $email, $senha, $conn): bool
{
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nome, $email, $senhaHash);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    } catch (mysqli_sql_exception $e) {
        return false; 
    }
}

function login($email, $senha, $conn): bool
{
    iniciar_sessao();

    $stmt = $conn->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario   = $resultado->fetch_assoc();
    $stmt->close();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true); // evita fixação de sessão
        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        return true;
    }
    return false;
}


function validar_tarefa(array $post): array
{
    $dados = [
        'titulo'      => trim($post['titulo'] ?? ''),
        'descricao'   => trim($post['descricao'] ?? ''),
        'data_tarefa' => valor_ou_null($post['data_tarefa'] ?? ''),
        'hora_tarefa' => valor_ou_null($post['hora_tarefa'] ?? ''),
        'prioridade'  => $post['prioridade'] ?? '',
        'status'      => $post['status'] ?? '',
    ];
    $erros = [];

    if ($dados['titulo'] === '') {
        $erros[] = 'Informe o título da tarefa.';
    } elseif (mb_strlen($dados['titulo']) > 150) {
        $erros[] = 'O título pode ter no máximo 150 caracteres.';
    }
    if (!in_array($dados['prioridade'], PRIORIDADES, true)) {
        $erros[] = 'Escolha uma prioridade.';
    }
    if (!in_array($dados['status'], STATUS_VALIDOS, true)) {
        $erros[] = 'Escolha um status.';
    }
    if ($dados['data_tarefa'] !== null && !DateTime::createFromFormat('Y-m-d', $dados['data_tarefa'])) {
        $erros[] = 'Data inválida.';
    }
    if ($dados['hora_tarefa'] !== null && !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $dados['hora_tarefa'])) {
        $erros[] = 'Hora inválida.';
    }

    return [$dados, $erros];
}

function criar_t($usuario_id, $titulo, $descricao, $data, $hora, $prioridade, $status, $conn): bool
{
    $stmt = $conn->prepare(
        'INSERT INTO tarefas (usuario_id, titulo, descricao, data_tarefa, hora_tarefa, prioridade, status)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('issssss', $usuario_id, $titulo, $descricao, $data, $hora, $prioridade, $status);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function listar_t($conn, $usuario_id)
{
    // Tarefas com data primeiro (da mais próxima para a mais distante); sem data por último
    $stmt = $conn->prepare(
        'SELECT id, titulo, descricao, data_tarefa, hora_tarefa, prioridade, status
         FROM tarefas
         WHERE usuario_id = ?
         ORDER BY data_tarefa IS NULL, data_tarefa, hora_tarefa, id DESC'
    );
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
    return $resultado;
}

function buscar_tarefa($conn, $usuario_id, $id): ?array
{
    $stmt = $conn->prepare('SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?');
    $stmt->bind_param('ii', $id, $usuario_id);
    $stmt->execute();
    $tarefa = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $tarefa ?: null;
}

function atualizar_tarefa($conn, $id, $usuario_id, $titulo, $descricao, $data, $hora, $prioridade, $status): bool
{
    $stmt = $conn->prepare(
        'UPDATE tarefas
         SET titulo = ?, descricao = ?, data_tarefa = ?, hora_tarefa = ?, prioridade = ?, status = ?
         WHERE id = ? AND usuario_id = ?'
    );
    $stmt->bind_param('ssssssii', $titulo, $descricao, $data, $hora, $prioridade, $status, $id, $usuario_id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function excluir_tarefa($conn, $id, $usuario_id): bool
{
    $stmt = $conn->prepare('DELETE FROM tarefas WHERE id = ? AND usuario_id = ?');
    $stmt->bind_param('ii', $id, $usuario_id);
    $stmt->execute();
    $apagou = $stmt->affected_rows > 0;
    $stmt->close();
    return $apagou;
}
