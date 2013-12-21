<?php
defined('EASYCAPTCHA_ID') or die('Hacking attempt!');

include(EASYCAPTCHA_PATH.'include/common.inc.php');
add_event_handler('loc_begin_index', 'add_easycaptcha');
add_event_handler('user_comment_check', 'check_easycaptcha', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_easycaptcha()
{
  global $template;
  $template->set_prefilter('guestbook', 'prefilter_easycaptcha');
}

function prefilter_easycaptcha($content, $smarty)
{
  $search = '#{\$comment_add\.CONTENT}</textarea>(\s*)</td>(\s*)</tr>#';
  $replace = '{\$comment_add.CONTENT}</textarea>$1</td>$2</tr>'."\n".'{\$EASYCAPTCHA_CONTENT}';
  return preg_replace($search, $replace, $content);
}

function check_easycaptcha($action, $comment)
{
  global $conf, $page;

  if (!is_a_guest()) return $action;

  if (!easycaptcha_check())
  {
    $page['errors'][] = l10n('Invalid answer');
    return 'reject';
  }

  return $action;
}