{if !$no_dep}
{combine_script id='jquery.event.drag' load='footer' require='jquery' path=$EASYCAPTCHA_PATH|cat:'drag/template/jquery.events/jquery.event.drag-2.2.js'}
{combine_script id='jquery.event.drop' load='footer' require='jquery' path=$EASYCAPTCHA_PATH|cat:'drag/template/jquery.events/jquery.event.drop-2.2.js'}

{combine_script id='easycaptcha.drag' load='footer' require='jquery.event.drag,jquery.event.drop' path=$EASYCAPTCHA_PATH|cat:'drag/template/drag.js'}
{combine_css id='easycaptcha.drag' path=$EASYCAPTCHA_PATH|cat:'drag/template/drag.css' template=true version=$EASYCAPTCHA.lastmod}
{/if}

{capture name=easycaptcha}
<noscript class="easycaptcha noscript">
  {'You must activate JavaScript in your browser in order to be able to add a comment, sorry for the inconvenience.'|translate}
</noscript>

<div class="easycaptcha drag" style="display:none;">
{counter start=0 assign=i}
{foreach from=$EASYCAPTCHA.drag.selection item=image}
  <div class="drag_item" style="left:{math equation='s*0.2+s*1.2*i' s=$EASYCAPTCHA.drag.size i=$i}px;" data-id="{$image}">
    <img src="{$ROOT_URL}{$EASYCAPTCHA_PATH}drag/get.php?theme={$EASYCAPTCHA.drag.theme}&amp;image={$image}">
  </div>
  {counter}
{/foreach}
  <div class="drop_zone">{'Drop'|translate}</div>
</div>

<input type="text" name="easycaptcha" value="" style="display:none">
{/capture}