<?php
defined('EASYCAPTCHA_PATH') or die('Hacking attempt!');

function load_easycaptcha_class($module)
{
  $class = 'Captcha' . ucfirst($module);
  include_once(EASYCAPTCHA_PATH . $module . '/' . $class . '.class.php');
  return new $class();
}

if (!function_exists('mt_shuffle'))
{
  function mt_shuffle(&$array)
  {
    $array = array_values($array);
    
    for ($i = count($array) - 1; $i > 0; --$i)
    {
      $j = mt_rand(0, $i);
      
      if ($i !== $j)
      {
        list($array[$i], $array[$j]) = array($array[$j], $array[$i]);
      }
    }

    return true; 
  }
}

/**
 * crypt a string using
 * http://stackoverflow.com/questions/800922/how-to-encrypt-string-without-mcrypt-library-in-php/802957#802957
 * @param: string value to crypt
 * @param: string key
 * @return: string
 */
function simple_crypt($value, $key)
{
  $result = null;
  for($i = 0; $i < strlen($value); $i++)
  {
    $char = substr($value, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char) + ord($keychar));
    $result .= $char;
  }

  $result = base64url_encode($result);
  return trim($result);
}

/**
 * decrypt a string crypted with previous function
 * @param: string value to decrypt
 * @param: string key
 * @return: string
 */
function simple_decrypt($value, $key)
{
  $value = base64url_decode($value);

  $result = null;
  for($i = 0; $i < strlen($value); $i++)
  {
    $char = substr($value, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char) - ord($keychar));
    $result .= $char;
  }

  return trim($result);
}

/**
 * variant of base64 functions usable into url
 * http://php.net/manual/en/function.base64-encode.php#103849
 */
if (!function_exists('base64url_encode'))
{
  function base64url_encode($data)
  {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }
  function base64url_decode($data)
  {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
  }
}

function hex2rgb($hex)
{
  $hex = ltrim($hex, '#');

  if (strlen($hex) == 3)
  {
    $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
  }
  else if (strlen($hex) != 6)
  {
    return array(0,0,0);
  }

  $int = hexdec($hex);
  return array(0xFF&($int>>0x10), 0xFF&($int>>0x8), 0xFF&$int);
}

function imagecolorallocatehex(&$img, $hex)
{
  $rgb = hex2rgb($hex);
  return imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
}

function imagegradientrectangle(&$img, $start, $end)
{
  $line_numbers = imagesx($img);
  $line_width = imagesy($img);

  list($r1,$g1,$b1) = $start;
  list($r2,$g2,$b2) = $end;
  list($r,$g,$b) = $end;

  $fill = imagecolorallocate($img, $r, $g, $b);

  for ($i=0; $i<$line_numbers; $i++)
  {
    $old_r = $r;
    $old_g = $g;
    $old_b = $b;

    $r = ( $r2 - $r1 != 0 ) ? intval( $r1 + ( $r2 - $r1 ) * ( $i / $line_numbers ) ): $r1;
    $g = ( $g2 - $g1 != 0 ) ? intval( $g1 + ( $g2 - $g1 ) * ( $i / $line_numbers ) ): $g1;
    $b = ( $b2 - $b1 != 0 ) ? intval( $b1 + ( $b2 - $b1 ) * ( $i / $line_numbers ) ): $b1;

    if ("$old_r $old_g $old_b" != "$r $g $b")
    {
      $fill = imagecolorallocate($img, $r, $g, $b);
    }
    imagefilledrectangle($img, 0, $i, $line_width, $i, $fill);
  }
}

function imageroundedrectangle(&$img, $x1, $y1, $x2, $y2, $r, $color)
{
  $r = min($r, floor(min(($x2-$x1)/2, ($y2-$y1)/2)));
  
  // top border
  imageline($img, $x1+$r, $y1, $x2-$r, $y1, $color);
  // right border
  imageline($img, $x2, $y1+$r, $x2, $y2-$r, $color);
  // bottom border
  imageline($img, $x1+$r, $y2, $x2-$r, $y2, $color);
  // left border
  imageline($img, $x1, $y1+$r, $x1, $y2-$r, $color);
  
  // top-left arc
  imagearc($img, $x1+$r, $y1+$r, $r*2, $r*2, 180, 270, $color);
  // top-right arc
  imagearc($img, $x2-$r, $y1+$r, $r*2, $r*2, 270, 0, $color);
  // bottom-right arc
  imagearc($img, $x2-$r, $y2-$r, $r*2, $r*2, 0, 90, $color);
  // bottom-left arc
  imagearc($img, $x1+$r, $y2-$r, $r*2, $r*2, 90, 180, $color);
  
  return true;
}

function check_color($hex, $allowEmpty=false)
{
  global $page;
  
  if ((empty($hex) || $hex=='transparent') && $allowEmpty)
  {
    return 'transparent';
  }

  $hex = ltrim($hex, '#');

  if (strlen($hex) == 3)
  {
    $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
  }
  if (strlen($hex) != 6 || !ctype_xdigit($hex))
  {
    $page['errors'][] = l10n('Invalid color code <i>%s</i>', '#'.$hex);
    $hex = '000000';
  }

  return '#'.$hex;
}