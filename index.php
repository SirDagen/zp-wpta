<?php

//$noindex=true; // do you want this page to be indexed by robots?

// define content
$GLOBALS['zdata']=array(
	'title'=>'' /*can be empty*/ 
	,'h1'=>'Start' 
	,'body'=>"<p>Mit ZANAPRESS kleine Webpräsenzen erstellen, die nicht gewartet werden müssen.</p>
<p><a href='kontakt.php'>Kontakt ></a></p>"
	);


// output page
include_once('./zp/zana.php'); // run ZP
out_page(); 


?>
