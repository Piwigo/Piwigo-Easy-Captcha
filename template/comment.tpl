{include file=$EASYCAPTCHA_ABS_PATH|cat:$EASYCAPTCHA.challenge|cat:'/template/captcha.tpl'}
<p><label class="easycaptcha_hint">{$EASYCAPTCHA.hint}</label></p>
{$smarty.capture.easycaptcha}
<input type="text" name="easycaptcha_key" value="{$EASYCAPTCHA.key}" style="display:none">