<?php

// page

$noindex=true;
$GLOBALS['zdata']=array(
	'title'=>'' /*can be empty*/ 
	,'h1'=>'Test' // <a href='./img/AKT_5295_G1_2b.jpg'><img src='./img/AKT_5295_G1_2b-198x250.jpg' alt='' height='250' class='alignleft size-medium' /></a>
	,'body'=>"<p>Testseite...</p>
<p><a href='kontakt.php'>Kontakt ></a></p>"
	);


// out
	
include_once('./zp/zana.php'); // global fns

out_page(); 


?>