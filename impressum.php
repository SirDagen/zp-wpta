<?php

// page

$body=<<<EOT
<p>Der Betreiber dieser Webseite ist...</p>
EOT;

$noindex=true;
$GLOBALS['zdata']=array(
	'title'=>''
	,'h1'=>'Impressum'
	,'body'=>$body
	);

	
// out
	
include_once('./zp/zana.php'); // global fns
out_page();



?>