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
 session_start(); 
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
    <?php foreach ($rows as $row_count => $row): ?>
      <tr <?php if ($row_classes[$row_count]) { print 'class="' . implode(' ', $row_classes[$row_count]) .'"';  } ?>>
        <?php foreach ($row as $field => $content): ?>
          <td <?php if ($field_classes[$field][$row_count]) { print 'class="'. $field_classes[$field][$row_count] . '" '; } ?><?php print drupal_attributes($field_attributes[$field][$row_count]); ?>>
            <?php print $content;?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<h1>TOTALES: </h1>
<table class="views-table cols-12">
<thead>
  <tr>
    <th class="views-field">Valores</th>
    <th class="views-field">Ene.</th>
    <th class="views-field">Feb.</th>
    <th class="views-field">Mar.</th>
    <th class="views-field">Abr.</th>
    <th class="views-field">May.</th>
    <th class="views-field">Jun.</th>
    <th class="views-field">Jul.</th>
    <th class="views-field">Ago.</th>
    <th class="views-field">Sep.</th>
    <th class="views-field">Oct.</th>
    <th class="views-field">Nov.</th>
    <th class="views-field">Dic.</th>
    <th class="views-field">Total</th>
  </tr>
</thead>
<tbody>
  <tr class="odd views-row-first views-row-last">
    <td class="views-field">
     <?php print '<div class="pago-td">Pagado:</div><div class="pago-td">Deuda:</div>'; ?>
    </td>
    <?php for($i = 1; $i <= 12; $i++)  {  ?>
      <td class="views-field">
        <?php print '<div class="pago-td">'.$_SESSION['mesT'][$i][0].'</div><div class="pago-td">'.$_SESSION['mesT'][$i][1].'</div>'; ?>
      </td>
   <?php }  
   for($i=1; $i <= count($_SESSION['mesT']); $i++){
     $ttp += $_SESSION['mesT'][$i][0];
     $ttd += $_SESSION['mesT'][$i][1];
   }
   ?>  
    <td class="views-field"><?php print '<div class="pago-td">'.$ttp.'</div><div class="pago-td">'.$ttd.'</div>'; ?></td>
  </tr>
  </tbody>
</table>


