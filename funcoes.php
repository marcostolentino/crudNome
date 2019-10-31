<?

//PRINT_R PRE
function pr($dado, $print_r = true) {
    echo '<pre>';
    if ($print_r) {
        print_r($dado);
    } else {
        var_dump($dado);
    }
}

function pdo() {
    $PDO = new PDO('mysql:host=127.0.0.1;dbname=CRUD', 'root', '');
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $PDO;
}

function pessoaDados() {
    $pessoaDados = [
        ':NOME' => @$_POST['NOME'],
        ':UF' => @$_POST['UF'],
        ':OBSERVACAO' => @$_POST['OBSERVACAO']
    ];
    return $pessoaDados;
}

function pessoaIncluir($pessoaDados) {
    $PDO = pdo();
    $pesIncluir = $PDO->prepare('
            INSERT INTO PESSOA (NOME,   UF,  OBSERVACAO) 
                        VALUES (:NOME, :UF, :OBSERVACAO)
        ');
    return $pesIncluir->execute($pessoaDados);
}

function pessoaAlterar($pessoaDados, $ID_PESSOA) {
    $PDO = pdo();
    $pesAlterar = $PDO->prepare('
            UPDATE PESSOA SET NOME = :NOME, 
                                UF = :UF, 
                        OBSERVACAO = :OBSERVACAO 
                   WHERE ID_PESSOA = :ID_PESSOA
        ');
    $pessoaDados[':ID_PESSOA'] = $ID_PESSOA;
    return $pesAlterar->execute($pessoaDados);
}

function pessoaExcluir($ID_PESSOA) {
    $PDO = pdo();
    $pesExcluir = $PDO->prepare('
            DELETE FROM PESSOA 
                  WHERE ID_PESSOA = :ID_PESSOA
        ');
    return $pesExcluir->execute([
                ':ID_PESSOA' => $ID_PESSOA
    ]);
}

function pessoaListar() {
    $PDO = pdo();
    $sql = "
        SELECT *
          FROM PESSOA
      ORDER BY NOME
    ";
    return $PDO->query($sql);
}

try {

    $mensagemErro = '';
    $pessoaArray = [];

    //INCLUIR/ALTERAR - DADOS
    if (in_array(@$_POST['ACAO'], ['Incluir', 'Alterar'])) {
        $pessoaDados = pessoaDados();
    }

    //INCLUIR
    if (@$_POST['ACAO'] == 'Incluir') {
        $acaoDescricaoOk = 'Incluído';
        $ok = pessoaIncluir($pessoaDados);
    }
    //ALTERAR
    elseif (@$_POST['ACAO'] == 'Alterar') {
        $acaoDescricaoOk = 'Alterado';
        $ok = pessoaAlterar($pessoaDados, $_POST['ID_PESSOA']);
    }
    //EXCLUIR
    elseif (@$_POST['ACAO'] == 'Excluir') {
        $acaoDescricaoOk = 'Excluído';
        $ok = pessoaExcluir($_POST['ID_PESSOA']);
    }
    //CANCELAR
    elseif (@$_POST['ACAO'] == 'Cancelar') {
        unset($_POST);
    }
} catch (Exception $ex) {
    $mensagemErro = "<br><small style='font-size: 12px'>{$ex->getMessage()}</small><br>";
}
?>