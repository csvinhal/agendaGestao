<?php
$page_title = "Atualizar Cliente";
include_once "header.php";
?>

<?php
    echo "<div class='right-button-margin'>";
    echo "<a href='view_cliente.php' class='btn btn-default pull-right'>Listar Clientes</a>";
    echo "</div>";
    
    //pega o ID do cliente a ser editado 
    $id = isset($_GET['idCliente']) ? $_GET['idCliente'] : die('ERROR: missing ID.');

    // include database e clienteDAO
    include_once '../config/database.class.php';
    include_once '../model/clienteDAO.class.php';
    include_once '../model/Cliente.class.php';

    //Pega conexao database
    $database = new Database();
    $db = $database->getConnection();
    
    //Instacia p cliente
    $cliente = new Cliente($db);
    //Instacia clienteDAO
    $clienteDAO = new clienteDAO($db);

    //Seta o ID do cliente a ser editado
    $cliente->idCliente = $id;

    // read the details of product to be edited
    $clienteDAO->readOne($cliente);
?>

<form action='../controller/controleCliente.php?operacao=update&idCliente=<?php echo $id; ?>' method='post'>
 
    <table class='table table-hover table-responsive table-bordered'>
 
        <tr>
            <td>Razão Social</td>
            <td><input type='text' name='razaosocial' value='<?php echo $cliente->razaosocial; ?>' class='form-control' required></td>
        </tr>
 
        <tr>
            <td>Nome Fantasia</td>
            <td><input type='text' name='nomefantasia' value='<?php echo $cliente->nomefantasia; ?>' class='form-control' required></td>
        </tr>
 
        <tr>
            <td>CNPJ</td>
            <td><input type='text' name='CNPJ' value='<?php echo $cliente->CNPJ; ?>' data-mask='99.999.999/9999-99' class='form-control' required></td>
        </tr>
        
        <tr>
            <td>CEP</td>
            <td><input type='text' name='CEP' value='<?php echo $cliente->CEP; ?>' data-mask='99999-999' class='form-control' required></td>
        </tr>
        
        <tr>
            <td>UF</td>
            <td>
                <select name="UF" class="form-control">
                <option value="" selected="selected">Selecione...</option>
                        <option value="AC" <?php if('AC' == $cliente->UF)echo 'selected' ?>>Acre</option>
                        <option value="AL" <?php if('AL' == $cliente->UF)echo 'selected' ?>>Alagoas</option>
                        <option value="AP" <?php if('AP' == $cliente->UF)echo 'selected' ?>>Amapá</option>
                        <option value="AM" <?php if('AM' == $cliente->UF)echo 'selected' ?>>Amazonas</option>
                        <option value="BA" <?php if('BA' == $cliente->UF)echo 'selected' ?>>Bahia</option>
                        <option value="CE" <?php if('CE' == $cliente->UF)echo 'selected' ?>>Ceará</option>
                        <option value="DF" <?php if('DF' == $cliente->UF)echo 'selected' ?>>Distrito Federal</option>
                        <option value="ES" <?php if('ES' == $cliente->UF)echo 'selected' ?>>Espírito Santo</option>
                        <option value="GO" <?php if('GO' == $cliente->UF)echo 'selected' ?>>Goiás</option>
                        <option value="MA" <?php if('MA' == $cliente->UF)echo 'selected' ?>>Maranhão</option>
                        <option value="MT" <?php if('MT' == $cliente->UF)echo 'selected' ?>>Mato Grosso</option>
                        <option value="MS" <?php if('MS' == $cliente->UF)echo 'selected' ?>>Mato Grosso do Sul</option>
                        <option value="MG" <?php if('MG' == $cliente->UF)echo 'selected' ?>>Minas Gerais</option>
                        <option value="PA" <?php if('PA' == $cliente->UF)echo 'selected' ?>>Pará</option>
                        <option value="PB" <?php if('PB' == $cliente->UF)echo 'selected' ?>>Paraíba</option>
                        <option value="PR" <?php if('PR' == $cliente->UF)echo 'selected' ?>>Paraná</option>
                        <option value="PE" <?php if('PE' == $cliente->UF)echo 'selected' ?>>Pernambuco</option>
                        <option value="PI" <?php if('PI' == $cliente->UF)echo 'selected' ?>>Piauí</option>
                        <option value="RJ" <?php if('RJ' == $cliente->UF)echo 'selected' ?>>Rio de Janeiro</option>
                        <option value="RN" <?php if('RN' == $cliente->UF)echo 'selected' ?>>Rio Grande do Norte</option>
                        <option value="RS" <?php if('RS' == $cliente->UF)echo 'selected' ?>>Rio Grande do Sul</option>
                        <option value="RO" <?php if('RO' == $cliente->UF)echo 'selected' ?>>Rodônia</option>
                        <option value="RR" <?php if('RR' == $cliente->UF)echo 'selected' ?>>Roraima</option>
                        <option value="SC" <?php if('SC' == $cliente->UF)echo 'selected' ?>>Santa Catarina</option>
                        <option value="SP" <?php if('SP' == $cliente->UF)echo 'selected' ?>>São Paulo</option>
                        <option value="SE" <?php if('SE' == $cliente->UF)echo 'selected' ?>>Sergipe</option>
                        <option value="TO" <?php if('TO' == $cliente->UF)echo 'selected' ?>>Tocantins</option>                
                </select>
            </td>
        </tr>
        
        <tr>
            <td>Cidade</td>
            <td><input type='text' name='cidade' value='<?php echo $cliente->cidade; ?>' class='form-control' required></td>
        </tr>
        
        <tr>
            <td>Bairro</td>
            <td><input type='text' name='bairro' value='<?php echo $cliente->bairro; ?>' class='form-control' required></td>
        </tr>
        
        <tr>
            <td>Rua</td>
            <td><input type='text' name='rua' value='<?php echo $cliente->rua; ?>' class='form-control' required></td>
        </tr>
        
        <tr>
            <td>Número</td>
            <td><input type='text' name='numero' value='<?php echo $cliente->numero; ?>' class='form-control' required></td>
        </tr>
 
         
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </td>
        </tr>
 
    </table>
</form>