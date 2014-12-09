<?php
define('PHPWG_ROOT_PATH', '../../../');
include(PHPWG_ROOT_PATH . 'include/common.inc.php');

defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

include_once(EASYCAPTCHA_PATH . 'include/functions.inc.php');
include_once(EASYCAPTCHA_PATH . 'drag/CaptchaDrag.class.php');

$captcha = new CaptchaDrag();

$captcha->get_image(@$_GET['theme'], @$_GET['image']);
