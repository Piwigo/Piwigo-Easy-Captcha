{include file=$EASYCAPTCHA_ABS_PATH|cat:$EASYCAPTCHA.challenge|cat:'/template/captcha.tpl'}

<tr>
  <td class="title"></td>
  <td>
    <span class="easycaptcha_hint">{$EASYCAPTCHA.hint}</span><br>
    {$smarty.capture.easycaptcha}
    <input type="text" name="easycaptcha_key" value="{$EASYCAPTCHA.key}" style="display:none">
  </td>
</tr>

{footer_script}
{if $EASYCAPTCHA.challenge == 'tictac'}
  var captcha_code = new LiveValidation(jQuery('input[name="easycaptcha_key"]')[0], {ldelim} onlyOnSubmit: true });
  captcha_code.add(Validate.Custom, {ldelim}
    failureMessage: "{'Pleaser answer'|translate}",
    against: function() {ldelim}
        return jQuery('input[name="easycaptcha"]:checked').length != 0;
    }
  });
{else}
  var captcha_code = new LiveValidation(jQuery('input[name^="easycaptcha"]')[0], {ldelim} onlyOnSubmit: true });
  captcha_code.add(Validate.Presence, {ldelim} failureMessage: "{'Pleaser answer'|translate}" });
{/if}
{/footer_script}