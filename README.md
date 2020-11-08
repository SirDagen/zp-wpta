# ZANAPRESS (ZP)
ZANAPRESS (ZP) is an adapter to use WordPress themes (WPTA) to create small websites that don't need to be maintained.

Each web page consists of one php-file, which you can modify directly in the text editor of your choice (ZP is **not** a CMS). You simply create a PHP file, which then populates the design (theme) of your choice with the given contents, e.g.

```php

//$noindex=true; // do you want this page to be indexed by robots?

// define content
$GLOBALS['zdata']=[
    'title'=>'', /*can be empty*/ 
    'h1'=>'Home', 
    'body'=>"<p>Create small web sites with ZANAPRESS that do not need to be maintained.</p>
<p><a href='contact.php'>Contact Us ></a></p>",
    ];

// output page
include_once('./zp/zana.php'); // run ZANAPRESS
out_page(); 
```
## Initial setup

- Download the ZP archive, unzip it and upload it to your website
- Download the minnow theme either from the wordpress or github website and put it the following directory "./layout/minnow" (it shouldnt matter if a theme hasn’t been updated for a long time). Links: https://wordpress.org/themes/minnow/ -or- https://github.com/theme/minnow-wpcom
- Keep the zana.css file in that directory (you can add and overwrite css declarations directly in this file). Thus you have to make less changes to the theme.
- Open the footer.php file, delete everything between <div class="site-info"> and </div> and replace it with the following line:
<?php echo $GLOBALS['zconf']['foot'][0]."  &nbsp; &ndash; &nbsp; ".$GLOBALS['zconf']['foot'][1]; ?>
- Open your website and everything should run fine, like in this screenshot:

| ![Screenshot](https://raw.githubusercontent.com/SirDagen/zp-wpta/main/img/screenshot_minnow.png)
| ------ |

- Now you can change the ./zp/_conf.php file to modify your navigation and footer and place new web pages in the root directory.
```php
	,'navi'=>array(
		'index'=>array('Start', './')
		,'software'=>array('Test', 'test.php')
		,'kontakt'=>array('Kontakt', 'kontakt.php')
		)
```

ZP helps keeping the web secure. 
