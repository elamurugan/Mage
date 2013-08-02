<?php

	ini_set('max_execution_time', 3600);
	ini_set("memory_limit","256M");

	$xml = simplexml_load_file('app/etc/local.xml', NULL, LIBXML_NOCDATA);

	$db['host'] = $xml->global->resources->default_setup->connection->host;
	$db['name'] = $xml->global->resources->default_setup->connection->dbname;
	$db['user'] = $xml->global->resources->default_setup->connection->username;
	$db['pass'] = $xml->global->resources->default_setup->connection->password;
	$db['pref'] = $xml->global->resources->db->table_prefix;
	if(!isset($_GET['clean'])) $_GET['clean'] = 'cache';
	if($_GET['clean'] == 'db') clean_log_tables();
	if($_GET['clean'] == 'cache') clean_var_directory();

	function clean_log_tables() {
		global $db;
		
		$tables = array(
			'dataflow_batch_export',
			'dataflow_batch_import',
			'log_customer',
			'log_quote',
			'log_summary',
			'log_summary_type',
			'log_url',
			'log_url_info',
			'log_visitor',
			'log_visitor_info',
			'log_visitor_online',
			'index_event',
			'report_event',
			'report_compared_product_index',
			'report_viewed_product_index',
			'catalog_compare_item',
			'catalogindex_aggregation',
			'catalogindex_aggregation_tag',
			'catalogindex_aggregation_to_tag'
		);
		
		mysql_connect($db['host'], $db['user'], $db['pass']) or die(mysql_error());
		mysql_select_db($db['name']) or die(mysql_error());
		
		foreach($tables as $v => $k) {
			@mysql_query('TRUNCATE `'.$db['pref'].$k.'`');
		}
		echo "<br/> DB Log cleared"; 
	}

	function clean_var_directory() {
		
		$script = "rm -rf var/cache/*";
		$results = system($script,$retval);

		echo " Cache cleared, RETURN VALUE: $retval\n";

		$script = "rm -rf var/log/*";
		$results = system($script,$retval);
		echo "<br/> Log cleared, RETURN VALUE: $retval\n"; 
		
		// $script = "rm -rf var/session/*";
		// $results = system($script,$retval);
		// echo "<br/> session cleared, RETURN VALUE: $retval\n"; 
		
		$script = "rm -rf var/report/*";
		$results = system($script,$retval);
		echo "<br/> report cleared, RETURN VALUE: $retval\n"; 
	}	
		