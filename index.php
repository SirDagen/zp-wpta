<?php

// page

//$noindex=true;
$GLOBALS['zdata']=array(
	'title'=>'' /*can be empty*/ 
	,'h1'=>'Start' 
	,'body'=>"<p>Mit ZANAPRESS kleine Webpräsenzen erstellen, die nicht gewartet werden müssen.</p>
<p><a href='kontakt.php'>Kontakt ></a></p>"
	);


// out
	
include_once('./zp/zana.php'); // global fns

out_page(); 


?>