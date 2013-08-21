<?php
defined('EASYCAPTCHA_ID') or die('Hacking attempt!');

function easycaptcha_install()
{
  global $conf;

  if (empty($conf['EasyCaptcha']))
  {
    $easycaptcha_default_config = array(
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
      );

    $conf['EasyCaptcha'] = serialize($easycaptcha_default_config);
    conf_update_param('EasyCaptcha', $conf['EasyCaptcha']);
  }
}