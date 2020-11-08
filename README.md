# ZANAPRESS (ZP)
ZANAPRESS (ZP) is an adapter to use WordPress themes (WPTA) to create small websites that don't need to be maintained.

The websites consist of one php-file each, which you can adapt directly in the text editor of your choice. ZP is not a CMS. You simply create a PHP file, which then populates the design (theme) of your choice, e.g.

```php

//$noindex=true;

// page
$GLOBALS['zdata']=array(
	'title'=>'' /*can be empty*/ 
	,'h1'=>'Home' 
	,'body'=>"<p>Create small web sites with ZANAPRESS that do not need to be maintained.</p>
<p><a href='contact.php'>Contact Us ></a></p>"
	);


// out
include_once('./zp/zana.php'); // global fns
out_page(); 
```
