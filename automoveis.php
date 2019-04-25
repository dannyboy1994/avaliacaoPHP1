<html>
<title>Cadastro de automóveis</title>
<body>
<a href="inicio.php">Página Inicial</a><br><br>
CADASTRO DE AUTOMÓVEIS
<br><br><br>
<form method="post" action="automoveis.php">
    <input type="submit" name="action" value="Cadastrar">
    <input type="submit" name="action" value="Consultar">
</form>
<?php
$pdo = new PDO("mysql:host=localhost:3306;
                    dbname=modelo;charset=latin1",
    'root', '');


$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$atualizar = filter_input(INPUT_GET, 'AtualizarID', FILTER_VALIDATE_INT);
$deletar = filter_input(INPUT_GET, 'DeletarID', FILTER_VALIDATE_INT);

if ($action == 'Cadastrar') {
    echo '
    <form method="post" action="automoveis.php">
    CPF do vendedor: <input type="text" name="cpf"/><br>
    Marca: <input type="text" name="marca"/><br>
    Modelo: <input type="text" name="modelo"/><br>
    Cor: <input type="text" name="cor"/><br>
    Ano: <input type="number" name="ano"/><br>
    Valor: <input type="number" name="valor"/><br>
        <input type="reset" value="Limpar"><br>
        <input type="submit" name="action" value="Enviar">
    </form>';

}

if ($action == 'Consultar') {
    $consulta = $pdo->query('SELECT * FROM carro');
    $autoArray = $consulta->fetchAll(PDO::FETCH_ASSOC);
    foreach ($autoArray as $auto) {
        echo '<br>Marca: ' . $auto['marca'] .
            '<br>Modelo: ' . $auto['modelo'] .
            '<br>Cor: ' . $auto['cor'] .
            '<br>Ano: ' . $auto['ano'] .
            '<br>Valor: R$ ' . $auto['valor'] .
            '<br>Cpf do Vendedor: ' . $auto['cpf_vendedor'] .
            '<br><a href="automoveis.php?AtualizarID=' . $auto['id_carro'] .'">Atualizar</a>  ' .
            '<a href="automoveis.php?DeletarID=' . $auto['id_carro'] .'">Deletar</a><br>' ;
    }
}

if (!empty($deletar) && empty($atualizar) && empty($action)) {
    $delete = "DELETE FROM carro WHERE id_carro = $deletar;";

    echo "query $delete";

    $pdo->exec($delete);

    echo "<br><br>Cadastro excluído";
}

if (empty($deletar) && !empty($atualizar) && empty($action)) {
    $consulta = $pdo->query("SELECT * FROM carro WHERE id_carro = $atualizar;");
    $carro = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if (is_array($carro) && count($carro) > 0) {
        $cpf = $carro[0]['cpf_vendedor'];
        $marca = $carro[0]['marca'];
        $modelo = $carro[0]['modelo'];
        $cor = $carro[0]['cor'];
        $ano = $carro[0]['ano'];
        $valor = $carro[0]['valor'];
    }

    echo '
    <form method="post">
    CPF do vendedor: ' . $cpf . ' <br>
    Marca: <input type="text" name="marca" value="' . $marca . '"/><br>
    Modelo: <input type="text" name="modelo" value="' . $modelo . '"/><br>
    Cor: <input type="text" name="cor" value="' . $cor . '"/><br>
    Ano: <input type="number" name="ano" value="' . $ano . '"/><br>
    Valor: <input type="number" name="valor" value="' . $valor . '"/><br>
        <input type="reset" value="Recomeçar"><br>
        <input type="submit" name="action" value="Atualizar">
    </form>';
}

if ($action == "Enviar") {

    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $cor = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);



    $insert = "INSERT INTO modelo.carro(cpf_vendedor, marca, modelo, cor, ano, valor)
                    VALUES('$cpf', '$marca', '$modelo', '$cor', $ano, $valor);";

    $pdo->exec($insert);

    echo "<br><br><br> Cadastro realizado";
}

if (!empty($atualizar) && $action == "Atualizar") {

    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $cor = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

    $update = "UPDATE carro SET marca='$marca', modelo='$modelo', cor='$cor', ano=$ano, valor=$valor WHERE id_carro=$atualizar";

    $pdo->exec($update);

    echo "<br><br><br> Atualização concluída";
}


?>
</body>
</html>