<?php

include "repositorio.php";
include "girComum.php";

$funcao = $_POST["funcao"];

if ($funcao == 'gravaBanco') {
    call_user_func($funcao);
}

if ($funcao == 'recuperaBanco') {
    call_user_func($funcao);
}

if ($funcao == 'excluirBanco') {
    call_user_func($funcao);
}

return;

function gravaBanco()
{

    $reposit = new reposit(); //Abre a conexão.

    //Verifica permissões
    // $possuiPermissao = $reposit->PossuiPermissao("VALETRANSPORTEUNITARIO_ACESSAR|VALETRANSPORTEUNITARIO_GRAVAR");

    // if ($possuiPermissao === 0) {
    //     $mensagem = "O usuário não tem permissão para gravar!";
    //     echo "failed#" . $mensagem . ' ';
    //     return;
    // }
    session_start();
    $reposit = new reposit();
    $usuario = $_SESSION['login'];
    $banco = $_POST['banco'];
    $codigo =  validaCodigo($banco['codigo']?: 0);
    $codigoBanco = validaString($banco['codigoBanco']);
    $nomeBanco = validaString($banco['nomeBanco']);


    $sql = "dbo.banco_Atualiza(
        $codigo,
        $codigoBanco,	
        $nomeBanco,
        $usuario
        )";

    $reposit = new reposit();
    $result = $reposit->Execprocedure($sql);

     
 
    $ret = 'sucess#';
    if ($result < 1) {
        $ret = 'failed#';
    }
    echo $ret;
    return;
}


function recuperaBanco()
{
    if ((empty($_POST["id"])) || (!isset($_POST["id"])) || (is_null($_POST["id"]))) {
        $mensagem = "Nenhum parâmetro de pesquisa foi informado.";
        echo "failed#" . $mensagem . ' ';
        return;
    } else {
        $id = +$_POST["id"];
    }

    $sql = "SELECT codigo, codigoBanco, nomeBanco FROM dbo.banco WHERE (0=0) AND codigo = " . $id;


    $reposit = new reposit();
    $result = $reposit->RunQuery($sql);

    $out = "";
    if (($row = odbc_fetch_array($result)))
        $row = array_map('utf8_encode', $row);
    $codigo = $row['codigo'];
    $codigoBanco = $row['codigoBanco']; 
    $nomeBanco = $row['nomeBanco']; 

    $out =   $codigo . "^" .
        $codigoBanco . "^" .
        $nomeBanco ;

    if ($out == "") {
        echo "failed#";
        return;
    }

    echo "sucess#" . $out;
    return;
}


function excluirBanco() {

    $reposit = new reposit(); 

    $id = $_POST["id"];

    if ((empty($_POST['id']) || (!isset($_POST['id'])) || (is_null($_POST['id'])))) {
        $mensagem = "Selecione um banco.";
        echo "failed#" . $mensagem . ' ';
        return;
    }
  
    $sql = "banco_Deleta ($id)";

    $reposit = new reposit();
    $result = $reposit->Execprocedure($sql);

    if ($result < 1) {
        echo('failed#');
        return;
    }

    echo 'sucess#' . $result;
    return;
}


function validaString($value)
{
    $null = 'NULL';
    if ($value == '')
        return $null;
    return '\'' . $value . '\'';
}

function validaNumero($value)
{
    if ($value == "") {
        $value = 'NULL';
    }
    return $value;
}
function validaCodigo($value)
{
    return $value;
}
function validaData($value)
{
    if($value == ""){
        $value = 'NULL';
        return $value;
    }
    $value = str_replace('/', '-', $value);
    $value = date("Y-m-d", strtotime($value));
    $value = "'" . $value . "'";
    return $value;
}

function validaDataRecupera($value)
{   
    if($value == ""){
        $value = '';
        return $value;
    }
    $value = date('d/m/Y', strtotime($value));
    return $value;
}
function validaVerifica($value)
{
    if ($value == "") {
        $value = NULL;
    }
    return $value;
}
