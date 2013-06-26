<?php

	$schema = $bd->getSchema();

	function print_tables($schema) {

		$html = "<ul id='schema_tables' class='schema_tables'>";
		foreach ($schema as $key => $value) {
			$html .= table_ui($key, $value);
		}
		return $html."</ul>";
	}

	function table_ui($name, $fields) {

		$html = "<li class='schema_table'>";
		$html .= "<div class='schema_title'>$name</div>";
		foreach ($fields as $key => $value) {
			$html .= "<div class='schema_column'>$key</div>";
		}
		$html .= "</li>";
		return $html;
	}

?>

<div class="container-fluid">
	<div class="row-fluid">
		<h5 align="center">Tabelas e colunas</h5>
		<?php echo print_tables($schema); ?>
	</div>
</div>
