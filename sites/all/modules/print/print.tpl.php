<?php

/**
 * @file
 * Default theme implementation to display a printer-friendly version page.
 *
 * This file is akin to Drupal's page.tpl.php template. The contents being
 * displayed are all included in the $content variable, while the rest of the
 * template focuses on positioning and theming the other page elements.
 *
 * All the variables available in the page.tpl.php template should also be
 * available in this template. In addition to those, the following variables
 * defined by the print module(s) are available:
 *
 * Arguments to the theme call:
 * - $node: The node object. For node content, this is a normal node object.
 *   For system-generated pages, this contains usually only the title, path
 *   and content elements.
 * - $format: The output format being used ('html' for the Web version, 'mail'
 *   for the send by email, 'pdf' for the PDF version, etc.).
 * - $expand_css: TRUE if the CSS used in the file should be provided as text
 *   instead of a list of @include directives.
 * - $message: The message included in the send by email version with the
 *   text provided by the sender of the email.
 *
 * Variables created in the preprocess stage:
 * - $print_logo: the image tag with the configured logo image.
 * - $content: the rendered HTML of the node content.
 * - $scripts: the HTML used to include the JavaScript files in the page head.
 * - $footer_scripts: the HTML  to include the JavaScript files in the page
 *   footer.
 * - $sourceurl_enabled: TRUE if the source URL infromation should be
 *   displayed.
 * - $url: absolute URL of the original source page.
 * - $source_url: absolute URL of the original source page, either as an alias
 *   or as a system path, as configured by the user.
 * - $cid: comment ID of the node being displayed.
 * - $print_title: the title of the page.
 * - $head: HTML contents of the head tag, provided by drupal_get_html_head().
 * - $robots_meta: meta tag with the configured robots directives.
 * - $css: the syle tags contaning the list of include directives or the full
 *   text of the files for inline CSS use.
 * - $sendtoprinter: depending on configuration, this is the script tag
 *   including the JavaScript to send the page to the printer and to close the
 *   window afterwards.
 *
 * print[--format][--node--content-type[--nodeid]].tpl.php
 *
 * The following suggestions can be used:
 * 1. print--format--node--content-type--nodeid.tpl.php
 * 2. print--format--node--content-type.tpl.php
 * 3. print--format.tpl.php
 * 4. print--node--content-type--nodeid.tpl.php
 * 5. print--node--content-type.tpl.php
 * 6. print.tpl.php
 *
 * Where format is the ouput format being used, content-type is the node's
 * content type and nodeid is the node's identifier (nid).
 *
 * @see print_preprocess_print()
 * @see theme_print_published
 * @see theme_print_breadcrumb
 * @see theme_print_footer
 * @see theme_print_sourceurl
 * @see theme_print_url_list
 * @see page.tpl.php
 * @ingroup print
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>">
  <head>

  
    <?php print $head; ?>
    <base href='<?php print $url ?>' />
    <title><?php //print $print_title; ?></title>
    <?php print $scripts; ?>
    <?php if (isset($sendtoprinter)) print $sendtoprinter; ?>
    <?php print $robots_meta; ?>
    <?php if (theme_get_setting('toggle_favicon')): ?>
      <link rel='shortcut icon' href='<?php print theme_get_setting('favicon') ?>' type='image/x-icon' />
    <?php endif; ?>	
    <?php print $css; ?>

  </head>
  
  <link type="text/css" rel="stylesheet" media="screen" href="/<?php echo drupal_get_path('theme', 'bartik'); ?>css/print.css" /> 
  
 
  <link type="text/css" rel="stylesheet" href="/<?php echo drupal_get_path('theme', 'bartik'); ?>/css/print.css" />
  <link type="text/css" rel="stylesheet" href="/<?php echo drupal_get_path('theme', 'bartik'); ?>/print.css" />
  
  <link type="text/css" rel="stylesheet" media="screen" href="/<?php echo drupal_get_path('theme', 'bartik'); ?>css/print.css" /> 

  <body>
    <?php if (!empty($message)): ?>
      <div class="print-message"><?php print $message; ?></div><p />
    <?php endif; ?>
    <?php if ($print_logo): ?>
      <div class="print-logo"><?php print $print_logo; ?></div>
    <?php endif; ?>
    <div class="print-site_name"><?php // print theme('print_published'); ?></div>
    <p />
    <div class="print-breadcrumb"><?php print theme('print_breadcrumb', array('node' => $node)); ?></div>
    <hr class="print-hr" />
    <?php if (!isset($node->type)): ?>
      <h2 class="print-title"><?php //print $print_title; ?></h2>
    <?php endif; ?>
    <div class="print-content">
	
<?php

if($node->type == 'page') {
	print '<table cellpadding="2" cellspacing="0">';
	$file_efq = new EntityFieldQuery();
    $file_efq->entityCondition('entity_type', 'file')
	->propertyCondition('uid', 100);;
    $file_efq_result = $file_efq->execute();
	//print "<pre>";print_r($file_efq_result);exit;
	foreach($file_efq_result['file'] as $file) {
		//print "<pre>";print_r($file);
		$new_file = file_load($file->fid);
		if(!empty($new_file)) {
			//print_r($new_file); exit;
			print '<tr><td><img src="'.file_create_url($new_file->uri).'" /></td></tr>';
		}
	}
	print '</table>';
}
if($node->type == 'trees') {
	$node_basic = node_load(75);
	$field_top_images = field_get_items('node', $node_basic, 'field_top_images');
	if(!empty($field_top_images)) {
		print '<table cellpadding="2" cellspacing="0" nobr="true">';
		for($i=0; $i<count($field_top_images); $i++) {
			print '<tr><td><img src="'.file_create_url($field_top_images[$i]['uri']).'" /></td></tr>';
		}
		print '</table><br/>';
	}
	global $index_nodes;
	$query = new EntityFieldQuery();
	$query->entityCondition('entity_type', 'node')
	  ->entityCondition('bundle', $node->type) // ex. article
	  ->propertyCondition('status', 1) // published nodes
	  ->propertyOrderBy('changed', 'ASC');
	$result = $query->execute();
	$nids = array_keys($result['node']);
	$nodes = node_load_multiple($nids); 
	$nodes2 = $nodes;
	$i = 0;
	foreach($nodes2 as $index_node) {
		$index_nodes[$i] = $index_node;
		$i++;
	}


	function index_tree($printed_tree) {
		global $index_nodes;
		
		$end = $printed_tree + 20; 
		$table = '<br/><table border="1" cellpadding="2" nobr="true">
				<tr nobr="true"><td width="9%"><b>Tree No</b></td><td width="10%"><b>Location</b></td><td width="10%"><b>Common Name</b></td><td width="11%"><b>Scientific Name</b></td>
				<td width="7%"><b>Girth</b></td><td width="7%"><b>Height</b></td><td width="8%"><b>Spread</b></td>
				<td width="7%"><b>Health</b></td><td width="10%"><b>Structure</b></td><td width="10%"><b>Form</b></td><td width="11%"><b>Hazard Rating</b></td>
				</tr>';
		for($i = $printed_tree; $i<$end; $i++) {
			if(isset($index_nodes[$i])) {
				$index_node = $index_nodes[$i];
				$field_index_location = field_get_items('node', $index_node, 'field_location');
				$field_index_common_name = field_get_items('node', $index_node, 'field_common_name');
				$field_index_scientific_name = field_get_items('node', $index_node, 'field_scientific_name');
				$field_index_girth_m = field_get_items('node', $index_node, 'field_girth_m_');
				$field_index_height_m = field_get_items('node', $index_node, 'field_height_m_');
				$field_index_grown_m = field_get_items('node', $index_node, 'field_grown_m_');
				$field_index_health = field_get_items('node', $index_node, 'field_health');
				$field_index_structure = field_get_items('node', $index_node, 'field_structure');
				$field_index_form = field_get_items('node', $index_node, 'field_form');
				$field_index_hazard_rating = field_get_items('node', $index_node, 'field_hazard_rating');
					$table .= '<tr nobr="true">
					<td class="dark">'. $index_node->title .'</td>
					<td class="dark">'. $field_index_location[0]['value'].'</td>
					<td class="dark">'. $field_index_common_name[0]['value'].'</td>
					<td class="dark"><i>'. $field_index_scientific_name[0]['value'].'</i></td>
					<td class="dark">'. $field_index_girth_m[0]['value'].'</td>
					<td class="dark">'. $field_index_height_m[0]['value'].'</td>
					<td class="dark">'. $field_index_grown_m[0]['value'].'</td>
					<td class="dark">'. $field_index_health[0]['value'].'</td>
					<td class="dark">'. $field_index_structure[0]['value'].'</td>
					<td class="dark">'. $field_index_form[0]['value'].'</td>
					<td class="dark">'. $field_index_hazard_rating[0]['value'].'</td>
					</tr>';		
				}
			}
			$table .='</table>';
			print $table;
	}
		
		$tree_counter = 0;
		foreach($nodes as $node):
			if($tree_counter%20 == 0) {
				index_tree($tree_counter);
			}
			$tree_counter++;
			$field_location = field_get_items('node', $node, 'field_location');
			$field_common_name = field_get_items('node', $node, 'field_common_name');
			$field_scientific_name = field_get_items('node', $node, 'field_scientific_name');
            $field_girth_m = field_get_items('node', $node, 'field_girth_m_');
			$field_height_m = field_get_items('node', $node, 'field_height_m_');
			$field_grown_m = field_get_items('node', $node, 'field_grown_m_');
				
			$field_trunk_condition = field_get_items('node', $node, 'field_trunk_condition');
			$field_trunk_observations = field_get_items('node', $node, 'field_trunk_observations');
			$field_trunks_condition = field_get_items('node', $node, 'field_trunks_condition');
			$field_tree_canopy = field_get_items('node', $node, 'field_tree_canopy');
			$field_pruning_history = field_get_items('node', $node, 'field_pruning_history');
			$field_wind_funneling = field_get_items('node', $node, 'field_wind_funneling');
			$field_soil_conditions = field_get_items('node', $node, 'field_soil_conditions');
			
			
			
			$field_tree_root_base = field_get_items('node', $node, 'field_tree_root_base');
			$field_pest_disease = field_get_items('node', $node, 'field_pest_disease');
			$field_trunk_condition = field_get_items('node', $node, 'field_trunk_condition');
			$field_tree_care_management_optio = field_get_items('node', $node, 'field_tree_care_management_optio');
			$field_crown_structure = field_get_items('node', $node, 'field_crown_structure');
			$field_vigour_growth = field_get_items('node', $node, 'field_vigour_growth');
			$field_recommendation = field_get_items('node', $node, 'field_recommendation');
			$field_health = field_get_items('node', $node, 'field_health');
			$field_structure = field_get_items('node', $node, 'field_structure');
			$field_form = field_get_items('node', $node, 'field_form');
			$field_hazard_rating = field_get_items('node', $node, 'field_hazard_rating');
			$field_mitigation = field_get_items('node', $node, 'field_mitigation');
			$field_image = field_get_items('node', $node, 'field_image');
			
			$field_image = field_get_items('node', $node, 'field_image');
			
			?>
			
				<table cellpadding="0" cellspacing="0">	
					
					<tr nobr="true">
						<table cellpadding="5" border="1">
						<tr><td colspan="3" style="font-size:9px;"><b>Location : </b> <?php print $field_location[0]['value']; ?></td></tr>
						<tr><td colspan="3">&nbsp;</td></tr>
						<tr>
						<td width="20%"><b>Tree Id</b></td>
						<td width="30%"><b>Common Name</b></td>
						<td width="50%" ><b>Scientific Name</b></td></tr>	
							   <tr><td  width="20%"><?php print $node->title; ?></td>
							   <td><?php print $field_common_name[0]['value']; ?></td>
							<td><i><?php print $field_scientific_name[0]['value']; ?></i></td></tr>
	.					</table>
					</tr>
					<tr><td>&nbsp;</td></tr>			
					<tr nobr="true">
						<td colspan="3">
							<table border="1" cellpadding="2">
							<tr><td class="dark" width="9%"><b>Girth(m)</b></td><td width="10%"><b>Height(m)</b></td><td width="11%"><b>Spread(m)</b></td>
							<td width="7%"><b>Health</b></td><td width="10%"><b>Structure</b></td><td width="10%"><b>Form</b></td><td width="15%"><b>Hazard Rating</b></td>
							<td width="28%"><b>Mitigation</b></td></tr>
							<tr>
							<td class="dark"><?php print $field_girth_m[0]['value']; ?></td>
							<td class="dark"><?php print $field_height_m[0]['value']; ?></td>
							<td class="dark"><?php print $field_grown_m[0]['value']; ?></td>
							<td class="dark"><?php print $field_health[0]['value']; ?></td>
							<td class="dark"><?php print $field_structure[0]['value']; ?></td>
							<td class="dark"><?php print $field_form[0]['value']; ?></td>
							<td class="dark"><?php print $field_hazard_rating[0]['value']; ?></td>
							<td class="dark"><?php print $field_mitigation[0]['value']; ?></td>
							</tr>
							</table>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr nobr="true">
						<td class="dark" colspan="2">
						<table border="1" cellpadding="2" width="100%">
							<tr><td><b>Girth(m)</b></td><td><b>Minimum Protecction Zone</b></td></tr>
							<tr><td>≤ 1.0m</td><td>2.0m</td></tr>
							<tr><td>> 1.0m but ≤ 1.5m</td><td>3.0m</td></tr>
							<tr><td>> 1.5m but ≤ 2.0m</td><td>4.0m</td></tr>
							<tr><td>> 2.0m</td><td>5.0m</td></tr>							
						</table>
						</td>
						<td class="dark"><table border="0" cellpadding="2">
						<tr><td class="dark">&nbsp;&nbsp;<b>Trunk Condition :</b> <?php print $field_trunk_condition[0]['value']; ?></td></tr>
						<tr><td class="dark">&nbsp;&nbsp;<b>Trunk observations :</b> <?php print $field_trunk_observations[0]['value']; ?></td></tr>
						<tr><td class="dark">&nbsp;&nbsp;<b>Trunks condition :</b> <?php print $field_trunks_condition[0]['value']; ?></td></tr>
						<tr><td class="dark">&nbsp;&nbsp;<b>Tree Canopy :</b> <?php print $field_tree_canopy[0]['value']; ?></td></tr>
						<tr><td class="dark">&nbsp;&nbsp;<b>Wind funneling :</b> <?php print $field_wind_funneling[0]['value']; ?></td></tr>
						</table>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td style="font-size:9px;"><b>Comments</b></td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr nobr="true">
						<td colspan="3">
							<table border="1" cellpadding="2">							
							<tr><td class="dark"><b>Tree/Root Base</b></td><td class="dark"><b>Pruning history</b></td>
							<td class="dark"><b>Crown Structure</b></td><td class="dark"><b>Vigour/ Growth</b></td></tr>
							<tr>
							<td class="dark"><?php print nl2br($field_tree_root_base[0]['value']); ?></td>
							<td class="dark"><?php print $field_pruning_history[0]['value']; ?></td>
							<td class="dark"><?php print nl2br($field_crown_structure[0]['value']); ?></td>
							<td class="dark"><?php print nl2br($field_vigour_growth[0]['value']); ?></td>
							</tr>
							<tr><td class="dark"><b>Pest/Disease</b></td><td class="dark"><b>Tree Care Management</b></td>
							<td class="dark"><b>Soil conditions</b></td><td class="dark"><b>Recommendation</b></td></tr>
							<tr>
							<td class="dark"><?php print nl2br($field_pest_disease[0]['value']); ?></td>
							<td class="dark"><?php print nl2br($field_tree_care_management_optio[0]['value']); ?></td>
							<td class="dark"><?php print $field_soil_conditions[0]['value']; ?></td>
							<td class="dark"><?php print $field_recommendation[0]['value']; ?></td>							
							</tr>
							</table>
						</td>
					</tr>	
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr><td colspan="4"><table cellpadding="2">
					<?php 
					for($i=0; $i<count($field_image); $i++) {
						print '<tr><td><img src="'.file_create_url($field_image[$i]['uri']).'" /></td></tr>';
					}
					?>
					
					
					</table></td></tr>
					
				</table>
		
		<?php endforeach; 
			$field_bottom_images = field_get_items('node', $node_basic, 'field_bottom_images');
			if(!empty($field_bottom_images)) {
				print '<table cellpadding="2" cellspacing="0" nobr="true">';
				for($i=0; $i<count($field_bottom_images); $i++) {
					print '<tr><td><img src="'.file_create_url($field_bottom_images[$i]['uri']).'" /></td></tr>';
				}
				print '</table>';
			}
}
?>
    <div class="print-footer"><?php print theme('print_footer'); ?></div>
    <hr class="print-hr" />
    <?php if ($sourceurl_enabled): ?>
      <div class="print-source_url">
        <?php print theme('print_sourceurl', array('url' => $source_url, 'node' => $node, 'cid' => $cid)); ?>
      </div>
    <?php endif; ?>
    <div class="print-links"><?php print theme('print_url_list'); ?></div>
    <?php print $footer_scripts; ?>
  </body>
</html>
