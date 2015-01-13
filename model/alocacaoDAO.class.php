<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of alocacaoDAO
 *
 * @author Cristiano
 */

    include_once '../config/database.class.php';
    include_once 'Alocacao.class.php';

    
class alocacaoDAO {
    
    // Cria um atributo chamado conexao para armazenar uma instância da conexão
    private $conn;

    /* Cria um método construtor para armazenar a instância da conexão na
     * No atributo conexao
    */
    public function __construct($db){
			// Armazena a instância da conexao no atributo conexao
			$this->conn = $db;
    }
    
    function create($alocacao){
        // to get time-stamp for 'created' field
        //$this->getTimestamp();
        $stmt = $this->conn->prepare("INSERT INTO alocacao(dataAlocacao, horaInicio, horaFim,
            idColaborador, desAlocacao, idTipAloc, confirmado, idUsuario, idCliente)
                                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$alocacao->dataAlocacao);
        $stmt->bindValue(2,$alocacao->horaInicio);
        $stmt->bindValue(3,$alocacao->horaFim);
        $stmt->bindValue(4,$alocacao->idColaborador);
        $stmt->bindValue(5,$alocacao->desAlocacao);
        $stmt->bindValue(6,$alocacao->idTipAloc);
        $stmt->bindValue(7,$alocacao->confirmado);
        $stmt->bindValue(8,$alocacao->idUsuario);
        $stmt->bindValue(9,$alocacao->idCliente);
        
        // Executa a instrução SQL
        if($stmt->execute()){
            return true;
        }else{
            return false;
        } 
    }
    
    function update($alocacao, $chave){
            $stmt = $this->conn->prepare("UPDATE alocacao SET dataAlocacao = ?, horaInicio = ?, 
                horaFim = ?, idColaborador = ?, desAlocacao = ?, idTipAloc = ?, confirmado = ?, 
                idUsuario = ?, idCliente = ? 
                WHERE dataAlocacao = ? AND horaInicio = ? AND
                horaFim = ? AND idColaborador = ?)");
            // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
            $stmt->bindValue(1,$alocacao->dataAlocacao);
            $stmt->bindValue(2,$alocacao->horaInicio);
            $stmt->bindValue(3,$alocacao->horaFim);
            $stmt->bindValue(4,$alocacao->idColaborador);
            $stmt->bindValue(5,$alocacao->desAlocacao);
            $stmt->bindValue(6,$alocacao->idTipAloc);
            $stmt->bindValue(7,$alocacao->confirmado);
            $stmt->bindValue(8,$alocacao->idUsuario);
            $stmt->bindValue(9,$alocacao->idCliente);
            $stmt->bindValue(10,$chave->dataAlocacao);
            $stmt->bindValue(11,$chave->horaInicio);
            $stmt->bindValue(12,$chave->horaFim);
            $stmt->bindValue(13,$chave->idColaborador);
            
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
              
    }

    function date_converter($_date = null) {
	$format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
	if ($_date != null && preg_match($format, $_date, $partes)) {
		return $partes[3].'-'.$partes[2].'-'.$partes[1];
	}
	return false;
    }
    
    //busca todos os alocacaos
    function search(){
        $stmt = $this->conn->prepare("SELECT * FROM alocacao ORDER BY dataAlocacao");
        $stmt->execute();
        
        return $stmt;
    }
    
    function searchMorning($dataAlocacao, $param){
        $stmt = $this->conn->prepare("SELECT * FROM alocacao 
                                        WHERE idColaborador = ?
                                        AND dataAlocacao = ?
                                        AND horaInicio = '08:00' 
                                        AND horaFim = '12:00'");
        $stmt->bindValue(1, $param);
        $stmt->bindValue(2, $dataAlocacao);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return $stmt;
        }else{
            return false;
        }
    }
    
    function searchAfternoon($dataAlocacao, $param){
        $stmt = $this->conn->prepare("SELECT * FROM alocacao 
                                        WHERE idColaborador = ?
                                        AND dataAlocacao = ?
                                        AND horaInicio = '14:00' 
                                        AND horaFim = '18:00'");
        $stmt->bindValue(1, $param);
        $stmt->bindValue(2, $dataAlocacao);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return $stmt;
            /*$row = $stmt->fetch(PDO::FETCH_ASSOC);
            $alocacao->idCliente = $row['idCliente'];*/
        }else{
            return false;
        }
    }
    
    //Numero de alocacaos cadastrados
    public function countAll(){
    
        $stmt = $this->conn->prepare("SELECT * FROM alocacao");
        $stmt->execute();
        $num = $stmt->rowCount();

        return $num;
    }
    
    //Deleta o alocacao
    function delete($alocacao){
        $stmt = $this->conn->prepare("DELETE FROM alocacao WHERE dataAlocacao = ? AND horaInicio = ? AND
                horaFim = ? AND idColaborador = ?");
        $stmt->bindValue(1,$alocacao->dataAlocacao);
        $stmt->bindValue(2,$alocacao->horaInicio);
        $stmt->bindValue(3,$alocacao->horaFim);
        $stmt->bindValue(4,$alocacao->idColaborador);

        if($resultado = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}
?>