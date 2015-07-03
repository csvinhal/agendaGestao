<?php
// set page headers
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php if(isset($_GET['idTipAloc'])){
    $id = $_GET['idTipAloc'];
    echo "<form action='../../controller/controleTipoAlocacao.php?operacao=update&idTipAloc=$id' method='post'>";
}else{
    echo "<form action='../../controller/controleTipoAlocacao.php?operacao=salvar' method='post'>";
}
?>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Cadastrar tipo de aloca&ccedil;&atilde;o</h3>
            </div>
 
            <table class='table table-hover'>
            <?php
                include_once '../../config/database.class.php';
                include_once '../../model/tipoAlocacaoDAO.class.php';
                include_once '../../model/TipoAlocacao.class.php';

                $database = new Database();
                $db = $database->getConnection();
                $objTipAlocDAO = new tipoAlocacaoDAO($db);
                
                /*
                 * Se houver id do tipo de alocacao traz as informacoes do tipo 
                 * para edicao.
                 * Se nao traz a pagina de adicionar tipo
                 */
                if(isset($_GET['idTipAloc'])){
                    $objTipAloc = new TipoAlocacao($db);
                    $objTipAloc->idTipAloc = $_GET['idTipAloc'];
                    $objTipAlocDAO->buscaTipAloc($objTipAloc);
                
                    echo "<tr>";
                        echo "<td>Descri&ccedil;&atilde;o:*</td>";
                        echo "<td><input type='text' name='desTipAloc' class='form-control' value='$objTipAloc->desAloc' autocomplete='off' required></td>";
                    echo "</tr>";
                    echo "<tr>";
                        echo "<td>Ativo</td>";
                        echo "<td>";
                            echo "<div class=\"radio\">";
                                echo "<label class=\"radio-inline\">";
                                    if($objTipAloc->ativo == 'S'){
                                        echo "<input type=\"radio\" name=\"ativo\" id=\"ativoSim\" value=\"S\" checked>";
                                    }else{
                                        echo "<input type=\"radio\" name=\"ativo\" id=\"ativoSim\" value=\"S\">";
                                    }
                                echo "Sim</label>";
                                echo "<label class=\"radio-inline\">";
                                    if($objTipAloc->ativo == 'N'){
                                        echo "<input type=\"radio\" name=\"ativo\" id=\"ativoNao\" value=\"N\" checked>";
                                    }else{
                                        echo "<input type=\"radio\" name=\"ativo\" id=\"ativoNao\" value=\"N\">";
                                    }
                                echo "N&atilde;o</label>";
                            echo "</div>";
                        echo "</td>";
                    echo "</tr>";
                }else{
                    echo "<tr>";
                        echo "<td>Descri&ccedil;&atilde;o:*</td>";
                        echo "<td><input type='text' name='desTipAloc' class='form-control' autocomplete='off' required></td>";
                    echo "</tr>";  
                    echo "<tr>";
                        echo "<td>Ativo</td>";
                        echo "<td>";
                            echo "<div class=\"radio\">";
                                echo "<label class=\"radio-inline\">";
                                    echo "<input type=\"radio\" name=\"ativo\" id=\"ativoSim\" value=\"S\" checked>";
                                echo "Sim</label>";
                                echo "<label class=\"radio-inline\">";
                                    echo "<input type=\"radio\" name=\"ativo\" id=\"ativoNao\" value=\"N\">";
                                echo "N&atilde;o</label>";
                            echo "</div>";
                        echo "</td>";
                    echo "</tr>";
                }

            ?>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>

<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Tipos de Aloca&ccedil;&atilde;o</h3>
        </div>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th class="text-center">C&oacute;digo</th>
                    <th class="text-center">Descri&ccedil;&atilde;o</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Gerenciar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($objTipAlocDAO->read()){
                        $stmt = $objTipAlocDAO->buscaTodos();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row);
                            echo "<tr>";
                                echo "<td class='text-center'>{$idTipAloc}</td>";
                                echo "<td class='text-center'>{$desAloc}</td>";
                                echo "<td class='text-center'>";
                                    if($row['ativo'] == 'S'){
                                        echo "Ativado";
                                    }else{
                                        echo "Desativado";
                                    }
                                echo "</td>";
                                echo "<td class='text-center'>";
                                // botoes edite e delete
                                echo "<a href='?idTipAloc={$idTipAloc}' class='btn btn-default left-margin'>";
                                echo "<span class='glyphicon glyphicon-cog' ></span> Editar</a>";
                                echo "</td>";
                            echo "</tr>";
                        }
                    }else{
                        echo "<tr>";
                            echo "<td>N&atilde;o foram encontrados registros</td>";
                        echo "</tr>";
                    }    
                ?>
            </tbody>
        </table>
    </div>
</div>
			
<?php
include_once "footer.php";
?>