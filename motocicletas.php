<html>
<title>Cadastro de motocicletas</title>
<body>
<a href="inicio.php">Página Inicial</a><br><br>
CADASTRO DE MOTOCICLETAS
<br><br><br>
<form method="post" action="motocicletas.php">
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
    <form method="post" action="motocicletas.php">
        CPF do vendedor: <input type="text" name="cpf"/><br>
        Marca: <input type="text" name="marca"/><br>
        Modelo: <input type="text" name="modelo"/><br>
        Cilindradas: <input type="number" name="cilindradas"/><br>
        Cor: <input type="text" name="cor"/><br>
        Ano: <input type="number" name="ano"/><br>
        Valor: <input type="number" name="valor"/><br>
        <input type="reset" value="Limpar"><br>
        <input type="submit" name="action" value="Enviar">
    </form>';


}

if ($action == 'Consultar') {
    $consulta = $pdo->query('SELECT * FROM moto');
    $autoArray = $consulta->fetchAll(PDO::FETCH_ASSOC);
    foreach ($autoArray as $auto) {
        echo 'Cpf do vendedor - ' . $auto['cpf_vendedor'].'<br>';
        echo 'Marca - ' . $auto['marca'] . '<br>';
        echo 'Modelo - ' . $auto['modelo'] . '<br>';
        echo 'Cilindradas - ' . $auto['cilindradas'] . '<br>';
        echo 'Cor - ' . $auto['cor'] . '<br> ';
        echo 'Ano - ' . $auto['ano'] . '<br>';
        echo 'Valor - R$' . $auto['valor'] .
            '<br><a href="motocicletas.php?AtualizarID=' . $auto['id_moto'] . '">Atualizar</a>  ' .
            '<a href="motocicletas.php?DeletarID=' . $auto['id_moto'] . '">Deletar</a><br><br>';
    }
}

if (!empty($deletar) && empty($atualizar) && empty($action)) {

    $delete = "DELETE FROM moto WHERE id_moto = $deletar;";

    $pdo->exec($delete);

    echo "<br><br>Cadastro excluído";
}

if (empty($deletar) && !empty($atualizar) && empty($action)) {
    $consulta = $pdo->query("SELECT * FROM moto WHERE id_moto = $atualizar;");
    $moto = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if (is_array($moto) && count($moto) > 0) {
        $cpf = $moto[0]['cpf_vendedor'];
        $marca = $moto[0]['marca'];
        $modelo = $moto[0]['modelo'];
        $cilindradas = $moto[0]['cilindradas'];
        $cor = $moto[0]['cor'];
        $ano = $moto[0]['ano'];
        $valor = $moto[0]['valor'];
    }

    echo '
    <form method="post">
    CPF do vendedor: ' . $cpf . ' <br>
    Marca: <input type="text" name="marca" value="' . $marca . '"/><br>
    Modelo: <input type="text" name="modelo" value="' . $modelo . '"/><br>
    Cilindradas: <input type="number" name="cilindradas" value="' . $cilindradas . '"/><br>
    Cor: <input type="text" name="cor" value="' . $cor . '"/><br>
    Ano: <input type="number" name="ano" value="' . $ano . '"/><br>
    Valor: <input type="number" name="valor" value="' . $valor . '"/><br>
        <input type="reset" value="Recomeçar"><br>
        <input type="submit" name="action" value="Atualizar">
    </form>';
}

if (!empty($atualizar) && $action == "Atualizar") {

    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $cilindradas = filter_input(INPUT_POST, 'cilindradas', FILTER_VALIDATE_INT);
    $cor = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

    $update = "UPDATE moto SET marca='$marca', modelo='$modelo', cilindradas=$cilindradas, cor='$cor', ano=$ano, valor=$valor WHERE id_moto=$atualizar;";

    $pdo->exec($update);

    echo "<br><br><br> Atualização concluída";
}

if ($action == "Enviar") {

    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
    $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
    $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
    $cilindradas = filter_input(INPUT_POST, 'cilindradas', FILTER_VALIDATE_INT);
    $cor = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);


    $insert = "INSERT INTO modelo.moto(cpf_vendedor, marca, modelo, cilindradas, cor, ano, valor)
                    VALUES('$cpf', '$marca', '$modelo', $cilindradas, '$cor', $ano, $valor);";

    $pdo->exec($insert);

    echo "<br><br><br> Cadastro realizado";

}

?>
</body>
</html>