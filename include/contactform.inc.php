<?php
defined('EASYCAPTCHA_ID') or die('Hacking attempt!');

include(EASYCAPTCHA_PATH.'include/common.inc.php');
add_event_handler('loc_begin_index', 'add_easycaptcha');
add_event_handler('contact_form_check', 'check_easycaptcha', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_easycaptcha()
{
  global $template;
  $template->set_prefilter('index', 'prefilter_easycaptcha');
}

function prefilter_easycaptcha($content, $smarty)
{
  $search = '{$contact.content}</textarea></td>';
  return str_replace($search, $search."\n{\$EASYCAPTCHA}", $content);
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