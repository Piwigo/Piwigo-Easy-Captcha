</li>
<li>
  {include file=$EASYCAPTCHA_ABS_PATH|cat:'template/common.inc.tpl'}

  {* <!-- DRAG & DROP --> *}
  {if $EASYCAPTCHA_CHALLENGE == 'drag'}
  <span class="property"><label>{'easycaptcha_drag_%s'|translate|sprintf:$EASYCAPTCHA_CONF.text}</label></span>

  {* <!-- TIC TAC TOE --> *}
  {else if $EASYCAPTCHA_CHALLENGE == 'tictac'}
  <span class="property"><label>{'easycaptcha_tictac'|translate}</label></span>

  {/if}
  {$smarty.capture.easycaptcha}