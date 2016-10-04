<?php 
	require_once('db_connect.php');
	require_once('functions.php');

	$per_page=30;

	isset($_GET['page']) && $_GET['page']>=1 ? $page = $_GET['page'] : $page = 1;
	isset($_GET['order_by']) ? $order_by =str_replace(' ', '_', $_GET['order_by']).", day, start_time" : $order_by = 'day, start_time';
	
	$offset = $per_page * ($page-1);
	$query = "SELECT * FROM classes";
	$query .= " WHERE ";
	$query .= " 1 ";
	
	if(isset($_GET['day'])) {
		$day = ($_GET['day']);
		$query .= " && day = ('{$day}') " ;
	}

	if(isset($_GET['class_type'])) {
		$class_type = ($_GET['class_type']);
		$query .= " && class_type = ('{$class_type}') " ;
	}

	if(isset($_GET['module_code'])) {
		$module_code = ($_GET['module_code']);
		$query .= " && module_code = ('{$module_code}') " ;
	}

	if(isset($_GET['module_title'])) {
		$module_title = ($_GET['module_title']);
		$query .= " && module_title = ('{$module_title}') " ;
	}

	if(isset($_GET['lecturer'])) {
		$lecturer = ($_GET['lecturer']);
		$query .= " && lecturer = ('{$lecturer}') " ;
	}

	if(isset($_GET['group'])) {
		$group = ($_GET['group']);
		$query .= " && `group` LIKE ('%{$group}%') " ;
	}

	if(isset($_GET['block'])) {
		$block = ($_GET['block']);
		$query .= " && block = ('{$block}') " ;
	}

	if(isset($_GET['room'])) {
		$room = ($_GET['room']);
		$query .= " && room = ('{$room}') " ;
	}

	$query .= " ORDER BY $order_by ";
	$query .= " LIMIT {$per_page}";
	$query1 = $query . " OFFSET {$offset}";
	echo $query1;

	$offset = $per_page*$page; //offset for next page

	$query2 = $query . " OFFSET {$offset}";
	$next_result = mysqli_fetch_assoc(mysqli_query($conn, $query2));

	$classes_set = mysqli_query($conn, $query1);
	if (! $classes_set) {
		die(mysqli_error($conn));
	}
	$classes = array();
	while ($class = mysqli_fetch_assoc($classes_set)) {
		$classes[] = $class;
	} 

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Saved routines</title>
 	<link rel="stylesheet" type="text/css" href="style/table.css">
 	<link rel="stylesheet" type="text/css" href="style/default.css">
 </head>
 <body>
 
<div>
	<a href="<?php echo form_link_with(basename(__FILE__), array()) ?>">
		<button id='reset' <?php  if(empty($_GET)) echo 'disabled="disabled"'?>>
			Reset all
		</button>
	</a>
</div>

 <div class="pagination">
	 	<a href="<?php echo $uri = get_previous_page(basename(__FILE__), $page, $_GET); ?>
	 	 ">
	 	 <button id="previous" <?php if ($page==1) echo 'disabled="disabled"';?>>
			PREVIOUS
	 	 </button>
		</a>
		
		<a href="<?php echo get_next_page(basename(__FILE__), $page ,$_GET) ?>">
		<button id="next" <?php echo empty($next_result) ? 'disabled="disabled"' : '';?> 
		>NEXT
		</button> </a>
 </div>

<?php 
	if($page>1) {
?>
	
<?php
	}
	if ($next_result) {
?>

<?php	
	}
 ?>

 <table>
 <tr>
 	<?php 
 		foreach ($classes[0] as $heading => $value) {
 			echo '<th>';
 			echo '<a href="' . link_with_order_by(basename(__FILE__), $_GET, $heading) . '">';
 			echo ucfirst(str_replace('_', ' ', $heading));
 			echo "</a>";
 			echo '</th>';
 		}
 	 ?>
 </tr>
 <?php 
 		foreach ($classes as $class) {
 			echo '<tr>';
 			foreach ($class as $heading => $value) {
	 			echo '<td>';
	 			echo '<a href="'. form_target_link(basename(__FILE__), $_GET, $heading, $value) .'">';
	 			if (stripos($heading, 'time')) {
					echo display_time($value); 				
	 			} else {
		 			echo $value;
	 			}
	 			echo "</a>";
	 			echo '</td>';
 			}
 			echo '</tr>';
 		}
  ?>
 </table>

</body>
</html>



