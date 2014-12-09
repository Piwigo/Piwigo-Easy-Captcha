<?php
defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

include_once(EASYCAPTCHA_PATH . 'colors/RandomColor.class.php');

class CaptchaColors
{
  static $shapes;
  
  function get_hint()
  {
    return l10n('To verify you are a human, please choose the closest color for each symbol.');
  }
  
  function get_key()
  {
    global $conf;
    
    $colors = $this->get_colors();
    $solution = $this->get_colors_solution($colors);
    
    $conf['EasyCaptcha']['colors']['colors'] = $this->get_colors_options($colors);
    
    pwg_set_session_var('easycaptcha', $solution['base']);
    
    $key = array();
    foreach ($solution['answer'] as $c)
    {
      $key[] = implode('', $c);
    }
    $key = implode(':', $key);
    
    return pwg_password_hash($conf['secret_key'] . $key);
  }
  
  function verify($key)
  {
    global $conf;
    
    $check = implode(':', $_POST['easycaptcha']);
    return pwg_password_verify($conf['secret_key'] . $check, $key);
  }
  
  function post_conf()
  {
    return array(
      'size'  => (int)$_POST['colors']['size'],
      'nb'    => (int)$_POST['colors']['nb'],
      'bd'    => check_color($_POST['colors']['bd'], true),
      'bg1'   => check_color($_POST['colors']['bg1']),
      'bg2'   => check_color($_POST['colors']['bg2']),
      'bd1'   => check_color($_POST['colors']['bd1'], true),
      'bd2'   => check_color($_POST['colors']['bd2'], true),
      'ar1'   => check_color($_POST['colors']['ar1']),
      'ar2'   => check_color($_POST['colors']['ar2'], true),
      );
  }
  
  function pre_conf()
  {
    global $template;
    
    $template->assign(array(
      'COLORS_CSS' => file_get_contents(EASYCAPTCHA_PATH . 'colors/template/colors.css'),
      ));
  }
  
  function generate($admin)
  {
    global $conf;
    
    $props = array();
    
    if ($admin)
    {
      $props['colors'] = array();
      for ($i=0, $j=60; $i<$conf['EasyCaptcha']['colors']['nb']; $i++, $j+=60)
      {
        $props['colors'][] = RandomColor::one(array('hue'=>$j, 'format'=>'rgb'));
      }
    }
    else
    {
      $props['colors'] = pwg_get_session_var('easycaptcha');
    }
    
    $props['count'] = count($props['colors']);
    $props['size'] = $conf['EasyCaptcha']['colors']['size'];
    $props['margin'] = $props['size'] * 0.15;
    $props['width'] = $props['count'] * ($props['size'] + $props['margin']*2);
    $props['height'] = $props['size'] + $props['margin']*2;


    $img = imagecreatetruecolor($props['width'], $props['height']);

    $bg = imagecolorallocate($img, 0, 0, 0);
    imagefilledrectangle($img, 0, 0, $props['width'], $props['height'], $bg);
    imagecolortransparent($img, $bg);
    
    if ($conf['EasyCaptcha']['colors']['bd1'] != 'transparent')
    {
      $bd = imagecolorallocatehex($img, $conf['EasyCaptcha']['colors']['bd1']);
    }

    foreach ($props['colors'] as $i => $c)
    {
      $x = $i * ($props['size'] + $props['margin']*2) + $props['margin'];
      $y = $props['margin'];
      $c = imagecolorallocate($img, $c['r'], $c['g'], $c['b']);
      
      $this->draw_shape($img, $this->get_shape(), $x, $y, $props['size'], $c);
      
      if (isset($bd))
      {
        imageroundedrectangle($img, $x-1, $y-1, $x+$props['size']+1, $y+$props['size']+1, 2, $bd);
      }
    }

    header('Content-Type: image/png');
    imagepng($img);
  }

  function get_colors()
  {
    $colors = array();
    
    for ($i=0; $i<360; $i+= 60)
    {
      $j = mt_rand($i-10, $i+10);
      if ($j < 0) $j = 360 + $j;
      if ($j >= 360) $j = 360 - $j;
      
      $colors[] = RandomColor::one(array('hue'=>$i, 'format'=>'rgb'));
      $colors[] = RandomColor::one(array('hue'=>$j, 'format'=>'rgb'));
    }
    
    return $colors;
  }

