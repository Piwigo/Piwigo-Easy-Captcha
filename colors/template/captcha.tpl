{if !$no_dep}
{combine_script id='easycaptcha.colors' load='async' require='jquery' path=$EASYCAPTCHA_PATH|cat:'colors/template/colors.js'}
{combine_css id='easycaptcha.colors' path=$EASYCAPTCHA_PATH|cat:'colors/template/colors.css' template=true version=$EASYCAPTCHA.lastmod}

{footer_script}
var EasyCaptchaColors = {$EASYCAPTCHA.colors.colors|@json_encode};
{/footer_script}
{/if}

{capture name=easycaptcha}
<noscript class="easycaptcha noscript">
  {'You must activate JavaScript in your browser in order to be able to add a comment, sorry for the inconvenience.'|translate}
</noscript>

<div class="easycaptcha colors">
  <img class="reference" src="{$ROOT_URL}{$EASYCAPTCHA_PATH}colors/gen.php">
  
  <div class="answer">
  {for $i=0 to $EASYCAPTCHA.colors.nb-1}
    <div class="item">
      <span class="item-next">&#9650;</span>
      <span class="item-prev">&#9660;</span>
      <input type="text" name="easycaptcha[{$i}]" style="display:none">
    </div>
  {/for}
  </div>
</div>
{/capture}