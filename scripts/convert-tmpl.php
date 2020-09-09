#!/usr/bin/env php
<?php
$tmpl=substr($argv[1],0,-4);
include($tmpl.'.php');
yaml_emit_file ( $tmpl.'.yml' , $p ) ;
rename($tmpl.'.php',$tmpl.'.php.converted');
?>
