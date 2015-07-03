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
include_once 'TipoAlocacao.class.php';

class tipoAlocacaoDAO {

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
        $stmt = $this->stmt->prepare("SELECT * FROM tipoalocacao WHERE ativo = 'S' ORDER BY desAloc");
        $stmt->execute();
        return $stmt;
    }
    
    function buscaTodos(){
        //seleciona todos os dados
        $stmt = $this->stmt->prepare("SELECT * FROM tipoalocacao ORDER BY desAloc");
        $stmt->execute();
        return $stmt;
    }
    
    //Busca um tipo de alocacao espec�fico
    function buscaTipAloc($objTipAloc){
        $stmt = $this->stmt->prepare("SELECT * FROM tipoalocacao WHERE idTipAloc = ? limit 0,1"); 
        $stmt->bindValue(1, $objTipAloc->idTipAloc);
        
        // Executa a instru��o SQL
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $objTipAloc->desAloc = $row['desAloc'];
            $objTipAloc->ativo = $row['ativo'];
    }
    
    // usado para ler a descri��o do tipo de aloca��o pelo ID
    function readName($param){
        $stmt = $this->stmt->prepare("SELECT desAloc FROM tipoalocacao WHERE idTipAloc = ? limit 0,1"); 
        $stmt->bindValue(1, $param);
            // Executa a instru��o SQL
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        return $row['desAloc'];
        }
    }
    
    function insereTpAlocacao($objTipAloc){
        $stmt = $this->stmt->prepare("INSERT INTO tipoalocacao (idTipAloc, desAloc, ativo)  VALUES(NULL, ?, ?)");
        $stmt->bindValue(1, $objTipAloc->desAloc);
        $stmt->bindValue(2, $objTipAloc->ativo);
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
    function atualizaTpAlocacao($objTipAloc){
        $stmt = $this->stmt->prepare("UPDATE tipoalocacao SET desAloc = ?, ativo = ?
                                        WHERE idTipAloc = ?");
        $stmt->bindValue(1, $objTipAloc->desAloc);
        $stmt->bindValue(2, $objTipAloc->ativo);
        $stmt->bindValue(3, $objTipAloc->idTipAloc);
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}