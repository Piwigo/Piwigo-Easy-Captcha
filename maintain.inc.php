<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

defined('EASYCAPTCHA_ID') or define('EASYCAPTCHA_ID', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . EASYCAPTCHA_ID . '/include/install.inc.php');

function plugin_install()
{
  easycaptcha_install();
  define('easycaptcha_installed', true);
}

function plugin_activate()
{
  if (!defined('easycaptcha_installed'))
  {
    easycaptcha_install();
  }
}

function plugin_uninstall()
{
  pwg_query('DELETE FROM '.CONFIG_TABLE.' WHERE param="EasyCaptcha" LIMIT 1');
}