<?php
/*
	Fetches the lunch menu, week or day, from restaurant Niagara
	Returns: JSON
	By: Anton Tibblin
*/
header('Content-Type: application/json');
// Includes nice library to parse DOM
require("simple_html_dom.php");
// Fetches the HTML-page
$page = file_get_html('http://restaurangniagara.se/lunch/');
// The days...
$days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");

// Get daily or weekly menu?
$today = false;
if(isset($_GET["today"])){
	$today = true;
}

$json = new ArrayObject();
// Every day is a table on the HTML-page
foreach($page->find('table') as $index => $element){
	// Every dish is a row in the table
	foreach($element->find('tr') as $tr){
		// Columns is in order [0] => category, [1] => title, [2] => price
		$category = $tr->find('td')[0]->plaintext;
		$food = new ArrayObject();
		$food["title"] = $tr->find('td')[1]->plaintext;
		$food["price"] = $tr->find('td')[2]->plaintext;
		if($today == false){
			$json[$days[$index]][$category] = $food;
		}else if(date("N")-1 == $index){
			$json[$category] = $food;
		}
	}
}

// Prints the lunch menu in JSON
echo json_encode($json);
?>