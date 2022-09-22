<?php

include "repositorio.php";
include "girComum.php";

$funcao = $_POST["funcao"];

if ($funcao == 'gravaGenero') {
    call_user_func($funcao);
}

if ($funcao == 'recuperaGenero') {
    call_user_func($funcao);
}

if ($funcao == 'excluirGenero') {
    call_user_func($funcao);
}

if ($funcao == 'verificaGenero') {
    call_user_func($funcao);
}


function gravaGenero(){
    //Variáveis
    if ((empty($_POST['id'])) || (!isset($_POST['id'])) || (is_null($_POST['id']))) {
        $codigo = 0;
    } else {
        $codigo = (int) $_POST["id"];
    }
    $ativo = 1;
    session_start();

    
    $descricao = "'" . $_POST['genero'] . "'";

    $sql = "dbo.desc_generoatt
            $codigo,
            $descricao,
            $ativo
            ";

    $reposit = new reposit();
    $result = $reposit->Execprocedure($sql);

    $ret = 'sucess#';
    if ($result < 1) {
        $ret = 'failed#';
    }
    echo $ret;
    return;
}

function recuperaGenero(){
    $condicaoId = !((empty($_POST["id"])) || (!isset($_POST["id"])) || (is_null($_POST["id"])));
    // $condicaoLogin = !((empty($_POST["loginPesquisa"])) || (!isset($_POST["loginPesquisa"])) || (is_null($_POST["loginPesquisa"])));


    // if (($condicaoId === false) && ($condicaoLogin === false)) {
    //     $mensagem = "Nenhum parâmetro de pesquisa foi informado.";
    //     echo "failed#" . $mensagem . ' ';
    //     return;
    // }

    // if (($condicaoId === true) && ($condicaoLogin === true)) {
    //     $mensagem = "Somente 1 parâmetro de pesquisa deve ser informado.";
    //     echo "failed#" . $mensagem . ' ';
    //     return;
    // }

    if ($condicaoId) {
        $codigo = $_POST["id"];
    }

    // if ($condicaoLogin) {
    //     $loginPesquisa = $_POST["loginPesquisa"];
    // }
    $sql = "SELECT codigo, descricao from dbo.desc_genero where codigo = $codigo";
    

    $reposit = new reposit();
    $result = $reposit->RunQuery($sql);

    $out = "";

    if ($row = $result[0]) {
        $id = $row['codigo'];
        $genero = $row['descricao'];
        



        $out = $id. "^". $genero;

        if ($out == "") {
            echo "failed#";
        }
        if ($out != '') {
            echo "sucess#" . $out . " ";
        }
        return;
    }
}

function excluirGenero(){

    $reposit = new reposit();
    // $possuiPermissao = $reposit->PossuiPermissao("CARGO_ACESSAR|CARGO_EXCLUIR");
    // if ($possuiPermissao === 0) {
    //     $mensagem = "O usuário não tem permissão para excluir!";
    //     echo "failed#" . $mensagem . ' ';
    //     return;
    // }
    $id = $_POST["id"];
    if ((empty($_POST['id']) || (!isset($_POST['id'])) || (is_null($_POST['id'])))) {
        $mensagem = "Selecione um cargo para ser excluído";
        echo "failed#" . $mensagem . ' ';
        return;
    }

    $reposit = new reposit();

    $result = $reposit->update('dbo.desc_genero' . '|' . 'ativo = 0' . '|' . 'codigo =' . $id);

    if ($result < 1) {
        echo ('failed#');
        return;
    }
    echo 'sucess#' . $result;
    return;
}

function verificaGenero(){

    $id =  $_POST["id"];
    $genero = strtoupper($_POST['genero']);  

    if ($id) {
        $sql = "SELECT UPPER(genero) AS cont FROM dbo.desc_genero WHERE (0=0) AND genero = '$genero' AND codigo != $id";
        $reposit = new reposit();
        $result = $reposit->RunQuery($sql);

        if (($result)) {
            echo 'failed#';
            return;
        } else {
            echo  'succes#';
        }
    } else {
        $sql = "SELECT genero from dbo.desc_genero WHERE genero = '$genero' ";
        $reposit = new reposit();
        $result = $reposit->RunQuery($sql);

        if ($genero != "" && count($result) > 0) {
            echo 'failed#';
            return;
        } else {
            echo  'succes#';
        }
    }
}