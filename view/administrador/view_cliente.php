<?php
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

include_once '../../model/clienteDAO.class.php';
include_once '../../model/papelDAO.class.php';
include_once '../../config/database.class.php';

$database = new Database();
$db = $database->getConnection();

$clienteDAO = new clienteDAO($db);
// query cliente
$stmt = $clienteDAO->search();
$num = $stmt->rowCount();
 
// display the products if there are any
if($num>0){
    echo "<table id='tabelaCliente' class='table table-hover'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th class='text-center'>C&oacute;digo</th>";
                echo "<th class='text-center'>Raz&atilde;o Social</th>";
                echo "<th class='text-center'>Nome Fantasia</th>";
                echo "<th class='text-center'>CNPJ</th>";
                echo "<th class='text-center'>Gerenciar</th>";
            echo "</tr>";
        echo "</thead>";

        echo "<tbody>"; 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            include_once '../../validate/Validate.class.php';
            extract($row);
            echo "<tr>";
                echo "<td class='text-center'>{$idCliente}</td>";
                echo "<td class='text-center'>{$razaosocial}</td>";
                echo "<td class='text-center'>{$nomefantasia}</td>";
                $CNPJ = Validate::mascara_string("##.###.###/####-##", $CNPJ);
                echo "<td class='text-center'>{$CNPJ}</td>";
                echo "<td>";
                        // botoes edite e delete
                        echo "<a href='update_cliente.php?idCliente={$idCliente}' class='btn btn-default left-margin'>";
                        echo "<span class='glyphicon glyphicon-cog' ></span> Editar</a>";
                        echo "<a delete-id='{$idCliente}' onclick='deletarCliente()' class='btn btn-default delete-object'>";
                        echo "<span class='glyphicon glyphicon-trash' ></span> Deletar</a>";
                echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";

    echo "</table>";
}
 
// avisa que nao ha clientes
else{
    echo $num."<div>N&atilde;o foram encontrados clientes.</div>";
}
?>

<script>
$(document).ready(function(){
    $('#tabelaCliente').dataTable();
});
</script>


<?php
include_once "footer.php";
?>