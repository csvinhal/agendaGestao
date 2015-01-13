<?php
$page_title = "Visualizar clientes";
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>

<?php

echo "<div class='right-button-margin'>";
    echo "<a href=\"criar_cliente.php\" class=\"btn btn-default pull-right\">";
    echo "<span class=\"glyphicon glyphicon-plus\" ></span>Criar cliente</a>";
echo "</div>";

//verificar se a pagina recebe parametro URL, pagina default e 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;

//numero de linhas por paginas
$records_per_page = 4;
 
//calcula por query o limite por pagina
$from_record_num = ($records_per_page * $page) - $records_per_page;

include_once '../model/clienteDAO.class.php';
include_once '../model/papelDAO.class.php';
include_once '../config/database.class.php';

$database = new Database();
$db = $database->getConnection();

$clienteDAO = new clienteDAO($db);
// query cliente
$stmt = $clienteDAO->readAll($page, $from_record_num, $records_per_page);
$num = $stmt->rowCount();
 
// display the products if there are any
if($num>0){
    echo "<div class=\"row\">";
        echo "<div class=\"panel panel-primary filterable\">";
            echo "<div class=\"panel-heading\">";
                echo "<h3 class=\"panel-title\">Clientes</h3>";
                echo "<div class=\"pull-right\">";
                    echo "<button class=\"btn btn-default btn-xs btn-filter\"><span class=\"glyphicon glyphicon-filter\"></span> Filtrar</button>";
                echo "</div>";
            echo "</div>";
            echo "<table class='table table-hover'>";
                echo "<thead>";
                    echo "<tr class=\"filters\">";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Codigo\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Raz&atilde;o Social\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Nome Fantasia\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"CNPJ\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"CEP\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"UF\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Cidade\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Bairro\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Logradouro\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"N&uacute;mero\" disabled></th>";
                        echo "<th><input type=\"text\" class=\"form-control\" placeholder=\"Gerenciar\" disabled></th>";
                    echo "</tr>";
                echo "</thead>";

                echo "<tbody>"; 
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                    extract($row);

                    echo "<tr>";
                        echo "<td class='text-center'>{$idCliente}</td>";
                        echo "<td class='text-center'>{$razaosocial}</td>";
                        echo "<td class='text-center'>{$nomefantasia}</td>";
                        echo "<td class='text-center'>{$CNPJ}</td>";
                        echo "<td class='text-center'>{$CEP}</td>";
                        echo "<td class='text-center'>{$UF}</td>";
                        echo "<td class='text-center'>{$cidade}</td>";
                        echo "<td class='text-center'>{$bairro}</td>";
                        echo "<td class='text-center'>{$logradouro}</td>";
                        echo "<td class='text-center'>{$numero}</td>";
                        echo "<td>";
                                // botoes edite e delete
                                echo "<a href='update_cliente.php?idCliente={$idCliente}' class='btn btn-default left-margin'>";
                                echo "<span class='glyphicon glyphicon-cog' ></span> Editar</a>";
                                echo "<a delete-id='{$idCliente}' class='btn btn-default delete-object'>";
                                echo "<span class='glyphicon glyphicon-trash' ></span> Deletar</a>";
                        echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";

            echo "</table>";
        echo "</div>";
    echo "</div>";
 
    //botoes de paginacaoo vao aqui
    include_once 'paginacao_cliente.php';
}
 
// avisa que nao ha clientes
else{
    echo $num."<div>Não foram encontrados clientes.</div>";
}
?>

<script>
$(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
    var q = confirm("Tem certeza que deseja excluir o cliente??");
 
    if (q == true){
 
        $.post('../controller/controleCliente.php', {
            operacao: 'deletar',
            object_id: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('N&atilde;o foi poss&iacute;vel deletar o cliente.');
        });
 
    }
 
    return false;
});
</script>


<?php
include_once "footer.php";
?>