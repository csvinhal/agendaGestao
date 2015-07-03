<?php

class Validate{
    
    // Cria uma fun��o para validar o nome
    public static function validarNome($nome){
            $erros = array();
            $NovoNome = str_replace(' ', '', $nome);
            //$formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
            if(!isset($NovoNome[3])){
                $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $erros[] = "Nome deve conter pelo menos 4 caracteres!";
                $erros[] = "</div>";
                $retorno = implode('', $erros);
                $_SESSION['Mensagem'] = $retorno;
            }/*else if(!preg_match($formato, $NovoNome)){
                $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $erros[] = "O nome deve conter somente letras!";
                $erros[] = "</div>";
                $retorno = implode('', $erros);
                $_SESSION['Mensagem'] = $retorno;
            }*/else{
                return false;
            }
    }
    
    // Cria uma fun��o para validar o sobrenome
    public static function validarSobreNome($sobrenome){
            $erros = array();
            $snome = str_replace(' ', '', $sobrenome);
            $formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
            if(!isset($snome[3])){
                $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $erros[] = "O sobrenome deve conter pelo menos 4 caracteres!";
                $erros[] = "</div>";
                $retorno = implode('', $erros);
                $_SESSION['Mensagem'] = $retorno;
            /*}else if(!preg_match($formato, $snome)){
                $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
                $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
                $erros[] = "O sobrenome deve conter somente letras!";
                $erros[] = "</div>";
                $retorno = implode('', $erros);
                $_SESSION['Mensagem'] = $retorno;
            */}else{
                return false;
            }
    }
    
    // Cria uma fun��o para validar a senha
    public static function validarSenha($senha){
        $erros = array();
        $NovaSenha = str_replace(' ', '', $senha);
        $formato='/^[A-Za-z0-9]+([0-9])*$/';
        if(!isset($NovaSenha[5])){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A senha deve conter pelo menos 6 caracteres!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        /*}else if(!preg_match($formato, $NovaSenha)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A senha deve conter somente letras e n&uacute;meros!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        */}else{
            return false;
        }
    }
    
    //fun��o para validar se a senha e a confirma��o da senha s�o iguais.
    public static function confirmaSenha($senha, $conSenha){
        $erros = array();
        if($senha !== $conSenha){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A senha e a confirma&ccedil;&atilde;o da senha devem ser iguais!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        }else{
            return false;
        }
    }
    
    public static function mascara_string($mascara,$string)
    {
       $string = str_replace(" ","",$string);
       for($i=0;$i<strlen($string);$i++)
       {
          $mascara[strpos($mascara,"#")] = $string[$i];
       }
       return $mascara;
    }
    
    public static function verificaVazio($var){
        if(empty($var)){
            return true;
        }else{
            return false;
        }
    }
	
