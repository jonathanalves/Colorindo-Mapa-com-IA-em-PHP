<?php

class Processamento{

    # INFORMA REGRA UTILIZADA
    var $regra;

    # GUARDA LISTA DE ESTADOS
    var $estados = array();

    #INICIA LISTA DE CORES
    var $coresUsadas = array();

    #INICIA LISTA DE CONTAGEM DE ESTADOS POR COR
    var $coresPorEstado = array();

    #GUARDA PONTEIRO DO LACO DE ESTADOS
    var $lacoEstados = 0;

    public function __construct( $regra, $estados ) {

        $this->regra = (int)$regra;

        #ORDENA LISTAGEM DE ESTADOS POR NÚMERO DE DIVISAS
        $di = array();

        foreach ($estados as $s => $e)
            $di[$s] = count($e->divisas);

        array_multisort($di, SORT_DESC, $estados);

        $this->estados = $estados;

    }

    /**
     * EFETUA PROCESSAMENTO DAS CORES
     */
    public function processaCores(){

        #EFETUA LAÇO COM ESTADOS
        foreach ( $this->estados as $sigla => $vetor ):

            #aumenta contador do laço de estados
            ++$this->lacoEstados;

            #CAPTURA REFERENCIA DA LISTA
            $estado = clone $this->estados[$sigla];

            #CAPTURA LISTA DE CORES USADAS NAS DIVISAS
            $ignoreColor = $this->capturaCoresDivisas($estado);

            #CAPTURA COR PARA O ESTADO
            $cor = $this->defineCor($ignoreColor);

            #ADICIONA COR A LISTA DE CORES
            if( !in_array($cor, $this->coresUsadas) )
                $this->coresUsadas[] = $cor;

            #ADICIONA COR AO ESTADO
            $this->estados[$sigla]->cor = $cor;

            #ADICIONA CONTADOR DA COR
            @++$this->coresPorEstado[$cor];

        endforeach;

        #ORNENA LISTA DE ESTADO PELA SIGLA
        ksort($this->estados);

    }

    /**
     * CAPTURA UM ARRAY DE CORES UTILIZADAS PELAS DIVISAS DO ESTADO
     * @param Estado $estado
     * @return type
     */
    public function capturaCoresDivisas(Estado $estado){

        $ignoreColor = array();
        
        #EFETUA LAÇO COM ESTADOS DA DIVISA
        foreach( $estado->divisas as $d ):

            #SE ALGUM ESTADO DE DIVISA TIVER COR DEFINIDA
            #ADICIONA A LISTA DE IGNORADAS
            if( $this->estados[$d]->cor )
                $ignoreColor[] = $this->estados[$d]->cor;

        endforeach;

        return $ignoreColor;

    }

    /**
     * CRIA UMA COR PARA USAR DENTRO DO ESTADO
     * RESPEITANDO UMA LISTA DE CORES À IGNORAR
     * @param type $ignoreColor
     * @return type
     */
    public function defineCor( $ignoreColor = array() ){

        #SETA VARIAVEL DE COR COMO FALSE
        $cor = FALSE;

        #EFETUA LAÇO COM CORES JÁ USADAS
        foreach($this->coresUsadas as $c):

            #se a cor estiver na lista de ignoradas, pula
            if( in_array( $c, $ignoreColor ) )
                continue;

            #se a cor tiver sido usada mais vezes do que a média (EstadosPercorridos/CoresUsadas) && regra de execução == 2, pula
            if( $this->regra == 2 && ( (int)@$this->coresPorEstado[$c] > ( $this->lacoEstados / count($this->coresUsadas) ) ) )
                continue;

            #SE A COR NAO TIVER SIDO USADA, QUEBRA O LAÇO
            $cor = $c;
            break;

        endforeach;

        #CASO A COR SEJA INVALIDA, CRIA CORES ATÉ SER UMA COR VALIDA
        while( ( !$cor || in_array($cor, $ignoreColor) ) ):
            $cor = $this->criaCor();
        
            if( in_array($cor, $this->coresUsadas ) )
                $cor = FALSE;
        
        endwhile;

        return $cor;

    }

    /**
     * cria cor aleatoria
     * @return type
     */
    public function criaCor(){

        $hash = md5('color' . rand(0, 255)); // modify 'color' to get a different palette
        return sprintf('%02X%02X%02X',
            hexdec(substr($hash, 0, 2)), // r
            hexdec(substr($hash, 2, 2)), // g
            hexdec(substr($hash, 4, 2))); //b

    }

}

?>
