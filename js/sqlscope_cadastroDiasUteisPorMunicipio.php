<?php

include "repositorio.php";
include "girComum.php";

$funcao = $_POST["funcao"];

if ($funcao == 'grava') {
    call_user_func($funcao);
}

if ($funcao == 'recupera') {
    call_user_func($funcao);
}

if ($funcao == 'excluir') {
    call_user_func($funcao);
}

if ($funcao == 'listaComboMunicipio') {
    call_user_func($funcao);
}


return;

function grava()
{

    $reposit = new reposit(); //Abre a conexão.

    //Verifica permissões
    $possuiPermissao = $reposit->PossuiPermissao("DIASUTEISPORMUNICIPIO_ACESSAR|DIASUTEISPORMUNICIPIO_GRAVAR");

    if ($possuiPermissao === 0) {
        $mensagem = "O usuário não tem permissão para gravar!";
        echo "failed#" . $mensagem . ' ';
        return;
    }

    $reposit = new reposit();

    //Atributos de dias úteis por município
    if ((empty($_POST['id'])) || (!isset($_POST['id'])) || (is_null($_POST['id']))) {
        $id = 0;
    } else {
        $id = formatarNumero((int) $_POST["id"]);
    }

    if ((empty($_POST['ativo'])) || (!isset($_POST['ativo'])) || (is_null($_POST['ativo']))) {
        $ativo = 0;
    } else {
        $ativo = formatarNumero((int) $_POST["ativo"]);
    }

    session_start();
    $usuario = formatarString($_SESSION['login']);  //Pegando o nome do usuário mantido pela sessão.
    $unidadeFederacao = formatarString($_POST['unidadeFederacao']);
    $municipio = formatarNumero($_POST['municipio']);
    $cidade = formatarString($_POST['cidade']);

    //Quantidades de dias do mês.  Todos são números.
    $quantidadeDiaJaneiro = formatarNumero((int) $_POST['qtdDiasJaneiro']);
    $quantidadeDiaFevereiro = formatarNumero((int) $_POST['qtdDiasFevereiro']);
    $quantidadeDiaMarco = formatarNumero((int) $_POST['qtdDiasMarco']);
    $quantidadeDiaAbril = formatarNumero((int) $_POST['qtdDiasAbril']);
    $quantidadeDiaMaio = formatarNumero((int) $_POST['qtdDiasMaio']);
    $quantidadeDiaJunho = formatarNumero((int) $_POST['qtdDiasJunho']);
    $quantidadeDiaJulho = formatarNumero((int) $_POST['qtdDiasJulho']);
    $quantidadeDiaAgosto = formatarNumero((int) $_POST['qtdDiasAgosto']);
    $quantidadeDiaSetembro = formatarNumero((int) $_POST['qtdDiasSetembro']);
    $quantidadeDiaOutubro = formatarNumero((int) $_POST['qtdDiasOutubro']);
    $quantidadeDiaNovembro = formatarNumero((int) $_POST['qtdDiasNovembro']);
    $quantidadeDiaDezembro = formatarNumero((int) $_POST['qtdDiasDezembro']);


    $sql = "Ntl.diasUteisPorMunicipio_Atualiza " . $id . "," .
        $ativo . "," .
        $unidadeFederacao . "," .
        $municipio .  "," .
        $cidade . "," .
        $quantidadeDiaJaneiro . "," .
        $quantidadeDiaFevereiro . "," .
        $quantidadeDiaMarco .  "," .
        $quantidadeDiaAbril . "," .
        $quantidadeDiaMaio . "," .
        $quantidadeDiaJunho . "," .
        $quantidadeDiaJulho . "," .
        $quantidadeDiaAgosto . "," .
        $quantidadeDiaSetembro . "," .
        $quantidadeDiaOutubro . "," .
        $quantidadeDiaNovembro  . "," .
        $quantidadeDiaDezembro . "," .
        $usuario . " ";

    $reposit = new reposit();
    $result = $reposit->Execprocedure($sql);

    $ret = 'sucess#';
    if ($result < 1) {
        $ret = 'failed#';
    }
    echo $ret;
    return;
}

