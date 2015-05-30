<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */

global $totalmes;
$node = node_load($output);
$ids = $node->field_medidor['und'][0]['target_id'];

$datestr = strtotime($node->field_mes['und'][0]['value']);
$mesactual = date("Y-m-d", $datestr);
$mesini = date('Y-m-d',mktime(0, 0, 0, (date("m", $datestr) - 1)  , 1, date("Y", $datestr)));

$multa=0;
$dias=0;
$multaEntity = EntitiesData::getDatas('node', 'porpagar', "monto limite_dias", 
                                          "activo=1,monto>0,periodicidad=incumplirPago,fecha<$mesactual");

foreach ($multaEntity AS $rows){
   $multa = $rows['monto'];
   $dias = $rows['limite_dias'];
}

/*   Meses adeudados
     --------------- */
$lecturas = EntitiesData::getDatas(
   'node',
   'lectura', 
   "monto monto_excedente fecha_lectura mes", 
   "monto_pagado=0,medidor=$ids,monto>0,mes<$mesactual");

$montodeuda=0;
$mesesdeuda=0;
foreach ($lecturas AS $rows){
   $montodeuda += $rows['monto'];
   $montodeuda += $multa;
   $mesesdeuda +=1;
}

/*   Pagos mensuales
     --------------- */
$pagosMensuales = EntitiesData::getDatas('node', 'porpagar', "monto fecha", 
                                          "activo=1,monto>0,periodicidad=Mensual,fecha<$mesactual");
$porpagar = 0;
foreach ($pagosMensuales AS $rows){
   $porpagar += $rows['monto'];
}

$porpagar = $mesesdeuda * $porpagar;

/*   Pagos individuales
     ------------------ */
$pagosInd = 0;
$pagosIndividuales =  EntitiesData::getDatas('node', 'multa', "monto monto_pagado", "monto>0,monto_pagado=0,medidor=$ids,fecha>=$mesini,fecha<$mesactual");
foreach ($pagosIndividuales AS $rows){
   $pagosInd += $rows['monto'];
}






$numFac = "<div id='deuda'><div class ='numfacturas'>Número de facturas que adeuda : ". $mesesdeuda ."</div>";
$numFac .= "<div class ='numfacturas'>Importe que adeuda : ". ($montodeuda + $porpagar + $pagosInd) ."</div></div>";

print $numFac; 
?>