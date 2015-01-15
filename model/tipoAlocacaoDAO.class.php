<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tipoAlocacaoDAO
 *
 * @author Cristiano
 */
include_once '../config/database.class.php';
include_once 'TipoAlocacao.class.php';

class tipoAlocacaoDAO {

    // Cria um atributo chamado conexao para armazenar uma instância da conexão
    private $stmt;

    /* Cria um método construtor para armazenar a instância da conexão na
     * No atributo conexao
    */
    public function __construct($db){
			// Armazena a instância da conexao no atributo conexao
			$this->stmt = $db;
    }
    
    // usado pelo select drop-down list
    function read(){
        //seleciona todos os dados
        $stmt = $this->stmt->prepare("SELECT * FROM tipoalocacao ORDER BY desAloc");
        $stmt->execute();
 
        return $stmt;
    }
    
    // usado para ler a descrição do tipo de alocação pelo ID
    function readName($param){
        $stmt = $this->stmt->prepare("SELECT desAloc FROM tipoalocacao WHERE idTipAloc = ? limit 0,1"); 
        $stmt->bindValue(1, $param);
            // Executa a instrução SQL
        $stmt->execute();
        $num = $stmt->rowCount();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        return $row['desAloc'];
        }
    }
}
