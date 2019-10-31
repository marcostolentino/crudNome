<style>
    label {
        font-weight: bold;
        display: block;
    }
    select, textarea {
        width: 173px;        
    }
</style>

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

try {

    //CONEXAO
    $PDO = new PDO('mysql:host=127.0.0.1;dbname=CRUD', 'root', '');
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mensagemErro = '';
    $pessoaArray = [];

    //INCLUIR/ALTERAR - DADOS
    if (in_array(@$_POST['ACAO'], ['Incluir', 'Alterar'])) {
        $pessoaDados = [
            ':NOME' => @$_POST['NOME'],
            ':UF' => @$_POST['UF'],
            ':OBSERVACAO' => @$_POST['OBSERVACAO']
        ];
    }

    //INCLUIR
    if (@$_POST['ACAO'] == 'Incluir') {
        $acaoDescricaoOk = 'Incluído';
        $pesIncluir = $PDO->prepare('
            INSERT INTO PESSOA (NOME,   UF,  OBSERVACAO) 
                        VALUES (:NOME, :UF, :OBSERVACAO)
        ');
        $ok = $pesIncluir->execute($pessoaDados);
    }
    //ALTERAR
    elseif (@$_POST['ACAO'] == 'Alterar') {
        $acaoDescricaoOk = 'Alterado';
        $pesAlterar = $PDO->prepare('
            UPDATE PESSOA SET NOME = :NOME, 
                                UF = :UF, 
                        OBSERVACAO = :OBSERVACAO 
                   WHERE ID_PESSOA = :ID_PESSOA
        ');
        $pessoaDados[':ID_PESSOA'] = @$_POST['ID_PESSOA'];
        $ok = $pesAlterar->execute($pessoaDados);
    }
    //EXCLUIR
    elseif (@$_POST['ACAO'] == 'Excluir') {
        $acaoDescricaoOk = 'Excluído';
        $pesExcluir = $PDO->prepare('
            DELETE FROM PESSOA 
                  WHERE ID_PESSOA = :ID_PESSOA
        ');
        $ok = $pesExcluir->execute([
            ':ID_PESSOA' => $_POST['ID_PESSOA']
        ]);
    }
    //CANCELAR
    elseif (@$_POST['ACAO'] == 'Cancelar') {
        unset($_POST);
    }
} catch (Exception $ex) {
    $mensagemErro = "<br><small style='font-size: 12px'>{$ex->getMessage()}</small><br>";
}

//LISTAR
$sql = "
        SELECT *
          FROM PESSOA
      ORDER BY NOME
    ";
$pessoaQuery = $PDO->query($sql);
?>