function recupera()
{
    $condicaoId = $_POST['id'];
    $condicaoLogin = !((empty($_POST["loginPesquisa"])) || (!isset($_POST["loginPesquisa"])) || (is_null($_POST["loginPesquisa"])));

    if (($condicaoId === false) && ($condicaoLogin === false)) {
        $mensagem = "Nenhum parâmetro de pesquisa foi informado.";
        echo "failed#" . $mensagem . ' ';
        return;
    }

    if (($condicaoId === true) && ($condicaoLogin === true)) {
        $mensagem = "Somente 1 parâmetro de pesquisa deve ser informado.";
        echo "failed#" . $mensagem . ' ';
        return;
    }

    if ($condicaoId) {
        $diasUteisPorMunicipioIdPesquisa = $_POST["id"];
    }

    if ($condicaoLogin) {
        $loginPesquisa = $_POST["loginPesquisa"];
    }

    $sql = " SELECT DM.*, M.descricao FROM Ntl.diasUteisPorMunicipio DM INNER JOIN 
    Ntl.municipio M ON DM.municipio = M.codigo WHERE (0=0) AND DM.codigo = " . $condicaoId;


    $reposit = new reposit();
    $result = $reposit->RunQuery($sql);

    $out = "";

    if($row = $result[0]) {
        $id = +$row['codigo'];
        $unidadeFederacao = $row['unidadeFederacao'];
        $municipio = $row['municipio'];
        $cidade = $row['cidade'];
        $ativo = +$row['ativo'];
        $quantidadeDiaJaneiro = +$row['quantidadeDiaJaneiro'];
        $quantidadeDiaFevereiro = +$row['quantidadeDiaFevereiro'];
        $quantidadeDiaMarco = +$row['quantidadeDiaMarco'];
        $quantidadeDiaAbril = +$row['quantidadeDiaAbril'];
        $quantidadeDiaMaio = +$row['quantidadeDiaMaio'];
        $quantidadeDiaJunho = +$row['quantidadeDiaJunho'];
        $quantidadeDiaJulho = +$row['quantidadeDiaJulho'];
        $quantidadeDiaAgosto = +$row['quantidadeDiaAgosto'];
        $quantidadeDiaSetembro = +$row['quantidadeDiaSetembro'];
        $quantidadeDiaOutubro = +$row['quantidadeDiaOutubro'];
        $quantidadeDiaNovembro = +$row['quantidadeDiaNovembro'];
        $quantidadeDiaDezembro = +$row['quantidadeDiaDezembro'];
        $nomeMunicipio = $row['descricao'];

        $out = $id . "^" .
            $unidadeFederacao . "^" .
            $municipio . "^" .
            $cidade . "^" .
            $ativo . "^" .
            $quantidadeDiaJaneiro . "^" .
            $quantidadeDiaFevereiro . "^" .
            $quantidadeDiaMarco . "^" .
            $quantidadeDiaAbril . "^" .
            $quantidadeDiaMaio . "^" .
            $quantidadeDiaJunho . "^" .
            $quantidadeDiaJulho . "^" .
            $quantidadeDiaAgosto . "^" .
            $quantidadeDiaSetembro . "^" .
            $quantidadeDiaOutubro . "^" .
            $quantidadeDiaNovembro . "^" .
            $quantidadeDiaDezembro . "^" .
            $nomeMunicipio;

        if ($out == "") {
            echo "failed#";
        }
        if ($out != '') {
            echo "sucess#" . $out . " ";
        }
        return;
    }
}

function excluir()
{

    $reposit = new reposit();
    $possuiPermissao = $reposit->PossuiPermissao("DIASUTEISPORMUNICIPIO_ACESSAR|DIASUTEISPORMUNICIPIO_EXCLUIR");

    if ($possuiPermissao === 0) {
        $mensagem = "O usuário não tem permissão para excluir!";
        echo "failed#" . $mensagem . ' ';
        return;
    }

    $id = $_POST["id"];

    if ((empty($_POST['id']) || (!isset($_POST['id'])) || (is_null($_POST['id'])))) {
        $mensagem = "Selecione um dia util por municipio.";
        echo "failed#" . $mensagem . ' ';
        return;
    }

    $reposit = new reposit();
    $result = $reposit->update('Ntl.diasUteisPorMunicipio' . '|' . 'ativo = 0' . '|' . 'codigo =' . $id);

    if ($result < 1) {
        echo ('failed#');
        return;
    }
    echo 'sucess#' . $result;
    return;
}
function listaComboMunicipio()
{
    $id = $_POST["codigo"];

    if ($id != "") {
        $sql = "SELECT * FROM Ntl.municipio WHERE (0 =0) AND  unidadeFederacao = '" . $id . "' AND ativo = 1";
    }

    $reposit = new reposit();
    $result = $reposit->RunQuery($sql);

    $out = "";
    $contador = 0;

    foreach($result as $row) {
        $id = $row['codigo'];
        $municipio = $row['descricao'];

        $out = $out . $id . "^" . $municipio . "|";
        $contador = $contador + 1;
    }
    if ($out == "") {
        echo "failed#0 ";
    }
    if ($out != '') {
        echo "sucess#" . $contador . "#" . $out;
    }
    return;
}

function formatarNumero($value)
{
    $aux = $value;
    $aux = str_replace('.', '', $aux);
    $aux = str_replace(',', '.', $aux);
    $aux = floatval($aux);
    if (!$aux) {
        $aux = 'null';
    }
    return $aux;
}

function formatarString($value)
{
    $aux = $value;
    $aux = str_replace("'", " ", $aux);
    if (!$aux) {
        return 'null';
    }
    $aux = '\'' . trim($aux) . '\'';
    return $aux;
}


function formatarData($value)
{
    $aux = $value;
    if (!$aux) {
        return 'null';
    }
    $aux = explode('/', $value);
    $data = $aux[2] . '-' . $aux[1] . '-' . $aux[0];
    $data = '\'' . trim($data) . '\'';
    return $data;
}

//Transforma uma data Y-M-D para D-M-Y. 
function formataDataRecuperacao($campo)
{
    $campo = explode("-", $campo);
    $diaCampo = explode(" ", $campo[2]);
    $campo = $diaCampo[0] . "/" . $campo[1] . "/" . $campo[0];
    return $campo;
}
