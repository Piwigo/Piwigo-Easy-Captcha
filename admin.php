<?php
defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

include_once(EASYCAPTCHA_PATH . 'include/functions.inc.php');

global $pwg_loaded_plugins;
$loaded = array(
  'contactform' => isset($pwg_loaded_plugins['ContactForm']),
  'category' => isset($pwg_loaded_plugins['Comments_on_Albums']),
  'guestbook' => isset($pwg_loaded_plugins['GuestBook']),
  'cryptocaptcha' => isset($pwg_loaded_plugins['CryptograPHP']),
  );

if ($loaded['cryptocaptcha'])
{
  $page['warnings'][] = l10n('We detected that Crypto Captcha plugin is available on your gallery. Both plugins can be used at the same time, but you should not under any circumstances activate both of them on the same page.');
}

$modules = array();
foreach ($conf['EasyCaptcha_modules'] as $module)
{
  $modules[$module] = load_easycaptcha_class($module);
}

if (isset($_POST['submit']))
{
  if (!isset($_POST['activate_on'])) $_POST['activate_on'] = array();
  if (empty($_POST['challenges']))   $_POST['challenges'] = $conf['EasyCaptcha_modules'];

  $conf['EasyCaptcha'] = array(
    'activate_on' => array(
      'picture'     => in_array('picture', $_POST['activate_on']),
      'category'    => in_array('category', $_POST['activate_on']) || !$loaded['category'],
      'register'    => in_array('register', $_POST['activate_on']),
      'contactform' => in_array('contactform', $_POST['activate_on']) || !$loaded['contactform'],
      'guestbook'   => in_array('guestbook', $_POST['activate_on']) || !$loaded['guestbook'],
      ),
    'comments_action' => $_POST['comments_action'],
    'guest_only' => isset($_POST['guest_only']),
    'challenges' => $_POST['challenges'],
    'lastmod' => time(),
    );
  
  foreach ($modules as $module => $captcha)
  {
    $conf['EasyCaptcha'][$module] = $captcha->post_conf();
  }

  conf_update_param('EasyCaptcha', $conf['EasyCaptcha']);
  $page['infos'][] = l10n('Information data registered in database');
}

foreach ($modules as $module => $captcha)
{
  $captcha->pre_conf();
}

$template->assign(array(
  'easycaptcha' => $conf['EasyCaptcha'],
  'easycaptcha_modules' => $conf['EasyCaptcha_modules'],
  'easycaptcha_loaded' => $loaded,
  'EASYCAPTCHA_PATH' => EASYCAPTCHA_PATH,
  'EASYCAPTCHA_ABS_PATH' => realpath(EASYCAPTCHA_PATH) . '/',
  ));

$template->set_filename('plugin_admin_content', realpath(EASYCAPTCHA_PATH . 'template/admin.tpl'));
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
