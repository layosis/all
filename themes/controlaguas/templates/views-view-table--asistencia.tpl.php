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

if(isset($_POST['fecha'])){
    global $list;
    global $user;
    $list = $_POST['control'];
}

global $i;


$faltabd = db_query("select field_monto_value
from node n inner join field_data_field_concepto c on(n.nid = c.entity_id) inner join field_data_field_monto using (entity_id)
where field_concepto_value ='Falta a asamblea'")->fetchfield();

$retrasobd = db_query("select field_monto_value
from node n inner join field_data_field_concepto c on(n.nid = c.entity_id) inner join field_data_field_monto using (entity_id)
where field_concepto_value ='Retraso a asamblea'")->fetchfield();

$_SESSION['falta'] = $faltabd > 0 ? $faltabd : 0;
$_SESSION['retraso'] = $retrasobd > 0 ? $retrasobd : 0;

$i=0;

?>
<form name="form1" class="node-form view-asistencia-form" action="/asistencia" method="post" id="asistencia-view-form" accept-charset="UTF-8">
<input type="hidden" name="fecha" value="<?php print date('Y-m-d'); ?>">
<table id="table" <?php if ($classes) { print 'class=display "'. $classes . '" '; } ?><?php print $attributes; ?>>
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
            <?php print $content; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<input type="submit" id="edit-submit" name="op" value="Guardar" class="form-submit">
</form>
