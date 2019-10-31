<?

//PRINT_R PRE
function pr($dado, $print_r = true) {
    echo '<pre>';
    if ($print_r) {
        print_r($dado);
    } else {
        var_dump($dado);}
}

try {

    //CONEXAO
    $PDO = new PDO('mysql:host=127.0.0.1;dbname=CRUD', 'root', '');
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mensagemErro = '';
    $pessoaArray = [];

    //INCLUIR
    if (@$_POST['ACAO'] == 'Incluir') {
        $acaoDescricaoOk = 'Incluído';
        $pesIncluir = $PDO->prepare('INSERT INTO PESSOA (NOME) VALUES(:NOME)');
        $ok = $pesIncluir->execute(array(
            ':NOME' => $_POST['NOME']
        ));
    }
    //ALTERAR
    elseif (@$_POST['ACAO'] == 'Alterar') {
        $acaoDescricaoOk = 'Alterado';
        $pesAlterar = $PDO->prepare('UPDATE PESSOA SET  NOME = :NOME WHERE ID_PESSOA = :ID_PESSOA');
        $ok = $pesAlterar->execute(array(
            ':NOME' => $_POST['NOME'],
            ':ID_PESSOA' => $_POST['ID_PESSOA']
        ));
        
    }
    //EXCLUIR
    elseif (@$_POST['ACAO'] == 'Excluir') {
        $acaoDescricaoOk = 'Excluído';
        $pesExcluir = $PDO->prepare('DELETE FROM PESSOA WHERE ID_PESSOA = :ID_PESSOA');
        $ok = $pesExcluir->execute(array(
            ':ID_PESSOA' => $_POST['ID_PESSOA']
        ));
    }
    //CANCELAR
    elseif (@$_POST['ACAO'] == 'Cancelar') {
        unset($_POST);
    }
} catch (Exception $ex) {
    $mensagemErro = "<br><small>{$ex->getMessage()}</small><br>";
}

//LISTAR
$sql = "
        SELECT ID_PESSOA, 
               NOME
          FROM PESSOA
      ORDER BY NOME
    ";
$pessoaQuery = $PDO->query($sql);
?>

<center>
    <?
    if (@$_POST['ACAO'] && @$ok == 1) {
        echo "<h3 style='color: green'>$acaoDescricaoOk com sucesso! $mensagemErro</h3>";
    } elseif (@$_POST['ACAO'] && @$_POST['NOME'] && !@$ok) {
        echo "<h3 style='color: red'>Não foi possível $_POST[ACAO]! $mensagemErro</h3>";
    }
    ?>
    <table border="1" style="min-width: 500px">
        <tr style=" vertical-align: top">
            <td style="text-align: right;">
                <h2 style="text-align: center">Listar Pessoas</h2> 

                <?
                if ($pessoaQuery->rowCount() == 0) {
                    echo '<h5 style="text-align: center; color: blue">Não existem pessoas para listar!</h5>';
                }
                while ($pessoaFetch = $pessoaQuery->fetch(PDO::FETCH_ASSOC)) {
                    $pessoaArray[$pessoaFetch['ID_PESSOA']] = $pessoaFetch;
                    echo $pessoaFetch['NOME'];
                    ?>
                    <form method="POST" style="display: inline">
                        <input name="ID_PESSOA" value="<?= @$pessoaFetch[ID_PESSOA] ?>" hidden>
                        <input name="ACAO" value="Editar" type="submit">
                        <input name="ACAO" value="Excluir" type="submit">
                    </form>
                    <hr>
                    <?
                }
                $pessoaAlterar = @$pessoaArray[@$_POST['ID_PESSOA']];
                $acaoDescricao = ($pessoaAlterar ? 'Alterar' : 'Incluir');
                ?>
            </td>
            <td style="text-align: center;">
                <h2><?= $acaoDescricao ?> Pessoa</h2> 
                <form method="POST">
                    <input name="ID_PESSOA" value="<?= $pessoaAlterar['ID_PESSOA'] ?>" hidden>
                    <input name="NOME" value="<?= $pessoaAlterar['NOME'] ?>" maxlength="100" required><br>
                    <input name="ACAO" value="<?= $acaoDescricao ?>" type="submit">
                    <input name="ACAO" value="Cancelar" type="submit">
                </form>
            </td>
        </tr>
    </table>
</center>