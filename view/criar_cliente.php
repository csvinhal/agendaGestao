<?php
// set page headers
$page_title = "Cadastrar Cliente";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    session_unset();
}
?>

<?php
echo "<div class='right-button-margin'>";
    echo "<a href='view_cliente.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-list-alt'></span> Visualizar Clientes</a>";
echo "</div>";
?>


<!-- HTML form para cadastrar cliente -->
<form action='../controller/controleCliente.php?operacao=salvar' method='post'>
 
    <table class='table table-hover table-responsive table-bordered'>
 
        <tr>
            <td>Razão Social</td>
            <td><input type='text' name='razaosocial' class='form-control' required></td>
        </tr>
 
        <tr>
            <td>Nome Fantasia</td>
            <td><input type='text' name='nomefantasia' class='form-control' required></td>
        </tr>
        <tr>
            <td>CNPJ</td>
            <td><input type='text' name='CNPJ' data-mask='99.999.999/9999-99' class='form-control' required></td>
        </tr>
        <tr>
            <td>CEP</td>
            <td><input type='text' name='CEP' data-mask='99999-999' class='form-control' required></td>
        </tr> 
        <tr>
            <td>UF</td>
            <td>
                <select name="UF" class="form-control">
                    <option value="" selected="selected">Selecione...</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rodônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>                
                </select>
            </td>
        </tr>
        <tr>
            <td>Cidade</td>
            <td><input type='text' name='cidade' class='form-control' required></td>
        </tr>
        <tr>
            <td>Bairro</td>
            <td><input type='text' name='bairro' class='form-control' required></td>
        </tr> 
        <tr>
            <td>Rua</td>
            <td><input type='text' name='rua' class='form-control' required></td>
        </tr>
        <tr>
            <td>Número</td>
            <td><input type='text' name='numero' class='form-control' required></td>
        </tr> 
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </td>
        </tr>
 
    </table>
</form>

<?php
include_once "footer.php";
?>