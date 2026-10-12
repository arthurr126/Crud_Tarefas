<?php
require_once 'funcoes.php';

$nome = '';
$email = '';
$mensagem = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $mensagem = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Informe um e-mail válido.';
    } elseif (strlen($senha) < 6) {
        $mensagem = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif (email_existe($conn, $email)) {
        $mensagem = 'Este e-mail já está em uso.';
    } elseif (registro($nome, $email, $senha, $conn)) {
        $sucesso = true;
        $nome = $email = '';
    } else {
        $mensagem = 'Não foi possível concluir o cadastro. Tente novamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro · Organizador de tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="w-100 m-auto form-container">
        <div class="div_login">
            <h1 class="h3 mb-1 fw-semibold">Organizador de tarefas</h1>
            <h2 class="h6 mb-4 fw-normal text-secondary">Crie sua conta</h2>

            <?php if ($sucesso): ?>
                <div class="alert alert-success py-2" role="alert">
                    Cadastro concluído. <a href="login.php" class="alert-link">Entrar agora</a>
                </div>
            <?php elseif ($mensagem): ?>
                <div class="alert alert-danger py-2" role="alert"><?= e($mensagem) ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="form-floating mb-2">
                    <input id="nome" type="text" name="nome" value="<?= e($nome) ?>" class="form-control"
                           placeholder="Nome" autocomplete="name" required>
                    <label for="nome">Nome</label>
                </div>

                <div class="form-floating mb-2">
                    <input id="email" type="email" name="email" value="<?= e($email) ?>" class="form-control"
                           placeholder="E-mail" autocomplete="email" required>
                    <label for="email">E-mail</label>
                </div>

                <div class="form-floating mb-3">
                    <input id="senha" type="password" name="senha" class="form-control"
                           placeholder="Senha" autocomplete="new-password" minlength="6" required>
                    <label for="senha">Senha (mínimo 6 caracteres)</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Criar conta</button>
            </form>
        </div>

        <p class="text-center mt-4 mb-0 text-secondary">
            Já tem conta? <a href="login.php">Entrar</a>
        </p>
    </main>
</body>
</html>
