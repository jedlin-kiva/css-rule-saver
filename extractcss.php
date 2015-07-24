<?php

	require "css-rule-saver.php";

	$input_html_file = $argv[1] ? $argv[1] : 'public/patterns/03-templates-01-frame/03-templates-01-frame.html';
	$input_css_file = $argv[2] ? $argv[12] : 'public/css/styles.css';
	$output_css_file = $argv[3] ? $argv[3] : 'kiva_nav.css';
	$scope_prefix = $argv[4] ? $argv[4] : '.kiva_nav ';

	// initialize the class
	$crs = new cssRuleSaver;

	// load the CSS & HTML files to compare
	$crs->loadCSS($input_css_file);
	$crs->loadHTML($input_html_file);

	// extract only the CSS rules that affect the given piece of mark-up
	$results = $crs->saveRules($scope_prefix);

	// write out file of extracted css
	file_put_contents($outfile, $results);
	$outfile = fopen($output_css_file,"w");

	print "Css extract done to $outfile\n";
	exit(0);