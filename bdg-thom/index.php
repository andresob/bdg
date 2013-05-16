<?php

function findFiles($diretorio){

    //apaga os arquivos e pastas, se ja foram setados
    $arquivos = $pastas = array();

    // abre o diretório
    if(!file_exists($diretorio)) return array();
    $ponteiro  = opendir($diretorio);

    // monta os vetores com os itens encontrados na pasta
    while ($nome_itens = readdir($ponteiro))
        $itens[] = $nome_itens;


    // percorre o vetor para fazer a separacao entre arquivos e pastas
    foreach ($itens as $listar){
        // retira "./" e "../" para que retorne apenas pastas e arquivos
        if ($listar!="." && $listar!=".." && $listar!=".DS_Store"){
            if (is_dir($listar)) $pastas[] = $listar;
            else $arquivos[] = $listar;
        }
    }
    $arr['arquivos'] = $arquivos;
    $arr['pastas'] = $pastas;
    //return $arr;
    return $arquivos;
}

$diretorio = dirname(__FILE__)."/lib/database";
$arr = findFiles($diretorio);
if(isset($_POST['sgbd'])){
    session_start();
    $_SESSION['sgbd'] = $_POST['sgbd'];
    $sgbd = $_SESSION['sgbd'];
    
    $filename = "$diretorio/$sgbd/connection.php";
    if(file_exists($filename)){
        require_once $filename;
        if(function_exists("installView")) installView();
        echo "<meta http-equiv='refresh' content='0;URL=praticando.php?sgbd=$sgbd' />";
    }
    require_once 'praticando.php';
    die();
}

?>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<h2>Selecione o banco de dados que você deseja se conectar</h2>
<form method='post' action=''>
    <select name="sgbd">
    <?php
    foreach($arr as $name){
        echo "<option value='$name'>".ucfirst($name)."</option>";
    }
    ?>
    </select>
    <br/>
    <input type="submit" value="Enviar" />
</form>