<html>
<title>Cadastro de imóveis</title>
<body>
<a href="inicio.php">Página Inicial</a><br><br>
CADASTRO DE IMÓVEIS
<br><br><br>
<form method="post" action="imoveis.php">
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
    <form method="post" action="imoveis.php">
    CPF do vendedor: <input type="text" name="cpf_vendedor"/><br>
    Quitinete: <input type="radio" name="tipo" value="Quitinete" /><br>
    Apartamento: <input type="radio" name="tipo" value="Apartamento" /><br>
    Casa: <input type="radio" name="tipo" value="Casa" /><br>
    Chácara: <input type="radio" name="tipo" value="Chácara" /><br>
    Endereço: <input type="text" name="endereco" /><br>
    Área: <input type="number" name="area" />m² <br>
    Dormitórios: <input type="number" name="dormitorios"/><br>
    Banheiros: <input type="number" name="banheiros"/><br>
    Vagas para carro: <input type="number" name="vagas"/><br>
    Valor: <input type="number" name="valor"/><br>
    <input type="reset" value="Limpar"><br>
    <input type="submit" name="action" value="Enviar">
    </form>';

}

if ($action == "Enviar") {
    $cpf_vendedor = filter_input(INPUT_POST, 'cpf_vendedor', FILTER_SANITIZE_STRING);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $area = filter_input(INPUT_POST, 'area', FILTER_SANITIZE_NUMBER_FLOAT);
    $dormitorios = filter_input(INPUT_POST, 'dormitorios', FILTER_VALIDATE_INT);
    $banheiros = filter_input(INPUT_POST, 'banheiros', FILTER_VALIDATE_INT);
    $vagas = filter_input(INPUT_POST, 'vagas', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

    $insert = "INSERT INTO modelo.imovel(cpf_vendedor, tipo, endereco, area, dormitorios, banheiros, vagas, valor)
                    VALUES('$cpf_vendedor', '$tipo', '$endereco', $area, $dormitorios, $banheiros, $vagas, $valor);";

    $pdo->exec($insert);


    echo "<br><br><br> Cadastro realizado";
}

if ($action == 'Consultar') {
    $consulta = $pdo->query('SELECT * FROM imovel');
    $autoArray = $consulta->fetchAll(PDO::FETCH_ASSOC);
    foreach ($autoArray as $auto) {
        echo '<br> CPF do vendedor: ' . $auto['cpf_vendedor'] .
            '<br>Tipo: ' . $auto['tipo'] .
            '<br>Endereco: ' . $auto['endereco'] .
            '<br>Area: ' . $auto['area'] . 'm²
            <br>Dormitórios: ' . $auto['dormitorios'] .
            '<br>Banheiros: ' . $auto['banheiros'] .
            '<br>Vagas para carros: ' . $auto['vagas'] .
            '<br>Valor: R$ ' . $auto['valor'].
            '<br><a href="imoveis.php?AtualizarID=' . $auto['id_imovel'] . '">Atualizar</a>  ' .
            '<a href="imoveis.php?DeletarID=' . $auto['id_imovel'] . '">Deletar</a><br><br>';;
    }
}

if (!empty($deletar) && empty($atualizar) && empty($action)) {
    $delete = "DELETE FROM imovel WHERE id_imovel = $deletar;";

    $pdo->exec($delete);

    echo "<br><br>Cadastro excluído";
}

if (empty($deletar) && !empty($atualizar) && empty($action)) {
    $consulta = $pdo->query("SELECT * FROM imovel WHERE id_imovel = $atualizar;");
    $imovel = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if (is_array($imovel) && count($imovel) > 0) {
        $cpf = $imovel[0]['cpf_vendedor'];
        $tipo = $imovel[0]['tipo'];
        $endereco = $imovel[0]['endereco'];
        $area = $imovel[0]['area'];
        $dormitorios = $imovel[0]['dormitorios'];
        $banheiros = $imovel[0]['banheiros'];
        $vagas = $imovel[0]['vagas'];
        $valor = $imovel[0]['valor'];
    }

    echo '<form method="post">
    CPF do vendedor:' . $cpf .'<br>
    Tipo: ' . $tipo .'<br><br><br><br>Tipo:<br>
    Quitinete: <input type="radio" name="tipo" value="Quitinete" /><br>
    Apartamento: <input type="radio" name="tipo" value="Apartamento" /><br>
    Casa: <input type="radio" name="tipo" value="Casa" /><br>
    Chácara: <input type="radio" name="tipo" value="Chácara" /><br>
    Endereço: <input type="text" name="endereco" value="' .$endereco. '"/><br>
    Área: <input type="number" name="area" value="' .$area. '"/>m² <br>
    Dormitórios: <input type="number" name="dormitorios" value="' .$dormitorios. '"/><br>
    Banheiros: <input type="number" name="banheiros" value="' .$banheiros. '"/><br>
    Vagas para carro: <input type="number" name="vagas" value="' .$vagas. '"/><br>
    Valor: <input type="number" name="valor" value="' .$valor. '"/><br>
    <input type="reset" value="Recomeçar"><br>
    <input type="submit" name="action" value="Atualizar">
    </form>';
}

if (!empty($atualizar) && $action == "Atualizar") {

    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
    $area = filter_input(INPUT_POST, 'area', FILTER_SANITIZE_NUMBER_FLOAT);
    $dormitorios = filter_input(INPUT_POST, 'dormitorios', FILTER_VALIDATE_INT);
    $banheiros = filter_input(INPUT_POST, 'banheiros', FILTER_VALIDATE_INT);
    $vagas = filter_input(INPUT_POST, 'vagas', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

    $update = "UPDATE imovel SET tipo='$tipo', endereco='$endereco', area=$area, dormitorios='$dormitorios', banheiros=$banheiros, vagas=$vagas, valor=$valor WHERE id_imovel=$atualizar;";

    $pdo->exec($update);

    echo "<br><br><br> Atualização concluída";
}

?>
</body>
</html>