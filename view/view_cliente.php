<?php
$page_title = "Visualizar clientes";
include_once "header.php";

session_start();
if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    session_unset();
}
?>

<?php

echo "<div class='right-button-margin'>";
    echo "<a href='criar_cliente.php' class='btn btn-default pull-right'>Criar cliente</a>";
echo "</div>";

//verificar se a pagina recebe parametro URL, pagina default é 1
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
    echo "<table class='table table-hover table-responsive table-bordered'>";
        echo "<tr>";
            echo "<th>Codigo</th>";
            echo "<th>Razão Social</th>";
            echo "<th>Nome Fantasia</th>";
            echo "<th>CNPJ</th>";
            echo "<th>CEP</th>";
            echo "<th>UF</th>";
            echo "<th>Cidade</th>";
            echo "<th>Bairro</th>";
            echo "<th>Rua</th>";
            echo "<th>Número</th>";
        echo "</tr>";
 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 
            extract($row);
 
            echo "<tr>";
                echo "<td>{$idCliente}</td>";
                echo "<td>{$razaosocial}</td>";
                echo "<td>{$nomefantasia}</td>";
                echo "<td>{$CNPJ}</td>";
                echo "<td>{$CEP}</td>";
                echo "<td>{$UF}</td>";
                echo "<td>{$cidade}</td>";
                echo "<td>{$bairro}</td>";
                echo "<td>{$rua}</td>";
                echo "<td>{$numero}</td>";
                echo "<td>";
                        // botões edite e delete
                        echo "<a href='update_cliente.php?idCliente={$idCliente}' class='btn btn-default left-margin'>Editar</a>";
                        echo "<a delete-id='{$idCliente}' class='btn btn-default delete-object'>Deletar</a>";
                echo "</td>";
            echo "</tr>";
 
        }
 
    echo "</table>";
 
    //botões de paginação vão aqui
    include_once 'paginacao_cliente.php';
}
 
// avisa que não há clientes
else{
    echo $num."<div>Não foram encontrados clientes.</div>";
}
?>

<script>
$(document).on('click', '.delete-object', function(){
 
    var id = $(this).attr('delete-id');
    var q = confirm("Tem certeza que deseja excluir o cliente??");
 
    if (q == true){
 
        $.post('../controller/controlecliente.php', {
            operacao: 'deletar',
            object_id: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Não foi possível deletar o cliente.');
        });
 
    }
 
    return false;
});
</script>


<?php
include_once "footer.php";
?>