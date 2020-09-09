<?php

// Load old settings

if (file_exists('/opt/unetlab/html/includes/config.php')) {
    require_once('/opt/unetlab/html/includes/config.php');
} else {
    exit (0);
}


// get proxy settings from apt

$proxy=shell_exec("echo -n $(apt-config dump  | grep 'Acquire::https::Proxy' | sed -e 's,.*Proxy *\".*//,,'  -e  's,/\".*,,')");
if ( $proxy != '' ) {
	define('PROXY_SERVER', preg_replace('/:.*/','',$proxy)); 
	define('PROXY_PORT', (int) preg_replace('/.*:/','',$proxy));
}

/*
 *  Load  settings
 *  This will NOT overwrite previouss ettings
 */
if (file_exists('/opt/unetlab/html/includes/config.yml')) {
        $config_yml = yaml_parse_file('/opt/unetlab/html/includes/config.yml');
        if (!defined('RADIUS_SERVER_IP')  && isset($config_yml['radius'][0]['server']) )  define('RADIUS_SERVER_IP', $config_yml['radius'][0]['server']);
        if (!defined('RADIUS_SERVER_PORT') && isset($config_yml['radius'][0]['port'])  )  define('RADIUS_SERVER_PORT', $config_yml['radius'][0]['port']);
        if (!defined('RADIUS_SERVER_SECRET') && isset($config_yml['radius'][0]['secret'])  )  define('RADIUS_SERVER_SECRET', $config_yml['radius'][0]['secret']);
        if (!defined('RADIUS_SERVER_IP_2') && isset($config_yml['radius'][1]['server']) ) define('RADIUS_SERVER_IP_2', $config_yml['radius'][1]['server']);
        if (!defined('RADIUS_SERVER_PORT_2') && isset($config_yml['radius'][1]['port']) ) define('RADIUS_SERVER_PORT_2', $config_yml['radius'][1]['port']);
        if (!defined('RADIUS_SERVER_SECRET_2') && isset($config_yml['radius'][1]['secret']) ) define('RADIUS_SERVER_SECRET_2', $config_yml['radius'][1]['secret']);
        if (!defined('PROXY_SERVER') && isset($config_yml['proxy'][0]['server']) )  define('PROXY_SERVER',$config_yml['proxy'][0]['server']);
        if (!defined('PROXY_PORT') && isset($config_yml['proxy'][0]['port']) ) define('PROXY_PORT',$config_yml['proxy'][0]['port']);
        if (!defined('MINDISK') && isset($config_yml['mindisk']) ) define('MINDISK',$config_yml['mindisk']);
        if (!defined('TEMPLATE_DISABLED') && isset($config_yml['template_disabled']) ) DEFINE('TEMPLATE_DISABLED', '.'.$config_yml['template_disabled']) ;
}




if (!defined('MINDISK')) define('MINDISK',3);
if (!defined('TEMPLATE_DISABLED')) define('TEMPLATE_DISABLED', '.missing');
if (!defined('RADIUS_SERVER_IP')) define('RADIUS_SERVER_IP', '0.0.0.0');
if (!defined('RADIUS_SERVER_PORT')) define('RADIUS_SERVER_PORT', 1812 );
if (!defined('RADIUS_SERVER_SECRET')) define('RADIUS_SERVER_SECRET', 'secret');
if (!defined('RADIUS_SERVER_IP_2')) define('RADIUS_SERVER_IP_2', '0.0.0.0');
if (!defined('RADIUS_SERVER_PORT_2')) define('RADIUS_SERVER_PORT_2', 1812 );
if (!defined('RADIUS_SERVER_SECRET_2')) define('RADIUS_SERVER_SECRET_2', 'secret');
if (!defined('PROXY_SERVER')) define ('PROXY_SERVER','');
if (!defined('PROXY_PORT')) define ('PROXY_PORT','');

// create yml files

//config.yml
$config_yml['radius'][0]['server']=RADIUS_SERVER_IP;
$config_yml['radius'][0]['port']=RADIUS_SERVER_PORT;
$config_yml['radius'][0]['secret']=RADIUS_SERVER_SECRET;
$config_yml['radius'][1]['server']=RADIUS_SERVER_IP_2;
$config_yml['radius'][1]['port']=RADIUS_SERVER_PORT_2;
$config_yml['radius'][1]['secret']=RADIUS_SERVER_SECRET_2;
$config_yml['proxy'][0]['server']=PROXY_SERVER;
$config_yml['proxy'][0]['port']=PROXY_PORT;
$config_yml['mindisk']=MINDISK;
$config_yml['template_disabled']=preg_replace('/^\./','',TEMPLATE_DISABLED);
yaml_emit_file ( '/opt/unetlab/html/includes/config.yml' , $config_yml );


$i=0;
foreach ( $custom_templates as $key => $value ) {
	$custom_templates_yml['custom_templates'][$i]['name'] = $key ;
	$custom_templates_yml['custom_templates'][$i]['listname'] = $value ;
	$i++;
}
yaml_emit_file ( '/opt/unetlab/html/includes/custom_templates.yml', $custom_templates_yml );

rename ( '/opt/unetlab/html/includes/config.php','/opt/unetlab/html/includes/config.old' ); 
?>

