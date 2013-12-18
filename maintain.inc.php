<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class EasyCaptcha_maintain extends PluginMaintain
{
  private $installed = false;

  function install($plugin_version, &$errors=array())
  {
    global $conf;

    if (empty($conf['EasyCaptcha']))
    {
      $default_config = array(
        'activate_on' => array(
          'picture'     => true,
          'category'    => true,
          'register'    => true,
          'contactform' => true,
          'guestbook'   => true,
          ),
        'comments_action' => 'reject',
        'challenge' => 'random',
        'drag' => array(
          'theme' => 'icons',
          'size'  => 50,
          'nb'    => 5,
          'bg1'   => '#F7F7F7',
          'bg2'   => '#E5E5E5',
          'obj'   => '#FFFFFF',
          'sel'   => '#C8FF96',
          'bd1'   => '#DDDDDD',
          'bd2'   => '#555555',
          'txt'   => '#222222',
          ),
        'tictac' => array(
          'size'  => 128,
          'bg1'   => '#F7F7F7',
          'bg2'   => '#E5E5E5',
          'bd'    => '#DDDDDD',
          'obj'   => '#00B4F7',
          'sel'   => '#F7B400',
          ),
        'lastmod' => time(),
        );

      $conf['EasyCaptcha'] = serialize($default_config);
      conf_update_param('EasyCaptcha', $conf['EasyCaptcha']);
    }
    else
    {
      $old_conf = is_string($conf['EasyCaptcha']) ? unserialize($conf['EasyCaptcha']) : $conf['EasyCaptcha'];

      if (empty($old_conf['lastmod']))
      {
        $old_conf['lastmod'] = time();
      }

      $conf['EasyCaptcha'] = serialize($old_conf);
      conf_update_param('EasyCaptcha', $conf['EasyCaptcha']);
    }

    $this->installed = true;
  }

  function activate($plugin_version, &$errors=array())
  {
    if (!$this->installed)
    {
      $this->install($plugin_version, $errors);
    }
  }

  function deactivate()
  {
  }

  function uninstall()
  {
    conf_delete_param('EasyCaptcha');
  }
}