<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Papel
 *
 * @author Cristiano
 */
class Papel {
	private $idPapel;
	private $descPapel;
	
	// Cria os m�todos Getters e Setters
	public function __get($atributo){
		return $this->$atributo;
	}
	public function __set($atributo, $valor){
		$this->$atributo=$valor;
	}
	// Cria  a fun��o toString da classe
	public function __toString(){
		return $this->nome;
	}
}
