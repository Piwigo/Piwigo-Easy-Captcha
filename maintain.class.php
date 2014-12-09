<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class EasyCaptcha_maintain extends PluginMaintain
{
  var $default_config = array(
    'activate_on' => array(
      'picture'     => true,
      'category'    => true,
      'register'    => true,
      'contactform' => true,
      'guestbook'   => true,
      ),
    'comments_action' => 'reject',
    'guest_only'      => true,
    'challenges'      => array('tictac', 'drag', 'colors'),
    'lastmod'         => 0,
    
    'tictac' => array(
      'size'  => 128,
      'bg1'   => '#F7F7F7',
      'bg2'   => '#E5E5E5',
      'bd'    => '#DDDDDD',
      'obj'   => '#00B4F7',
      'sel'   => '#F7B400',
      ),
    'drag' => array(
      'theme' => 'icons',
      'size'  => 50,
      'nb'    => 5,
      'bd'    => '#D8D8D8',
      'bg1'   => '#F7F7F7',
      'bg2'   => '#E5E5E5',
      'obj'   => '#FFFFFF',
      'sel'   => '#C8FF96',
      'bd1'   => '#DDDDDD',
      'bd2'   => '#555555',
      'txt'   => '#222222',
      ),
    'colors' => array(
      'size'  => 36,
      'nb'    => 4,
      'bd'    => '#D8D8D8',
      'bg1'   => '#F7F7F7',
      'bg2'   => '#E5E5E5',
      'bd1'   => 'transparent',
      'bd2'   => '#777777',
      'ar1'   => '#777777',
      'ar2'   => '#EFEFEF',
      ),
    );
  
  function __construct($plugin_id)
  {
      parent::__construct($plugin_id);
      
      $this->default_conf['lastmod'] = time();
  }
        
  function install($plugin_version, &$errors=array())
  {
    global $conf;

    if (empty($conf['EasyCaptcha']))
    {
      conf_update_param('EasyCaptcha', $this->default_config, true);
    }
    else
    {
      $old_conf = safe_unserialize($conf['EasyCaptcha']);

      if (empty($old_conf['lastmod']))
      {
        $old_conf['lastmod'] = $this->default_conf['lastmod'];
      }
      if (!isset($old_conf['guest_only']))
      {
        $old_conf['guest_only'] = $this->default_conf['guest_only'];
      }
      
      if (!isset($old_conf['colors']))
      {
        $old_conf['colors'] = $this->default_config['colors'];
        $old_conf['drag']['bd'] = $this->default_config['drag']['bd'];
        
        if ($old_conf['challenge'] == 'random')
        {
          $old_conf['challenges'] = $this->default_config['challenges'];
        }
        else
        {
          $old_conf['challenges'] = array($old_conf['challenge']);
        }
      }

      conf_update_param('EasyCaptcha', $old_conf, true);
    }
  }

  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }

  function uninstall()
  {
    conf_delete_param('EasyCaptcha');
  }
}