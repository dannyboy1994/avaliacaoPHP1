<html>
<title>Página inicial</title>
<body>
<form method="post">
    SITE DE VENDAS
    <BR>
    <BR>
    Faça Login no sistema <br>
    CPF do Usuário: <input type="text" name="cpf"/><br>
    Senha: <input type="password" name="senha"/><br>
    <input type="submit" name="Enviar" value="Enviar"/>
</form>

<a href="cadastro_usuario.php">Página de Usuários</a><br><br>ANUNCIE AQUI!<br>
<a href="automoveis.php">Página de Automóveis</a><br>
<a href="motocicletas.php">Página de Motocicletas</a><br>
<a href="imoveis.php">Página de Imóveis</a><br>

</body>
</html>

<?php
$pdo = new PDO("mysql:host=localhost:3306;
                    dbname=modelo;charset=latin1",
    'root', '');


$senhaAberta = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

if (!is_null($senhaAberta)) {
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_DEFAULT);

    $sql = "select * from modelo.vendedor where cpf = '$cpf';";

    $consulta = $pdo->query($sql);

    $usuario = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $senha = $usuario[0]['senha'];

    $senhaCadastrada = password_hash($senhaAberta, PASSWORD_DEFAULT);

    $logou = password_verify($senhaAberta, $senha);

    if ($logou) {
        session_start();
        echo 'Senha válida!';
    } else {
        echo 'Senha inválida';
    }
}

?>