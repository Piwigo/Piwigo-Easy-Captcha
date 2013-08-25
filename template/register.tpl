</li>
<li>
  {include file=$EASYCAPTCHA_ABS_PATH|cat:'template/common.inc.tpl'}

  {* <!-- DRAG & DROP --> *}
  {if $EASYCAPTCHA_CHALLENGE == 'drag'}
  <span class="property"><label>{'To verify you are a human, please place the <b>%s</b> in the most right box bellow.'|translate|sprintf:$EASYCAPTCHA_CONF.text}</label></span>

  {* <!-- TIC TAC TOE --> *}
  {else if $EASYCAPTCHA_CHALLENGE == 'tictac'}
  <span class="property"><label>{'You are player X, click on the right case to complete the line.'|translate}</label></span>

  {/if}
  {$smarty.capture.easycaptcha}