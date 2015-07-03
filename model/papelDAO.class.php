<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of papelDAO
 *
 * @author Cristiano
 */
include_once 'Papel.class.php';
    
class papelDAO {
    // Cria um atributo chamado conexao para armazenar uma inst�ncia da conex�o
    private $stmt;

    /* Cria um m�todo construtor para armazenar a inst�ncia da conex�o na
     * No atributo conexao
    */
    public function __construct($db){
			// Armazena a inst�ncia da conexao no atributo conexao
			$this->stmt = $db;
    }
    
    // usado pelo select drop-down list
    function read(){
        //seleciona todos os dados
        $stmt = $this->stmt->prepare("SELECT * FROM papel ORDER BY descPapel");
        $stmt->execute();
 
        return $stmt;
    }
    
    // usado para ler a descri��o do papel pelo ID
    function readName($param){
        $stmt = $this->stmt->prepare("SELECT descPapel FROM papel WHERE idPapel = ? limit 0,1"); 
        $stmt->bindValue(1, $param);
            // Executa a instru��o SQL
        $stmt->execute();
        $num = $stmt->rowCount();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        return $row['descPapel'];
        }
    }
}