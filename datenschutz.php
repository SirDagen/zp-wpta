<?php

// page

$body=<<<EOT
<h3 class='first'>Datenschutz</h3>
<p>Die Betreiber dieser Seiten...</p>

EOT;

$noindex=true;
$GLOBALS['zdata']=array(
	'title'=>''
	,'h1'=>'Datenschutzerklärung'
	,'body'=>$body
	);
	
	
// out
	
include_once('./zp/zana.php'); // global fns
out_page();



?>