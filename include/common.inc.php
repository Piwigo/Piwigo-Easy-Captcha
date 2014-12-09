<?php
defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

global $template;

include_once(EASYCAPTCHA_PATH . 'include/functions.inc.php');

load_language('plugin.lang', EASYCAPTCHA_PATH);

// choose random challenge
$conf['EasyCaptcha']['challenge'] = $conf['EasyCaptcha']['challenges'][ array_rand($conf['EasyCaptcha']['challenges']) ];

$captcha = load_easycaptcha_class($conf['EasyCaptcha']['challenge']);

$conf['EasyCaptcha']['key'] = $conf['EasyCaptcha']['challenge'] . '-' . $captcha->get_key();
$conf['EasyCaptcha']['hint'] = $captcha->get_hint();

$template->assign(array(
  'EASYCAPTCHA' => $conf['EasyCaptcha'],
  'EASYCAPTCHA_PATH' => EASYCAPTCHA_PATH,
  'EASYCAPTCHA_ABS_PATH' => realpath(EASYCAPTCHA_PATH).'/',
  ));

$template->set_filename('EasyCaptcha', realpath(EASYCAPTCHA_PATH.'template/'.$conf['EasyCaptcha']['template'].'.tpl'));
$template->append('EASYCAPTCHA', array('parsed_content' => $template->parse('EasyCaptcha', true)), true);


function easycaptcha_check()
{
  global $conf;

  if (empty($_POST['easycaptcha_key']) || empty($_POST['easycaptcha']))
  {
    return false;
  }

  list($challenge, $key) = explode('-', $_POST['easycaptcha_key']);
  
  $captcha = load_easycaptcha_class($challenge);
  
  return $captcha->verify($key);
}
