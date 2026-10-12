<?php
require_once 'funcoes.php';
iniciar_sessao();


if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$mensagem = '';
$email    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';

    if ($email === '' || $senha === '') {
        $mensagem = 'Preencha o e-mail e a senha.';
    } elseif (login($email, $senha, $conn)) {
        header('Location: index.php');
        exit;
    } else {
        $mensagem = 'E-mail ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar · Organizador de tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="w-100 m-auto form-container">
        <div class="div_login">
            <h1 class="h3 mb-1 fw-semibold">Organizador de tarefas</h1>
            <h2 class="h6 mb-4 fw-normal text-secondary">Entre na sua conta</h2>

            <?php if ($mensagem): ?>
                <div class="alert alert-danger py-2" role="alert"><?= e($mensagem) ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="form-floating mb-2">
                    <input id="email" type="email" name="email" class="form-control" placeholder="E-mail"
                           value="<?= e($email) ?>" autocomplete="email" required>
                    <label for="email">E-mail</label>
                </div>

                <div class="form-floating mb-3">
                    <input id="senha" type="password" name="password" class="form-control" placeholder="Senha"
                           autocomplete="current-password" required>
                    <label for="senha">Senha</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Entrar</button>
            </form>
        </div>

        <p class="text-center mt-4 mb-0 text-secondary">
            Não tem conta? <a href="registro.php">Cadastre-se</a>
        </p>
    </main>
</body>
</html>
