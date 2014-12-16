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
        $stmt = $this->conn->prepare("INSERT INTO alocacao(idAlocacao, desAlocacao, dataAlocacao, 
            horaInicio, horaFim, confirmado, idUsuario, idColaborador, idCliente)
                                        VALUES(null,?, ?, ?, ?, ?, ?, ?, ?)");
        // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$alocacao->desAlocacao);
        $stmt->bindValue(2,$alocacao->dataAlocacao);
        $stmt->bindValue(3,$alocacao->horaInicio);
        $stmt->bindValue(4,$alocacao->horaFim);
        $stmt->bindValue(5,$alocacao->confirmado);
        $stmt->bindValue(6,$alocacao->idUsuario);
        $stmt->bindValue(7,$alocacao->idColaborador);
        $stmt->bindValue(8,$alocacao->idCliente);
        
        // Executa a instrução SQL
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
        $stmt = $this->conn->prepare("SELECT * FROM alocacao ORDER BY nome");
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
            /*$row = $stmt->fetch(PDO::FETCH_ASSOC);
            $alocacao->idCliente = $row['idCliente'];*/
        }else{
            return false;
        }
    }
    
    function searchAfternoon($dataAlocacao, $param){
        $stmt = $this->conn->prepare("SELECT * FROM alocacao 
                                        WHERE idColaborador = ?
                                        AND dataAlocacao = ?
                                        AND horaInicio = '13:30' 
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
    
    function searchCol(){
        $stmt = $this->conn->prepare("SELECT * FROM alocacao WHERE idPapel = ? ORDER BY nome");
        $stmt->bindValue(1, 'C');
        $stmt->execute();
        
        return $stmt;
    }
    
    function readAll($page, $from_record_num, $records_per_page){
        $stmt = $this->conn->prepare("SELECT * 
                                FROM alocacao ORDER BY nome ASC LIMIT {$from_record_num}, {$records_per_page}");

        $stmt->execute();
        return $stmt;
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
        $stmt = $this->conn->prepare("DELETE FROM alocacao WHERE idAlocacao = ?");
        $stmt->bindValue(1, $alocacao->idAlocacao);

        if($resultado = $stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    function readOne($alocacao){
        $stmt = $this->conn->prepare("SELECT * FROM alocacao WHERE idAlocacao = ? LIMIT 0,1");
        $stmt->bindValue(1, $alocacao->idAlocacao);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $alocacao->nome = $row['nome'];
        $alocacao->sobrenome = $row['sobrenome'];
        $alocacao->email = $row['email'];
        $alocacao->idPapel = $row['idPapel'];
    }
    
    function update($alocacao){
        $stmt = $this->conn->prepare("UPDATE alocacao SET nome = ?, sobrenome = ?,
                 email= ?, idPapel = ? WHERE idAlocacao = ?");
        // Adiciona os dados do alocacao no lugar das interrogações da instrução SQL
        $stmt->bindValue(1,$alocacao->nome);
        $stmt->bindValue(2,$alocacao->sobrenome);
        $stmt->bindValue(3,$alocacao->email);
        $stmt->bindValue(4,$alocacao->idPerfil);
        $stmt->bindValue(5,$alocacao->idAlocacao);

        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}

?>
