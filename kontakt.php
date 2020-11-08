<?php

// page

$body=<<<EOT
<p>Sie erreichen uns unter: </p>
<table class="bio">
<tbody>
<tr>
<td class="c1">Mail</td>
<td class="c2">example@example.com</td>
</tr>
<tr>
<td class="c1">Telefon</td>
<td class="c2">0000-00000000</td>
</tr>
</tbody>
</table>
<p><a href='./'>Info ></a></p>
EOT;
// <p><a href="/unser-ansatz/">Unser Ansatz</a> &gt;</p>

$noindex=true;
$GLOBALS['zdata']=array(
	'title'=>''
	,'h1'=>'Kontakt'
	,'body'=>$body
	);

	
// out
	
include_once('./zp/zana.php'); // global fns
out_page();



?>