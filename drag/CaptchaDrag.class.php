<?php
defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

class CaptchaDrag
{
  function get_key()
  {
    global $conf;

    $drag_images = include(EASYCAPTCHA_PATH . 'drag/themes/' . $conf['EasyCaptcha']['drag']['theme'] . '/conf.inc.php');
    $conf['EasyCaptcha']['drag']['nb'] = min($conf['EasyCaptcha']['drag']['nb'], count($drag_images));

    foreach (array_rand($drag_images, $conf['EasyCaptcha']['drag']['nb']) as $row)
    {
      $conf['EasyCaptcha']['drag']['selection'][ $row ] = $this->encode_image_url($row);
    }

    $selected = array_rand($conf['EasyCaptcha']['drag']['selection']);
    $conf['EasyCaptcha']['drag']['text'] = $drag_images[ $selected ];
    
    return pwg_password_hash($conf['secret_key'] . $selected);
  }
  
  function get_hint()
  {
    global $conf;
    
    load_language($conf['EasyCaptcha']['drag']['theme'] . '.lang', EASYCAPTCHA_PATH);
    
    return l10n('To verify you are a human, please place the <b>%s</b> in the most right box bellow.',
      l10n($conf['EasyCaptcha']['drag']['text'])
      );
  }
  
  function pre_conf()
  {
    global $template;
    
    $template->assign(array(
      'DRAG_THEMES' => $this->list_themes(),
      'DRAG_CSS' => file_get_contents(EASYCAPTCHA_PATH . 'drag/template/drag.css'),
      ));
  }
  
  function verify($key)
  {
    global $conf;
    
    $check = $this->decode_image_url($_POST['easycaptcha']);
    return pwg_password_verify($conf['secret_key'] . $check, $key);
  }
  
  function post_conf()
  {
    return array(
      'theme' => $_POST['drag']['theme'],
      'size'  => (int)$_POST['drag']['size'],
      'nb'    => (int)$_POST['drag']['nb'],
      'bd'    => check_color($_POST['drag']['bd'], true),
      'bg1'   => check_color($_POST['drag']['bg1']),
      'bg2'   => check_color($_POST['drag']['bg2']),
      'obj'   => check_color($_POST['drag']['obj'], true),
      'sel'   => check_color($_POST['drag']['sel']),
      'bd1'   => check_color($_POST['drag']['bd1'], true),
      'bd2'   => check_color($_POST['drag']['bd2'], true),
      'txt'   => check_color($_POST['drag']['txt']),
      );
  }
  
  function get_image($theme, $image)
  {
    if (empty($theme) || empty($image))
    {
      return;
    }
    
    $image = $this->decode_image_url($image);
    $file = EASYCAPTCHA_PATH . 'drag/themes/' . $theme . '/' . $image;

    if (@file_exists($file))
    {
      $ext = get_extension($image);
      if ($ext == 'jpg') $ext = 'jpeg';

      header('Content-Type: image/' . $ext);
      readfile($file);
    }
  }
  
  /*
   * crypt the name of an image, it use the Piwigo secret_key
   * and a random salt to prevent attacker to build a dictionnary
   */
  function encode_image_url($name)
  {
    global $conf, $easycaptcha_uniqid;

    if (empty($easycaptcha_uniqid))
    {
      $easycaptcha_uniqid = uniqid(null, true);
    }

    $name.= '-'. $easycaptcha_uniqid;
    $name = simple_crypt($name, $conf['secret_key']);

    return $name;
  }

  /*
   * decrypt the image name
   */
  function decode_image_url($name)
  {
    global $conf;

    $name = simple_decrypt($name, $conf['secret_key']);
    $name = strtok($name, '-');

    return $name;
  }
  
  function list_themes()
  {
    $dir = EASYCAPTCHA_PATH . 'drag/themes/';
    $dh = opendir($dir);
    $themes = array();

    while (($item = readdir($dh)) !== false )
    {
      if ($item!=='.' && $item!=='..' &&
          is_dir($dir.$item) && file_exists($dir.$item.'/conf.inc.php')
        )
      {
        $drag_images = include($dir.$item.'/conf.inc.php');
        $themes[$item] = array(
          'image' => key($drag_images),
          'count' => count($drag_images),
          );
      }
    }

    closedir($dh);
    return $themes;
  }
}