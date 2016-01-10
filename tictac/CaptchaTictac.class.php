<?php
defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

class CaptchaTictac
{
  private $props;
  private $img_circle;
  private $img_cross;
  
  static $configs;
  
  function get_key()
  {
    return '0';
  }
  
  function get_hint()
  {
    return l10n('To verify you are a human, please beat the game.');
  }
  
  function verify($key)
  {
    return $_POST['easycaptcha'] == pwg_get_session_var('easycaptcha', '33');
  }
  
  function post_conf()
  {
    return array(
      'size' => (int)$_POST['tictac']['size'],
      'bg1'  => check_color($_POST['tictac']['bg1']),
      'bg2'  => check_color($_POST['tictac']['bg2']),
      'bd'   => check_color($_POST['tictac']['bd']),
      'obj'  => check_color($_POST['tictac']['obj']),
      'sel'  => check_color($_POST['tictac']['sel']),
      );
  }
  
  function pre_conf() {}
  
  function generate($cross, $admin)
  {
    global $conf;
    
    $this->props = array();

    // special size when asking for cross only
    $this->props['size'] = $cross!=0 ? $cross : $conf['EasyCaptcha']['tictac']['size'];

    // compute various sizes
    $this->props['bd_size'] = max(1, floor($this->props['size']*0.01));
    $this->props['box_size'] = floor(($this->props['size']-4*$this->props['bd_size'])/3);
    $this->props['size'] = 3*$this->props['box_size'] + 4*$this->props['bd_size'] + 1;
    $this->props['pad'] = floor($this->props['box_size']*0.1);
    $this->props['radius'] = floor($this->props['box_size']*0.2);


    // return cross only
    if ($cross != 0)
    {
      $this->props['bd_size']/= 2;

      $img = imagecreatetruecolor($this->props['box_size'], $this->props['box_size']);
      if (function_exists('imageantialias'))
      {
        imageantialias($img, true);
      }

      $bg = imagecolorallocatehex($img, $conf['EasyCaptcha']['tictac']['bg1']);

      imagefilledrectangle($img, 0, 0, $this->props['box_size'], $this->props['box_size'], $bg);
      imagecolortransparent($img, $bg);

      $this->drawcross($img, array(0,0), $conf['EasyCaptcha']['tictac']['sel']);
    }
    else
    {
      // pick a random config
      $selection = $this->get_config();
      if (!$admin)
      {
        pwg_set_session_var('easycaptcha', implode('', $selection['answer']));
      }

      // create image
      $img = imagecreatetruecolor($this->props['size'], $this->props['size']);
      if (function_exists('imageantialias'))
      {
        imageantialias($img, true);
      }

      // background
      $bg_start = hex2rgb($conf['EasyCaptcha']['tictac']['bg1']);
      $bg_end = hex2rgb($conf['EasyCaptcha']['tictac']['bg2']);

      imagegradientrectangle($img, $bg_start, $bg_end);
      // $bg = imagecolorallocatehex($img, $conf['EasyCaptcha']['tictac']['bg1']);
      // imagefilledrectangle($img, 0, 0, $this->props['size'], $this->props['size'], $bg);

      // borders
      $bd = imagecolorallocatehex($img, $conf['EasyCaptcha']['tictac']['bd']);
      for ($i=0; $i<4; $i++)
      {
        imagefilledrectangle($img, $i*($this->props['box_size']+$this->props['bd_size']), 0, $i*($this->props['box_size']+$this->props['bd_size'])+$this->props['bd_size'], $this->props['size'], $bd);
        imagefilledrectangle($img, 0, $i*($this->props['box_size']+$this->props['bd_size']), $this->props['size'], $i*($this->props['box_size']+$this->props['bd_size'])+$this->props['bd_size'], $bd);
      }

      // crosses
      foreach ($selection['checked'] as $pos)
      {
        $this->drawcross($img, $pos, $conf['EasyCaptcha']['tictac']['obj']);
      }
      if ($admin)
      {
        imagedestroy($this->img_cross);
        $this->drawcross($img, $selection['answer'], $conf['EasyCaptcha']['tictac']['sel']);
      }

      // circles
      $protect = $selection['checked'];
      $protect[] = $selection['answer'];
      $i = rand(2,3);
      $circles = array();

      while ($i>0)
      {
        $pos = array(rand(0, 2), rand(0, 2));

        foreach ($protect as $pro)
        {
          if ($pos[0]==$pro[0] && $pos[1]==$pro[1]) continue(2);
        }
        if ($this->checkline($pos, $circles)) continue;

        $protect[] = $pos;
        $circles[$pos[0]][$pos[1]] = true;

        $this->drawcircle($img, $pos, $conf['EasyCaptcha']['tictac']['obj']);
        $i--;
      }
    }

    // output
    header('Content-Type: image/png');
    imagepng($img);
  }
 
