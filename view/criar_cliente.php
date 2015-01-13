<?php
// set page headers
$page_title = "Cadastrar Cliente";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php
echo "<div class='right-button-margin'>";
    echo "<a href='view_cliente.php' class='btn btn-default pull-right'>";
    echo "<span class='glyphicon glyphicon-list-alt'></span> Visualizar Clientes</a>";
echo "</div>";
?>

<!-- HTML form para cadastrar Cliente -->
<form action="../controller/controleCliente.php?operacao=salvar" method="post">
    
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Raz&atilde;o Social:*</td>
            <td><input type='text' name='razaosocial' class='form-control' autocomplete="off" required></td>
        </tr>
 
        <tr>
            <td>Nome Fantasia:*</td>
            <td><input type='text' name='nomefantasia' class='form-control' autocomplete="off" required></td>
        </tr>
        <tr>
            <td>CNPJ:*</td>
            <td><input type='text' name='CNPJ' data-mask='99.999.999/9999-99' class='form-control' autocomplete="off" required></td>
        </tr>
        <tr>
            <td> CEP:* </td> 
            <td><input type="text" name="cep" data-mask='99.999-999'  id="cep" class='form-control' autocomplete="off" required/>
            </td>
        </tr>
            <!--Caso nao exista o CEP, ira mostrar uma mensagem aqui-->
            <div id="mensagemErro" class="ocultar"></div>
            
            <!--Aqui onde estarao os campos que serao preenchidos-->
            <div id="boxCampos" class="ocultar">
                <tr>
                    <td>UF:*</td>
                    <td>
                        <select name="UF" id='uf' class="form-control">
                            <option value="" selected="selected">Selecione...</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Cear&aacute;</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Esp&iacute;rito Santo</option>
                                <option value="GO">Goi&aacute;s</option>
                                <option value="MA">Maranh&atilde;o</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Par&aacute;</option>
                                <option value="PB">Para&iacute;ba</option>
                                <option value="PR">Paran&aacute;</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piau&iacute;</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rod&ocirc;nia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">S&atilde;o Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>                
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Cidade:*</td>  
                    <td><input type="text" name='cidade' id='cidade' class='form-control' autocomplete="off" required /></td>
                </tr>
                <tr>
                    <td>Bairro:*</td>  
                    <td><input type="text" name='bairro' id='bairro' class='form-control' autocomplete="off" required /></td>
                </tr>
                <tr>
                    <td>Logradouro:*</td>
                    <td><input type='text' name='logradouro' id='rua' class='form-control' autocomplete="off" required></td>
                </tr>
                <tr>
                    <td>N&uacute;mero:*</td>
                    <td><input type='text' name='numero' id='numero' class='form-control' autocomplete="off" required></td>
                </tr>
                <tr>
                    <td>Observa&ccedil;&atilde;o:*</td>
                    <td>
                        <textarea type="text" name="observacao" class="form-control" autocomplete="off" required></textarea>
                    </td>

                </tr>
 
            </div>
            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </td>
            </tr>
    </table>
</form>
<script type="text/javascript">
    $(function(){
        /**
         * Atribuo ao elemento #cep, o evento blur
         * Blur, dispara uma ação, quando o foco
         * sair do elemento, no nosso caso cep 
         */
        $("#cep").blur(function(){
            /**
             * Resgatamos o valor do campo #cep
             * usamos o replace, pra remover tudo que nao for numeico,
             * com uma expressão regular
             */
            var cep     = $(this).val().replace(/\.|\-/g, ''); 
            //Armazena a referência da div#boxCampos
            var boxes   = $("#boxCampos");
             //Armazena a referência da div#mensagemErro
            var msgErro = $("#mensagemErro");

            /**
             * Por padrao, vamos ocultar
             * div#boxCampos e tambem #mensagemErro
             */
            boxes.addClass('ocultar');
            msgErro.addClass('ocultar');

            //Verifica se não está vazio
            if(cep !== ""){
                 //Cria variável com a URL da consulta, passando o CEP
                 var url = 'http://cep.correiocontrol.com.br/'+cep+'.json';

                 /**
                  * Fazemos um requisição a URL, como vamos retornar json, 
                  * usamos o metodo $.getJSON;
                  * Que e composta pela URL, e a funcao que vai retorna os dados
                  * o qual passamos a variável json, para recuperar os valores
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

                        //Usamos o metodo fail, caso nao retorne nada
                    }).fail(function(){
                     //Não retornando um valor valido, ele mostra a mensagem
                     msgErro.removeClass('ocultar').html('<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>CEP inexistente</div>')
                });
            }
        });
    });
</script>
</body>

<?php
include_once "footer.php";
?>