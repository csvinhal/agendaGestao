<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once '../config/functions.php';
sec_session_start();

$relatorio = filter_input(INPUT_GET,'relatorio', FILTER_SANITIZE_URL);

if(isset($relatorio)){
    include_once '../model/alocacaoDAO.class.php';
    include_once '../config/database.class.php';
    
    switch($relatorio){
        case 'consultorAlocacao':
            if($_POST){
                //Instancia uma nova conexaos
                $database = new Database();
                $db = $database->getConnection();
                
                $idColaborador = filter_input(INPUT_POST,'idColaborador', FILTER_SANITIZE_NUMBER_INT);
                $dataIniAloc = filter_input(INPUT_POST,'dataIniAlocacao', FILTER_SANITIZE_STRING);
                $dataFimAloc = filter_input(INPUT_POST,'dataFimAlocacao', FILTER_SANITIZE_STRING);
                
                $alocacaoDAO = new alocacaoDAO($db);
                if(empty($dataIniAloc)){
                    $dataIniAloc = '1900-01-01';
                }else{
                    $dataIniAloc = $alocacaoDAO->date_converter($dataIniAloc);
                }
                
                if(empty($dataFimAloc)){
                    $dataFimAloc = '2099-12-31';
                }else{
                    $dataFimAloc = $alocacaoDAO->date_converter($dataFimAloc); 
                }
                $alocacaoDAO->relatorioConsultorAlocacao($idColaborador, $dataIniAloc, $dataFimAloc);
            }
    }
}

