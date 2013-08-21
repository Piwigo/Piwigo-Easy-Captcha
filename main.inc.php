<?php
/*
Plugin Name: Easy Captcha
Version: auto
Description: An fun antibot system for comments, registration, ContactForm and GuestBook.
Plugin URI: auto
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

// TODO : test on mobile
if (mobile_theme())
{
  return;
}

defined('EASYCAPTCHA_ID') or define('EASYCAPTCHA_ID', basename(dirname(__FILE__)));
define('EASYCAPTCHA_PATH' , PHPWG_PLUGINS_PATH . EASYCAPTCHA_ID . '/');
define('EASYCAPTCHA_ADMIN', get_root_url() . 'admin.php?page=plugin-' . EASYCAPTCHA_ID);
define('EASYCAPTCHA_VERSION', 'auto');


add_event_handler('init', 'easycaptcha_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'easycaptcha_plugin_admin_menu');
}
else
{
  add_event_handler('init', 'easycaptcha_document_init');
  add_event_handler('loc_end_section_init', 'easycaptcha_section_init', EVENT_HANDLER_PRIORITY_NEUTRAL+30);
}


// plugin init
function easycaptcha_init()
{
  global $conf, $pwg_loaded_plugins;

  if (
    EASYCAPTCHA_VERSION == 'auto' or
    $pwg_loaded_plugins[EASYCAPTCHA_ID]['version'] == 'auto' or
    version_compare($pwg_loaded_plugins[EASYCAPTCHA_ID]['version'], EASYCAPTCHA_VERSION, '<')
  )
  {
    include_once(EASYCAPTCHA_PATH . 'include/install.inc.php');
    easycaptcha_install();

    if ( $pwg_loaded_plugins[EASYCAPTCHA_ID]['version'] != 'auto' && EASYCAPTCHA_VERSION != 'auto' )
    {
      $query = '
UPDATE '. PLUGINS_TABLE .'
SET version = "'. EASYCAPTCHA_VERSION .'"
WHERE id = "'. EASYCAPTCHA_ID .'"';
      pwg_query($query);

      $pwg_loaded_plugins[EASYCAPTCHA_ID]['version'] = EASYCAPTCHA_VERSION;
    }
  }

  load_language('plugin.lang', EASYCAPTCHA_PATH);
  $conf['EasyCaptcha'] = unserialize($conf['EasyCaptcha']);
}


// modules : picture comment & register
function easycaptcha_document_init()
{
  global $conf, $user;

  if (!is_a_guest()) return;

  if ( script_basename() == 'register' && $conf['EasyCaptcha']['activate_on']['register'] )
  {
    $conf['EasyCaptcha']['template'] = 'register';
    include(EASYCAPTCHA_PATH . 'include/register.inc.php');
  }
  else if ( script_basename() == 'picture' && $conf['EasyCaptcha']['activate_on']['picture'] )
  {
    $conf['EasyCaptcha']['template'] = 'comment';
    include(EASYCAPTCHA_PATH . 'include/picture.inc.php');
  }

}

// modules : album comment & contact & guestbook
function easycaptcha_section_init()
{
  global $conf, $pwg_loaded_plugins, $page;

  if (!is_a_guest() || !isset($page['section'])) return;

  if (
    $page['section'] == 'categories' && isset($page['category']) &&
    isset($pwg_loaded_plugins['Comments_on_Albums']) &&
    $conf['EasyCaptcha']['activate_on']['category']
    )
  {
    $conf['EasyCaptcha']['template'] = 'comment';
    include(EASYCAPTCHA_PATH . 'include/category.inc.php');
  }
  else if ( $page['section'] == 'contact' && $conf['EasyCaptcha']['activate_on']['contactform'] )
  {
    $conf['EasyCaptcha']['template'] = 'contactform';
    include(EASYCAPTCHA_PATH . 'include/contactform.inc.php');
  }
  else if ( $page['section'] == 'guestbook' && $conf['EasyCaptcha']['activate_on']['guestbook'] )
  {
    $conf['EasyCaptcha']['template'] = 'guestbook';
    include(EASYCAPTCHA_PATH . 'include/guestbook.inc.php');
  }
}


// admin
function easycaptcha_plugin_admin_menu($menu)
{
  array_push($menu, array(
    'NAME' => 'Easy Captcha',
    'URL' => EASYCAPTCHA_ADMIN,
    ));
  return $menu;
}