<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alocacao
 *
 * @author Cristiano
 */
class Alocacao {
        private $idAlocacao;
	private $desAlocacao;
	private $dataAlocacao;
        private $periodo;
	private $horaInicio;
        private $horaFim;
        private $confirmado;
	private $idUsuario;
	private $idColaborador;
	private $idCliente;
	
	
	
	// Cria os métodos Getters e Setters
	public function __get($atributo){
		return $this->$atributo;
	}
	public function __set($atributo, $valor){
		$this->$atributo=$valor;
	}
	// Cria  a função toString da classe
	public function __toString(){
		return $this->nome;
	}
}
