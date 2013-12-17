<?php

#   CARREGA CLASSE DE ESTADOS
require_once 'classes/Estado.class.php';

#   CARREGA CLASSE DE PROCESSAMENTO
require_once 'classes/Processamento.class.php';

$regra = (int)$_POST['regra'];

#CARREGA LISTA DE ESTADOS
$estados = Estado::getEstadosBrasil();

$processo = new Processamento($regra, $estados);

unset($estados);

$processo->processaCores();

//  INICIALIZA BUFFER
ob_start("sanitize_output");

/*============================================================================
 * Relação de cores e estados
 ============================================================================*/

# APRESENTA TITULO
echo '<h2>Relação de cores e estados</h2><br />';

#IMPRIME RESULTADOS
echo '<table><tr><th>Estado</th><th>Divisas</th><th>Cor</th></tr>';

foreach($processo->estados as $resultado):

    $divisas = implode(', ', $resultado->divisas);

    printf("<tr><td>%s</td><td>%s</td><td style='background:#%s'>%s</td></tr>",
            $resultado->nome.' - '.$resultado->sigla, $divisas, $resultado->cor, $resultado->cor);

endforeach;

echo '</table>';

/*============================================================================
 * TOTAL DE CORES
 ============================================================================*/

printf( '<h1>Total de cores: %s</h1>', count($processo->coresUsadas) );

    #IMPRIME RESULTADOS
    echo '<table><tr><th>Cor</th><th>Estados</th></tr>';

        foreach($processo->coresPorEstado as $cor => $qtd):
            printf("<tr><td>%s</td><td>%s</td></tr>", $cor, $qtd);
        endforeach;

    echo '</table>';

/*============================================================================
 * MAPA COLORIDO
 ============================================================================*/

print( '<h1>Mapa Colorido</h1>' );

    #ordena mapa por sigla e por nivel
    $nivel = array();

    foreach ($processo->estados as $s => $n)
        $nivel[$s] = $n->nivel;

    array_multisort($nivel, SORT_ASC, $processo->estados);

    #imprime mapa pintado
    echo '<img src="ui/images/brasil400.png" class="map" width="400" height="352" usemap="#mapabrasil"/>
        <map id="mapabrasil" name="mapabrasil">';

        foreach($processo->estados as $resultado):

            echo "<area data-maphilight='{\"strokeWidth\":1,\"fillColor\":\"{$resultado->cor}\",\"fillOpacity\":1}' ";
            echo " shape='poly' coords=\"{$resultado->coords}\" href='' alt='{$resultado->sigla}' title='{$resultado->nome} - {$resultado->sigla}' />";

        endforeach;

    echo '</map>';

/*============================================================================
 * DESCARREGA BUFFER
 ============================================================================*/

//  HEADERS
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//  IMPRIME BUFFER
@ob_end_flush();

?>