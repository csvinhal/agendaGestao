<?php
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php
    echo "<div class='right-button-margin'>";
    echo "<a href='view_cliente.php' class='btn btn-default pull-right'>Listar Clientes</a>";
    echo "</div>";
    
    //pega o ID do cliente a ser editado 
    $id = isset($_GET['idCliente']) ? $_GET['idCliente'] : die('ERROR: missing ID.');

    // include database e clienteDAO
    include_once '../../config/database.class.php';
    include_once '../../model/clienteDAO.class.php';
    include_once '../../model/Cliente.class.php';

    //Pega conexao database
    $database = new Database();
    $db = $database->getConnection();
    
    //Instacia p cliente
    $cliente = new Cliente($db);
    //Instacia clienteDAO
    $clienteDAO = new clienteDAO($db);

    //Seta o ID do cliente a ser editado
    $cliente->idCliente = $id;

    // L� os detalhes do cliente a ser editado
    $clienteDAO->readOne($cliente);
?>

<form action='../../controller/controleCliente.php?operacao=update&idCliente=<?php echo $id; ?>' method='post'>
    
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Alterar clientes</h3>
            </div> 
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Raz&atilde;o Social:*</td>
                    <td><input type='text' name='razaosocial' value='<?php echo $cliente->razaoSocial; ?>' class='form-control' required></td>
                </tr>

                <tr>
                    <td>Nome Fantasia:*</td>
                    <td><input type='text' name='nomefantasia' value='<?php echo $cliente->nomeFantasia; ?>' class='form-control' required></td>
                </tr
                <tr>
                    <td>CNPJ:*</td>
                    <td><input type='text' name='CNPJ' value='<?php echo $cliente->CNPJ; ?>' data-mask='99.999.999/9999-99' class='form-control'></td>
                </tr>
                <tr>
                    <td>CEP:*</td>
                    <td><input type='text' name='cep' id='cep' value='<?php echo $cliente->CEP; ?>' data-mask='99999-999' class='form-control'></td>
                </tr>
                    <!--Caso n�o exista o CEP, ir� mostrar uma mensagem aqui-->
                    <div id='mensagemErro' class='ocultar'></div>

                    <!--Aqui onde estar�o os campos que ser�o preenchidos-->
                    <div id='boxCampos' class='ocultar'>
                        <tr>
                            <td>UF:*</td>
                            <td>
                                <select name="UF" id='uf' class="form-control">
                                <option value="" selected="selected">Selecione...</option>
                                        <option value="AC" <?php if('AC' == $cliente->UF)echo 'selected' ?>>Acre</option>
                                        <option value="AL" <?php if('AL' == $cliente->UF)echo 'selected' ?>>Alagoas</option>
                                        <option value="AP" <?php if('AP' == $cliente->UF)echo 'selected' ?>>Amap&aacute;</option>
                                        <option value="AM" <?php if('AM' == $cliente->UF)echo 'selected' ?>>Amazonas</option>
                                        <option value="BA" <?php if('BA' == $cliente->UF)echo 'selected' ?>>Bahia</option>
                                        <option value="CE" <?php if('CE' == $cliente->UF)echo 'selected' ?>>Cear&aacute;</option>
                                        <option value="DF" <?php if('DF' == $cliente->UF)echo 'selected' ?>>Distrito Federal</option>
                                        <option value="ES" <?php if('ES' == $cliente->UF)echo 'selected' ?>>Esp&iacute;rito Santo</option>
                                        <option value="GO" <?php if('GO' == $cliente->UF)echo 'selected' ?>>Goi&aacute;s</option>
                                        <option value="MA" <?php if('MA' == $cliente->UF)echo 'selected' ?>>Maranh&atilde;o</option>
                                        <option value="MT" <?php if('MT' == $cliente->UF)echo 'selected' ?>>Mato Grosso</option>
                                        <option value="MS" <?php if('MS' == $cliente->UF)echo 'selected' ?>>Mato Grosso do Sul</option>
                                        <option value="MG" <?php if('MG' == $cliente->UF)echo 'selected' ?>>Minas Gerais</option>
                                        <option value="PA" <?php if('PA' == $cliente->UF)echo 'selected' ?>>Par&aacute;</option>
                                        <option value="PB" <?php if('PB' == $cliente->UF)echo 'selected' ?>>Para&iacute;ba</option>
                                        <option value="PR" <?php if('PR' == $cliente->UF)echo 'selected' ?>>Paran&aacute;</option>
                                        <option value="PE" <?php if('PE' == $cliente->UF)echo 'selected' ?>>Pernambuco</option>
                                        <option value="PI" <?php if('PI' == $cliente->UF)echo 'selected' ?>>Piau&iacute;</option>
                                        <option value="RJ" <?php if('RJ' == $cliente->UF)echo 'selected' ?>>Rio de Janeiro</option>
                                        <option value="RN" <?php if('RN' == $cliente->UF)echo 'selected' ?>>Rio Grande do Norte</option>
                                        <option value="RS" <?php if('RS' == $cliente->UF)echo 'selected' ?>>Rio Grande do Sul</option>
                                        <option value="RO" <?php if('RO' == $cliente->UF)echo 'selected' ?>>Rod&ocirc;nia</option>
                                        <option value="RR" <?php if('RR' == $cliente->UF)echo 'selected' ?>>Roraima</option>
                                        <option value="SC" <?php if('SC' == $cliente->UF)echo 'selected' ?>>Santa Catarina</option>
                                        <option value="SP" <?php if('SP' == $cliente->UF)echo 'selected' ?>>S&atilde;o Paulo</option>
                                        <option value="SE" <?php if('SE' == $cliente->UF)echo 'selected' ?>>Sergipe</option>
                                        <option value="TO" <?php if('TO' == $cliente->UF)echo 'selected' ?>>Tocantins</option>                
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Cidade:*</td>
                            <td><input type='text' name='cidade' id='cidade' value='<?php echo $cliente->cidade; ?>' class='form-control'></td>
                        </tr>

                        <tr>
                            <td>Bairro:*</td>
                            <td><input type='text' name='bairro' id='bairro' value='<?php echo $cliente->bairro; ?>' class='form-control'></td>
                        </tr>
                        <tr>
                            <td>Logradouro:*</td>
                            <td><input type='text' name='logradouro' id='rua' value='<?php echo $cliente->logradouro; ?>' class='form-control'></td>
                        </tr>
                    </div>

                        <tr>
                            <td>N&uacute;mero:*</td>
                            <td><input type='text' name='numero' id='numero' value='<?php echo $cliente->numero; ?>' class='form-control'></td>
                        </tr> 
                     <tr>
                        <td></td>
                        <td>
                            <button type='submit' class='btn btn-primary'>Atualizar</button>
                        </td>
                    </tr>
            </table>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(function(){
        /**
         * Atribuo ao elemento #cep, o evento blur
         * Blur, dispara uma a��o, quando o foco
         * sair do elemento, no nosso caso cep 
         */
        $("#cep").blur(function(){
            /**
             * Resgatamos o valor do campo #cep
             * usamos o replace, pra remover tudo que n�o for num�rico,
             * com uma express�o regular
             */
            var cep     = $(this).val().replace(/\.|\-/g, ''); 
            //Armazena a refer�ncia da div#boxCampos
            var boxes   = $("#boxCampos");
             //Armazena a refer�ncia da div#mensagemErro
            var msgErro = $("#mensagemErro");

            /**
             * Por padr�o, vamos ocultar
             * div#boxCampos e tamb�m #mensagemErro
             */
            boxes.addClass('ocultar');
            msgErro.addClass('ocultar');

            //Verifica se n�o est� vazio
            if(cep !== ""){
                 //Cria vari�vel com a URL da consulta, passando o CEP
                 var url = 'http://cep.correiocontrol.com.br/'+cep+'.json';

                 /**
                  * Fazemos um requisi��o a URL, como vamos retornar json, 
                  * usamos o m�todo $.getJSON;
                  * Que � composta pela URL, e a fun��o que vai retorna os dados
                  * o qual passamos a vari�vel json, para recuperar os valores
                  */
                 $.getJSON(url, function(json){
                        //Atribuimos o valor aos inputs
                        $("#rua").val(json.logradouro);
                        $("#bairro").val(json.bairro);
                        $("#cidade").val(json.localidade);
                        $("#uf").val(json.uf);
                        /**
                         * Removemos a classe ocultar, para mostrar os campos
                         * preenchidos
                         */
                        boxes.removeClass('ocultar');

                        //Usamos o m�todo fail, caso n�o retorne nada
                    }).fail(function(){
                     //N�o retornando um valor v�lido, ele mostra a mensagem
                     msgErro.removeClass('ocultar').html('<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>CEP inexistente</div>')
                });
            }
        });
    });
</script>

<?php
include_once "footer.php";
?>