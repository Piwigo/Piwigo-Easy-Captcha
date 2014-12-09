</li>
<li>
  {include file=$EASYCAPTCHA_ABS_PATH|cat:$EASYCAPTCHA.challenge|cat:'/template/captcha.tpl'}
  <span class="property"><label class="easycaptcha_hint">{$EASYCAPTCHA.hint}</label></span>
  {$smarty.capture.easycaptcha}
  <input type="text" name="easycaptcha_key" value="{$EASYCAPTCHA.key}" style="display:none">