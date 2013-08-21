</tr>
<tr>
  {include file=$EASYCAPTCHA_ABS_PATH|cat:'template/common.inc.tpl'}

  {* <!-- DRAG & DROP --> *}
  {if $EASYCAPTCHA_CHALLENGE == 'drag'}
  <td class="title">{'easycaptcha_drag_%s'|translate|sprintf:$EASYCAPTCHA_CONF.text}</td>

  {footer_script}
  var captcha_code = new LiveValidation(jQuery('input[name="easycaptcha"]')[0], {ldelim} onlyOnSubmit: true });
  captcha_code.add(Validate.Presence, {ldelim} failureMessage: "{'Pleaser answer'|translate}" });
  {/footer_script}

  {* <!-- TIC TAC TOE --> *}
  {else if $EASYCAPTCHA_CHALLENGE == 'tictac'}
  <td class="title">{'easycaptcha_tictac'|translate}</td>

  {footer_script}
  var captcha_code = new LiveValidation(jQuery('input[name="easycaptcha_key"]')[0], {ldelim} onlyOnSubmit: true });
  captcha_code.add(Validate.Custom, {ldelim}
    failureMessage: "{'Pleaser answer'|translate}",
    against: function() {ldelim}
        return jQuery('input[name="easycaptcha"]:checked').length != 0;
    }
  });
  {/footer_script}

  {/if}
  <td>
    {$smarty.capture.easycaptcha}
  </td>