  function get_colors_solution($colors)
  {
    global $conf;
    
    $range = range(0, 5);
    mt_shuffle($range);
    $indexes = array_slice($range, 0, $conf['EasyCaptcha']['colors']['nb']);
    
    $result = array();
    
    foreach ($indexes as $i)
    {
      $result['base'][] = $colors[$i*2];
      $result['answer'][] = $colors[$i*2+1];
    }
    
    return $result;
  }

  function get_colors_options($colors)
  {
    $result = array();
    
    for ($i=1; $i<12; $i+=2)
    {
      $result[] = $colors[$i];
    }
    
    return $result;
  }

  function get_shape()
  {
    return self::$shapes[ array_rand(self::$shapes) ];
  }
  
  function resize_shape(&$coords, $x, $y, $s)
  {
    for ($i=0, $l=count($coords); $i<$l; $i+=2)
    {
      $coords[$i] = $coords[$i]*$s + $x;
      $coords[$i+1] = $coords[$i+1]*$s + $y;
    }
  }

  function draw_shape(&$img, $shape, $x, $y, $s, $c)
  {
    if ($shape === 'circle')
    {
      imagefilledellipse($img, $x+$s/2, $y+$s/2, $s, $s, $c);
    }
    else if (is_array($shape[0]))
    {
      foreach ($shape as $subshape)
      {
        $this->resize_shape($subshape, $x, $y, $s);
        imagefilledpolygon($img, $subshape, count($subshape)/2, $c);
      }
    }
    else
    {
      $this->resize_shape($shape, $x, $y, $s);
      imagefilledpolygon($img, $shape, count($shape)/2, $c);
    }
  }
}

CaptchaColors::$shapes = array(
  // circle
  'circle',
  // square
  array(0,0,1,0,1,1,0,1),
  // medium square
  array(0.25,0.25,0.75,0.25,0.75,0.75,0.25,0.75),
  // small square
  array(0.33,0.33,0.67,0.33,0.67,0.67,0.33,0.67),
  // checkerboard
  array(0,0,0.33,0,0.33,1,0,1,0,0.67,1,0.67,1,1,0.67,1,0.67,0,1,0,1,0.33,0,0.33),
  // switzerland
  array(0.33,0,0.67,0,0.67,0.33,1,0.33,1,0.67,0.67,0.67,0.67,1,0.33,1,0.33,0.67,0,0.67,0,0.33,0.33,0.33),
  // diamond
  array(0.5,0,1,0.5,0.5,1,0,0.5),
  // medium diamond
  array(0.5,0.15,0.855,0.5,0.5,0.855,0.15,0.5),
  // small diamond
  array(0.5,0.25,0.75,0.5,0.5,0.75,0.25,0.5),
  // empty diamond
  array(0.5,0,1,0.5,0.5,1,0,0.5,0.5,0,0.5,0.25,0.25,0.5,0.5,0.75,0.75,0.5,0.5,0.25),
  // empty diamond 2
  array(0.5,0,1,0.5,0.5,1,0,0.5,0.5,0,0.5,0.33,0.33,0.33,0.33,0.67,0.67,0.67,0.67,0.33,0.5,0.33),
  // cross
  array(array(0,0.25,1,0.75,1,0.25,0,0.75),array(0.75,0,0.25,1,0.75,1,0.25,0)),
  // morning star
  array(0,0,0.5,0.25,1,0,0.75,0.5,1,1,0.5,0.75,0,1,0.25,0.5),
  // night star
  array(0.5,0,0.67,0.33,1,0.5,0.67,0.67,0.5,1,0.33,0.67,0,0.5,0.33,0.33),
  // empty star
  array(0,0,0.5,0.25,1,0,0.75,0.5,1,1,0.5,0.75,0,1,0.25,0.5,0.5,0.75,0.75,0.5,0.5,0.25,0.25,0.5),
  // spikes
  array(array(0,0,0.5,0,0.25,0.5),array(1,0,1,0.5,0.5,0.25),array(0,0.5,0.5,0.75,0,1),array(0.5,1,0.75,0.5,1,1)),
  // revert spikes
  array(array(0.5,0,1,0.25,0.5,0.5),array(1,0.5,0.75,1,0.5,0.5),array(0.5,1,0,0.75,0.5,0.5),array(0,0.5,0.25,0,0.5,0.5)),
  // cross 2
  array(0,0,0.21,0,0.5,0.3,0.8,0,1,0,1,0.2,0.7,0.5,1,0.81,1,1,0.81,1,0.5,0.7,0.2,1,0,1,0,0.8,0.3,0.5,0,0.21),
);