  function drawcircle(&$img, $pos, $color)
  {
    global $conf;

    if (!is_resource($this->img_circle))
    {
      $this->img_circle = imagecreatetruecolor($this->props['box_size'], $this->props['box_size']);

      $bg = imagecolorallocatehex($this->img_circle, $conf['EasyCaptcha']['tictac']['bg1']);
      $obj = imagecolorallocatehex($this->img_circle, $color);

      imagefilledrectangle($this->img_circle, 0, 0, $this->props['box_size'], $this->props['box_size'], $bg);
      imagecolortransparent($this->img_circle, $bg);

      $radius = $this->props['box_size'] - $this->props['pad']*2;
      $radius2 = $radius - 2*sqrt(2*$this->props['pad']*$this->props['pad']);

      imagefilledellipse($this->img_circle, $this->props['box_size']/2, $this->props['box_size']/2, $radius, $radius, $obj);
      imagefilledellipse($this->img_circle, $this->props['box_size']/2, $this->props['box_size']/2, $radius2, $radius2, $bg);
    }

    $pos = array(
      $pos[0]*($this->props['bd_size']+$this->props['box_size']) + $this->props['bd_size'],
      $pos[1]*($this->props['bd_size']+$this->props['box_size']) + $this->props['bd_size'],
      );

    imagecopymerge($img, $this->img_circle, $pos[0], $pos[1], 0, 0, $this->props['box_size'], $this->props['box_size'], 100);
  }

  function drawcross(&$img, $pos, $color)
  {
    global $conf;

    if (!is_resource($this->img_cross))
    {
      $this->img_cross = imagecreatetruecolor($this->props['box_size'], $this->props['box_size']);

      $bg = imagecolorallocatehex($this->img_cross, $conf['EasyCaptcha']['tictac']['bg1']);
      $obj = imagecolorallocatehex($this->img_cross, $color);

      imagefilledrectangle($this->img_cross, 0, 0, $this->props['box_size'], $this->props['box_size'], $bg);
      imagecolortransparent($this->img_cross, $bg);

      $points1 = array(
        $this->props['pad']*2,                            $this->props['pad'],
        $this->props['box_size'] - $this->props['pad'],   $this->props['box_size'] - $this->props['pad']*2,
        $this->props['box_size'] - $this->props['pad']*2, $this->props['box_size'] - $this->props['pad'],
        $this->props['pad'],                              $this->props['pad']*2,
        );

      $points2 = array(
        $this->props['box_size'] - $this->props['pad']*2, $this->props['pad'],
        $this->props['box_size'] - $this->props['pad'],   $this->props['pad']*2,
        $this->props['pad']*2,                            $this->props['box_size'] - $this->props['pad'],
        $this->props['pad'],                              $this->props['box_size'] - $this->props['pad']*2,
        );

      imagefilledpolygon($this->img_cross, $points1, 4, $obj);
      imagefilledpolygon($this->img_cross, $points2, 4, $obj);
    }

    $pos = array(
      $pos[0]*($this->props['bd_size']+$this->props['box_size']) + $this->props['bd_size'],
      $pos[1]*($this->props['bd_size']+$this->props['box_size']) + $this->props['bd_size'],
      );

    imagecopymerge($img, $this->img_cross, $pos[0], $pos[1], 0, 0, $this->props['box_size'], $this->props['box_size'], 100);
  }

