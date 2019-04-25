<html>
<title>Cadastro de usuários</title>
<body>
<a href="inicio.php">Página Inicial</a><br><br>
Cadastro de usuário
<form method="post" action="cadastro_usuario.php">
    <input type="submit" name="action" value="Cadastrar vendedor">
    <br><br>Entre em contato com os vendedores<br>
    <input type="submit" name="action" value="Consultar vendedores"/>
</form>

<?php

$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING);
$atualizar = filter_input(INPUT_GET, 'AtualizarID', FILTER_SANITIZE_STRING);
$deletar = filter_input(INPUT_GET, 'DeletarID', FILTER_SANITIZE_STRING);

$pdo = new PDO("mysql:host=localhost:3306;
                    dbname=modelo;charset=latin1",
    'root', '');

if ($action == "Cadastrar vendedor") {
    echo '<form method="post">
    Nome: <input type="text" name="nome" /><br>
    Sobrenome: <input type="text" name="sobrenome" /><br>
    CPF: <input type="text" name="cpf"/><br>
    Senha: <input type="password" name="senha"/><br>
    Telefone: <input type="text" name="telefone"/><br>
    Email: <input type="email" name="email"/><br>

    <input type="submit" name="action" value="Enviar" />';
}

if ($action == "Enviar") {
    $senhaAberta = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

    if (!is_null($senhaAberta)) {
        $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
        $sobrenome = filter_input(INPUT_POST, "sobrenome", FILTER_SANITIZE_STRING);
        $cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

        $senhaCadastrar = password_hash($senhaAberta, PASSWORD_DEFAULT);

        $inserir = "INSERT INTO modelo.vendedor(nome, sobrenome, email, telefone, senha, cpf) 
            VALUES ('$nome', '$sobrenome', '$email', '$telefone', '$senhaCadastrar', '$cpf')";


        $pdo->exec($inserir);

    }

}

if ($action == "Consultar vendedores") {
    $consulta = $pdo->query('SELECT * FROM vendedor');
    $userArray = $consulta->fetchAll(PDO::FETCH_ASSOC);
    foreach ($userArray as $user) {
        echo '<br>Nome: ' . $user['nome'] . ' ' . $user['sobrenome'] .
            '<br>E-mail ' . $user['email'] .
            '<br>Telefone: ' . $user['telefone'] .
            '<br>Cpf: ' . $user['cpf'] . '<br>' .
            '<a href="cadastro_usuario.php?AtualizarID=' . $user['cpf'] . '">Atualizar</a>  ' .
            '<a href="cadastro_usuario.php?DeletarID=' . $user['cpf'] . '">Deletar</a><br><br>';
    }
}

if (!empty($deletar) && empty($atualizar) && empty($action)) {

    $delete = "DELETE FROM vendedor WHERE cpf ='$deletar';";

    $pdo->exec($delete);

    echo "<br><br>Cadastro excluído";
}

if (empty($deletar) && !empty($atualizar) && empty($action)) {
    $consulta = $pdo->query("SELECT * FROM vendedor WHERE cpf = '$atualizar';");
    $user = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if (is_array($user) && count($user) > 0) {
        $nome = $user[0]['nome'];
        $sobrenome = $user[0]['sobrenome'];
        $cpf = $user[0]['cpf'];
        $email = $user[0]['email'];
        $telefone = $user[0]['telefone'];
    }

    echo '
    <form method="post">
    Nome: <input type="text" name="nome" value="' . $nome . '"/><br>
    Sobrenome: <input type="text" name="sobrenome" value="' . $sobrenome . '"/><br>
    CPF: <input type="text" name="cpf" value="' . $cpf . '"/><br>
    Telefone: <input type="text" name="telefone" value="' . $telefone . '"/><br>
    Email: <input type="email" name="email" value="' . $email . '"/><br>
    Senha: <input type="password" name="senha"/><br>
        <input type="reset" value="Recomeçar"><br>
        <input type="submit" name="action" value="Atualizar">
    </form>';
}

if (!empty($atualizar) && $action == "Atualizar") {
    $consulta = $pdo->query("SELECT * FROM vendedor WHERE cpf = '$atualizar';");
    $user = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $senhaAberta = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

    $senha = $user[0]['senha'];

    $senhaCadastrada = password_hash($senhaAberta, PASSWORD_DEFAULT);

    $logou = password_verify($senhaAberta, $senha);

    if ($logou) {


        $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
        $sobrenome = filter_input(INPUT_POST, "sobrenome", FILTER_SANITIZE_STRING);
        $cpf = filter_input(INPUT_POST, "cpf", FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

        $update = "UPDATE vendedor SET nome='$nome', sobrenome='$sobrenome', email='$email', telefone='$telefone', cpf='$cpf' WHERE cpf='$atualizar';";

        $pdo->exec($update);

        echo "<br><br><br> Atualização concluída";
    }
}

?>
</body>
</html>