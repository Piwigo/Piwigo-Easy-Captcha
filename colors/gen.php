<?php
define('PHPWG_ROOT_PATH', '../../../');
include(PHPWG_ROOT_PATH . 'include/common.inc.php');

defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

if (isset($_GET['admin']))
{
  is_admin() or die('Hacking attempt!');
  
  $conf['EasyCaptcha']['colors'] = array_merge($conf['EasyCaptcha']['colors'], $_GET);
}

include_once(EASYCAPTCHA_PATH . 'include/functions.inc.php');
include_once(EASYCAPTCHA_PATH . 'colors/CaptchaColors.class.php');

$captcha = new CaptchaColors();

$captcha->generate(isset($_GET['admin']));
