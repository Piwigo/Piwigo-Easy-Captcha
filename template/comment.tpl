{include file=$EASYCAPTCHA_ABS_PATH|cat:'template/common.inc.tpl'}

{* <!-- DRAG & DROP --> *}
{if $EASYCAPTCHA_CHALLENGE == 'drag'}
<p><label>{'easycaptcha_drag_%s'|translate|sprintf:$EASYCAPTCHA_CONF.text}</label></p>

{* <!-- TIC TAC TOE --> *}
{else if $EASYCAPTCHA_CHALLENGE == 'tictac'}
<p><label>{'easycaptcha_tictac'|translate}</label></p>

{/if}
{$smarty.capture.easycaptcha}