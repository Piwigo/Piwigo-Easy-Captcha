<?php
defined('EASYCAPTCHA_ID') or die('Hacking attempt!');

include(EASYCAPTCHA_PATH.'include/common.inc.php');
add_event_handler('loc_begin_index', 'add_easycaptcha');
add_event_handler('user_comment_check_albums', 'check_easycaptcha', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_easycaptcha()
{
  global $template;
  $template->set_prefilter('comments_on_albums', 'prefilter_easycaptcha');
}

function prefilter_easycaptcha($content, $smarty)
{
  $search = '{$comment_add.CONTENT}</textarea></p>';
  return str_replace($search, $search."\n{\$EASYCAPTCHA_CONTENT}", $content);
}

function check_easycaptcha($action, $comment)
{
  global $conf, $page;

  if (!is_a_guest()) return $action;

  if (!easycaptcha_check())
  {
    if ($conf['EasyCaptcha']['comments_action'] == 'reject')  $page['errors'][] = l10n('Invalid answer');
    return ($action != 'reject') ? $conf['EasyCaptcha']['comments_action'] : 'reject';
  }

  return $action;
}