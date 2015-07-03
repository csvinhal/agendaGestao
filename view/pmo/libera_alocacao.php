<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "header.php";

if(isset($_SESSION['Mensagem'])){
    echo $_SESSION['Mensagem'];
    unset($_SESSION['Mensagem']);
}
?>
<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Libera&ccedil;&atilde;o de Aloca&ccedil;&atilde;o</h3>
        </div>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th class="text-center">Data</th>
                    <th class="text-center">Colaborador</th>
                    <th class="text-center">Hora Inicio</th>
                    <th class="text-center">Hora Fim</th>
                    <th class="text-center">Liberar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    include_once '../../config/database.class.php';
                    include_once '../../model/alocacaoDAO.class.php';

                    $database = new Database();
                    $db = $database->getConnection();
                    $objAlocDAO = new alocacaoDAO($db);
                    if($objAlocDAO->procurarBloqueadoGeral()){
                        $stmt = $objAlocDAO->procurarBloqueadoGeral();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row);
                            echo "<tr>";
                                echo "<td class='text-center'>{$dataAlocacao}</td>";
                                $usuario = new Usuario($db);
                                $usuario->idUsuario = $idColaborador;
                                $objColaborador = new usuarioDAO($db);
                                $objColaborador->readOne($usuario);
                                echo "<td class='text-center'>{$usuario->nome}</td>";
                                echo "<td class='text-center'>{$horaInicio}</td>";
                                echo "<td class='text-center'>{$horaFim}</td>";
                                echo "<td class='text-center'>";
                                echo "<a onclick=\"liberarAlocacao()\" libera-data='{$dataAlocacao}' libera-col='{$idColaborador}' libera-hIni='{$horaInicio}' libera-hFim='{$horaFim}' class='btn btn-default libera-object'>Liberar</a></td>";
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