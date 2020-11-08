<?php

# ----- config website -----

/*
themes:
https://wordpress.com/themes
https://de.wordpress.org/themes/browse/featured/

how to use: see zana.php

open theme footer.php, delete everything between <div class="site-info"> ... </div> and replace it with the following:
			<?php echo $GLOBALS['zconf']['foot'][0]."  &nbsp; &ndash; &nbsp; ".$GLOBALS['zconf']['foot'][1]; ?>
*/

/*
If you have put the theme you want to use inside the layout folder, you can apply it via belows config:
	'layout'=>'/minnow' // https://wordpress.org/themes/minnow/ -- https://github.com/theme/minnow-wpcom
	'layout'=>'/syntax' // https://wordpress.org/themes/syntax/
	'layout'=>'/seedlet' // https://wordpress.org/themes/seedlet/
*/

// conf of your website
$GLOBALS['zconf']=array( // $GLOBALS['zconf']['title']
	'layout'=>'/minnow' // theme (situated in ./layout/...)
	, 'menutype'=>0 // 0=std, 1=primary (seedlet) 
	, 'lang'=>'de'
	,'title'=>'Testseite' 
	,'subtitle'=>'Meine Internetpräsenz' 
	,'navi'=>array(
		'index'=>array('Start', './')
		,'software'=>array('Test', 'test.php')
		,'kontakt'=>array('Kontakt', 'kontakt.php')
		)
	,'foot'=>array(
		0=>"<a href='impressum.php'>Impressum</a> • <a href='datenschutz.php'>Datenschutz</a>"
		,1=>"<span style='font-size:0.65em'><a href='http://www.example.com/' target='_blank'>Link</a> • <a href='http://www.example.com/' target='_blank'>Link</a> • <a href='https://github.com/SirDagen/zp-wpta' target='_blank'>ZP</a></span>"
		)
	,'favicon'=>'<link rel="icon" href="./img/icon.png" sizes="128x128">'.chr(10)
	);
	


?>
