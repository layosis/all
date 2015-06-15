<?php

/**
 * @file
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $caption: The caption for this table. May be empty.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */
 $total=0;
 $fi = isset($_GET['field_fecha_pago_value']['min']['date']) ? str_replace('/', '-', $_GET['field_fecha_pago_value']['min']['date']) : date('d-m-Y');
 $ff = isset($_GET['field_fecha_pago_value']['max']['date']) ? str_replace('/', '-', $_GET['field_fecha_pago_value']['max']['date']) : date('d-m-Y');
 $fi = date('Y-m-d', strtotime($fi));
 $ff = date('Y-m-d', strtotime($ff));

  $lecturas = EntitiesData::getDatas(
    'node',
    'lectura', 
    "medidor(direccion) monto monto_excedente fecha_pago monto_pagado", 
    "monto_pagado>0");

  dpm($lecturas);

  $Incumplimiento = EntitiesData::getDatas('node', 'porpagar', "concepto", "activo=1,monto>0,periodicidad=incumplirPago")[0]['concepto'];

  $filas = array();
  foreach ($lecturas as $key => $row) {
    $fecha = $row['fecha_pago'];
    $idmedidor = $row['medidor']['nid'];
    $filas[]=array(
      'date' => $fecha,
      'medidor' => key($row['medidor']['direccion']),
      'monto' => $row['monto_pagado'] - $row['monto_excedente'],
      'detalle' => 'Tarifa basica por consumo de agua',
    );
    $filas[]=array(
      'date' => $fecha,
      'medidor' => key($row['medidor']['direccion']),
      'monto' => $row['monto_excedente'],
      'detalle' => 'Excedente por consumo de agua',
    );
    $pagosIndividuales=array();

    $pagosIndividuales =  EntitiesData::getDatas('node', 'multa', 
          "fecha_pago monto_pagado motivo", 
          "monto_pagado>0,fecha_pago=$fecha,medidor=$idmedidor");

    foreach ($pagosIndividuales as $pa_indiv => $pa_row) {
      $filas[]=array(
        'date' => $pa_row['fecha_pago'],
        'medidor' => key($row['medidor']['direccion']),
        'monto' => $pa_row['monto_pagado'],
        'detalle' => $pa_row['motivo'],
      );
    }
  }

  foreach ($rows as $row_count => $row) {
//   dpm($row_count);
  }

?>

<table <?php if ($classes) { print 'class="'. $classes . '" '; } ?><?php print $attributes; ?>>
   <?php if (!empty($title) || !empty($caption)) : ?>
     <caption><?php print $caption . $title; ?></caption>
  <?php endif; ?>
  <?php if (!empty($header)) : ?>
    <thead>
      <tr>
        <?php foreach ($header as $field => $label): ?>
          <th <?php if ($header_classes[$field]) { print 'class="'. $header_classes[$field] . '" '; } ?>>
            <?php print $label; ?>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>

    <?php
      $total=0;  
      foreach ($filas as $rowsTables => $datos) { 
        $total += $datos['monto'];
        $class = ($rowsTables % 2 == 0 ? 'odd' : 'even');
      ?>
      <tr <?php print 'class="' . $class .'"'; ?>>
          <td><?php print date('d-m-Y', strtotime($datos['date'])); ?></td>
          <td class='views-field views-field-field-medidor'>
          <?php print $datos['medidor']; ?>
          </td>
          <td class='views-field views-field-field-monto-pagado'>
          <?php print $datos['monto']; ?>
          </td>
          <td class='views-field views-field-field-motivo'><?php print $datos['detalle']; ?>
          </td>
      </tr>

    <?php } ?>

      <tr <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
          <td></td>
          <td class='views-field views-field-field-medidor'>
          <b>TOTAL :</b>
          </td>
          <td class='views-field views-field-field-monto-pagado'>
          <b><?php print $total; ?></b>
          </td>
          <td class='views-field views-field-field-motivo'>
          </td>
      </tr>

  </tbody>
</table>
