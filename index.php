<? require_once 'funcoes.php'; ?>

<style>
    label {
        font-weight: bold;
        display: block;
    }
    select, textarea {
        width: 173px;        
    }
</style>

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
            <td style="text-align: right; padding-right: 10px">
                <h2 style="text-align: center">Listar Pessoas</h2> 
                <?
                $pessoaQuery = pessoaListar();
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
            <td style="padding-left: 10px">
                <h2 style="text-align: center;"><?= $acaoDescricao ?> Pessoa</h2> 
                <form method="POST">
                    <input type="text" name="ID_PESSOA" value="<?= $pessoaAlterar['ID_PESSOA'] ?>" hidden>
                    <label>Nome:</label>
                    <input type="text" name="NOME" value="<?= @$pessoaAlterar['NOME'] ?>" minlength="3" maxlength="100" required><br>
                    <label>UF:</label>
                    <select name="UF" required>
                        <option></option>
                        <option value="SC" <?= (@$pessoaAlterar['UF'] == 'SC' ? 'selected' : '') ?>>SC</option>
                        <option value="OU" <?= (@$pessoaAlterar['UF'] == 'OU' ? 'selected' : '') ?>>Outro</option>
                    </select><br>
                    <label style="font-weight: normal">Observação:</label>
                    <textarea maxlength="1000" style="height: 100px" name="OBSERVACAO"><?= @$pessoaAlterar['OBSERVACAO'] ?></textarea>
                    <hr>
                    <div style="border: 0px solid; text-align: center">
                        <input name="ACAO" value="<?= $acaoDescricao ?>" type="submit">
                        <input name="ACAO" value="Cancelar" type="submit">
                    </div>
                </form>
            </td>
        </tr>
    </table>
</center>