  function checkline($pos, $existing)
  {
    $existing[$pos[0]][$pos[1]] = true;

    // check col
    $nb = 0;
    for ($l=0; $l<3; $l++)
    {
      if (isset($existing[$pos[0]][$l])) $nb++;
    }
    if ($nb==3) return true;

    // check line
    $nb = 0;
    for ($c=0; $c<3; $c++)
    {
      if (isset($existing[$c][$pos[1]])) $nb++;
    }
    if ($nb==3) return true;

    // check diag 1
    $nb = 0;
    for ($i=0; $i<3; $i++)
    {
      if (isset($existing[$i][$i])) $nb++;
    }
    if ($nb==3) return true;

    // check diag 2
    $nb = 0;
    for ($i=0; $i<3; $i++)
    {
      if (isset($existing[$i][2-$i])) $nb++;
    }
    if ($nb==3) return true;

    return false;
  }

  function get_config()
  {
    return self::$configs[ array_rand(self::$configs) ];
  }
}

CaptchaTictac::$configs = array(
  // line 1
  array(
    'checked' => array(array(0,0),array(0,1)),
    'answer' => array(0,2),
    ),
  array(
    'checked' => array(array(0,0),array(0,2)),
    'answer' => array(0,1),
    ),
  array(
    'checked' => array(array(0,1),array(0,2)),
    'answer' => array(0,0),
    ),
  // line 2
  array(
    'checked' => array(array(1,0),array(1,1)),
    'answer' => array(1,2),
    ),
  array(
    'checked' => array(array(1,0),array(1,2)),
    'answer' => array(1,1),
    ),
  array(
    'checked' => array(array(1,1),array(1,2)),
    'answer' => array(1,0),
    ),
  // line 3
  array(
    'checked' => array(array(2,0),array(2,1)),
    'answer' => array(2,2),
    ),
  array(
    'checked' => array(array(2,0),array(2,2)),
    'answer' => array(2,1),
    ),
  array(
    'checked' => array(array(2,1),array(2,2)),
    'answer' => array(2,0),
    ),
  // col 1
  array(
    'checked' => array(array(0,0),array(1,0)),
    'answer' => array(2,0),
    ),
  array(
    'checked' => array(array(0,0),array(2,0)),
    'answer' => array(1,0),
    ),
  array(
    'checked' => array(array(1,0),array(2,0)),
    'answer' => array(0,0),
    ),
  // col 2
  array(
    'checked' => array(array(0,1),array(1,1)),
    'answer' => array(2,1),
    ),
  array(
    'checked' => array(array(0,1),array(2,1)),
    'answer' => array(1,1),
    ),
  array(
    'checked' => array(array(1,1),array(2,1)),
    'answer' => array(0,1),
    ),
  // col 3
  array(
    'checked' => array(array(0,2),array(1,2)),
    'answer' => array(2,2),
    ),
  array(
    'checked' => array(array(0,2),array(2,2)),
    'answer' => array(1,2),
    ),
  array(
    'checked' => array(array(1,2),array(2,2)),
    'answer' => array(0,2),
    ),
  // diag 1
  array(
    'checked' => array(array(0,0),array(1,1)),
    'answer' => array(2,2),
    ),
  array(
    'checked' => array(array(0,0),array(2,2)),
    'answer' => array(1,1),
    ),
  array(
    'checked' => array(array(1,1),array(2,2)),
    'answer' => array(0,0),
    ),
  // diag 2
  array(
    'checked' => array(array(2,0),array(1,1)),
    'answer' => array(0,2),
    ),
  array(
    'checked' => array(array(2,0),array(0,2)),
    'answer' => array(1,1),
    ),
  array(
    'checked' => array(array(1,1),array(0,2)),
    'answer' => array(2,0),
    ),
  );
