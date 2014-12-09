{include file=$EASYCAPTCHA_ABS_PATH|cat:$EASYCAPTCHA.challenge|cat:'/template/captcha.tpl'}

<div class="col-100">
  <label class="easycaptcha_hint">{$EASYCAPTCHA.hint}</label>
</div>

<div class="col-100">
  {$smarty.capture.easycaptcha}
  <input type="text" name="easycaptcha_key" value="{$EASYCAPTCHA.key}" style="display:none">
</div>

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