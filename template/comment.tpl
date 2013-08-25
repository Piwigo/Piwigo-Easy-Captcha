{include file=$EASYCAPTCHA_ABS_PATH|cat:'template/common.inc.tpl'}

{* <!-- DRAG & DROP --> *}
{if $EASYCAPTCHA_CHALLENGE == 'drag'}
<p><label>{'To verify you are a human, please place the <b>%s</b> in the most right box bellow.'|translate|sprintf:$EASYCAPTCHA_CONF.text}</label></p>

{* <!-- TIC TAC TOE --> *}
{else if $EASYCAPTCHA_CHALLENGE == 'tictac'}
<p><label>{'You are player X, click on the right case to complete the line.'|translate}</label></p>

{/if}
{$smarty.capture.easycaptcha}