    function validateEmail($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "E-mail inv&aacute;lido!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        }else{
            return false;
        }
    }
    
    
    // Cria uma fun��o para validar a razaosocial
    public static function validarRazaoSocial($razaosocial){
        $erros = array();
        $rz = str_replace(' ', '', $razaosocial);
        $formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
        if(!isset($rz[4])){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A raz&atilde;o social deve conter pelo menos 4 caracteres!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        /*}else if(!preg_match($formato, $rz)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A raz&atilde;o social deve conter somente letras!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        */}else{
            return false;
        }
    }
    
    // Cria uma fun��o para validar o nome fantasia
    public static function validarNomeFantasia($nomefantasia){
        $erros = array();
        $nf = str_replace(' ', '', $nomefantasia);
        $formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
        if(!isset($nf[3])){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O nome fantasia deve conter pelo menos 4 caracteres!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        /*}else if(!preg_match($formato, $nf)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O nome fantasia deve conter somente letras!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        */}else{
            return false;
        }
    }
    
    
    //cria fun��o para validar o CNPJ
    public static function validarCNPJ( $cnpj ) {
        $erros = array();
        // Deixa o CNPJ com apenas n�meros
        $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );

        // Garante que o CNPJ � uma string
        $cnpj = (string)$cnpj;

        // O valor original
        $cnpj_original = $cnpj;

        // Captura os primeiros 12 n�meros do CNPJ
        $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );

        /**
         * Multiplica��o do CNPJ
         *
         * @param string $cnpj Os digitos do CNPJ
         * @param int $posicoes A posi��o que vai iniciar a regress�o
         * @return int O
         *
         */
        function multiplica_cnpj( $cnpj, $posicao = 5 ) {
            // Vari�vel para o c�lculo
            $calculo = 0;

            // La�o para percorrer os item do cnpj
            for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
                    // C�lculo mais posi��o do CNPJ * a posi��o
                    $calculo = $calculo + ( $cnpj[$i] * $posicao );

                    // Decrementa a posi��o a cada volta do la�o
                    $posicao--;

                    // Se a posi��o for menor que 2, ela se torna 9
                    if ( $posicao < 2 ) {
                            $posicao = 9;
                    }
            }
            // Retorna o c�lculo
            return $calculo;
        }

        // Faz o primeiro c�lculo
        $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );

        // Se o resto da divis�o entre o primeiro c�lculo e 11 for menor que 2, o primeiro
        // D�gito � zero (0), caso contr�rio � 11 - o resto da divis�o entre o c�lculo e 11
        $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );

        // Concatena o primeiro d�gito nos 12 primeiros n�meros do CNPJ
        // Agora temos 13 n�meros aqui
        $primeiros_numeros_cnpj .= $primeiro_digito;

        // O segundo c�lculo � a mesma coisa do primeiro, por�m, come�a na posi��o 6
        $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
        $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );

        // Concatena o segundo d�gito ao CNPJ
        $cnpj = $primeiros_numeros_cnpj . $segundo_digito;

        // Verifica se o CNPJ gerado � id�ntico ao enviado
        if ( $cnpj !== $cnpj_original ) {
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "CNPJ inv&aacute;lido!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        }else{
            return false;
        }
    }
    
    // Cria uma fun��o para validar a cidade
    public static function validarCidade($cidade){
        $erros = array();
        $ncidade = str_replace(' ', '', $cidade);
        $formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
        if(!isset($ncidade[3])){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A cidade deve conter pelo menos 4 caracteres!".$ncidade;
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        /*}else if(!preg_match($formato, $ncidade)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "A cidade deve conter somente letras!".$ncidade;
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        */}else{
            return false;
        }
    }
    
    // Cria uma fun��o para validar o bairro
    public static function validarBairro($bairro){
        $erros = array();
        $NovoBairro = str_replace(' ', '', $bairro);
        $formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
        if(!isset($NovoBairro[3])){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O bairro deve conter pelo menos 4 caracteres!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        /*}else if(!preg_match($formato, $NovoBairro)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O bairro deve conter somente letras!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        */}else{
            return false;
        }
    }
    
    // Cria uma fun��o para validar o logradouro
    public static function validarLogradouro($logradouro){
        $erros = array();
        $nlogradouro =  str_replace(' ', '', $logradouro);
        $formato='/^[A-Za-z]+(\s[A-Za-z]+)*$/';
        if(!isset($nlogradouro[3])){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O logradouro deve conter pelo menos 4 caracteres!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        /*}else if(!preg_match($formato, $nlogradouro)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O logradouro deve conter somente letras!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        */}else{
            return FALSE;
        }
    }
    
    //Cria uma fun��o para validar o logradouro
    public static function validarNumero($numero){
        $erros = array();
        if(!is_numeric($numero)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O n&uacute;mero do endere�o deve conter apenas n�meros!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;            
        }else if(empty($numero)){
            $erros[] = "<div class=\"alert alert-danger alert-dismissable\">";
            $erros[] = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
            $erros[] = "O campo n&uacute;mero n&atilde;o pode ficar em branco!";
            $erros[] = "</div>";
            $retorno = implode('', $erros);
            $_SESSION['Mensagem'] = $retorno;
        }else{
            return FALSE;
        }
    }
    
    public static  function removeNaoNumeros($string){
            return preg_replace( '/[^0-9]/', '', $string);
        }

}    
?>