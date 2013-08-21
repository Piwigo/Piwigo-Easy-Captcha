{* <!-- DRAG & DROP --> *}
{if $EASYCAPTCHA_CHALLENGE == 'drag'}
{combine_script id='jquery.event.drag' load='footer' require='jquery' path=$EASYCAPTCHA_PATH|cat:'template/jquery.events/jquery.event.drag-2.2.js'}
{combine_script id='jquery.event.drop' load='footer' require='jquery' path=$EASYCAPTCHA_PATH|cat:'template/jquery.events/jquery.event.drop-2.2.js'}

{combine_script id='easycaptcha.drag' load='footer' require='jquery.event.drag,jquery.event.drop' path=$EASYCAPTCHA_PATH|cat:'template/drag.js'}

{html_style}
#easycaptcha, #easycaptcha_noscript {ldelim}
  display:inline-block;
  position:relative;
  padding:10px;
  border-radius:8px;
  background: {$EASYCAPTCHA_CONF.bg1};
  background: -webkit-linear-gradient(top, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: -moz-linear-gradient(top, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: -ms-linear-gradient(top, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: -o-linear-gradient(top, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: linear-gradient(to bottom, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  color:{$EASYCAPTCHA_CONF.txt};
}
#easycaptcha .drag_item {ldelim}
  position:absolute;
  top:15px;
  width:{$EASYCAPTCHA_CONF.size}px;
  height:{$EASYCAPTCHA_CONF.size}px;
  border-radius:5px;
  border:1px solid {$EASYCAPTCHA_CONF.bd1};
  background:{$EASYCAPTCHA_CONF.obj};
  z-index:10;
  cursor:move;
}
#easycaptcha .drag_item.active {ldelim}
  z-index:100;
  opacity:0.75;
}
#easycaptcha .drag_item img {ldelim}
  width:{$EASYCAPTCHA_CONF.size}px;
  height:{$EASYCAPTCHA_CONF.size}px;
  border-radius:5px;
}
#easycaptcha .drop_zone {ldelim}
  -moz-box-sizing:borderbox;
  box-sizing:borderbox;
  padding:5px;
  width:{$EASYCAPTCHA_CONF.size}px;
  height:{$EASYCAPTCHA_CONF.size}px;
  margin-left:{math equation='15+(x+5)*y' x=$EASYCAPTCHA_CONF.size y=$EASYCAPTCHA_CONF.nb}px;
  line-height:{$EASYCAPTCHA_CONF.size}px;
  background: {$EASYCAPTCHA_CONF.bg1};
  background: -webkit-linear-gradient(bottom, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: -moz-linear-gradient(bottom, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: -ms-linear-gradient(bottom, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: -o-linear-gradient(bottom, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  background: linear-gradient(to top, {$EASYCAPTCHA_CONF.bg1} 0%, {$EASYCAPTCHA_CONF.bg2} 100%);
  border:1px dotted {$EASYCAPTCHA_CONF.bd2};
  border-radius:5px;
  text-align:center;
  text-transform:uppercase;
  color:{$EASYCAPTCHA_CONF.txt};
}
#easycaptcha .drop_zone.active {ldelim}
  background:{$EASYCAPTCHA_CONF.sel};
}
#easycaptcha .drop_zone.valid {ldelim}
  background:{$EASYCAPTCHA_CONF.sel};
  box-shadow:0 0 0 2px {$EASYCAPTCHA_CONF.sel};
}
{/html_style}

{capture name=easycaptcha}
<noscript id="easycaptcha_noscript">
  {'easycaptcha_javascript_public'|translate}
</noscript>

<div id="easycaptcha" style="display:none;">
{counter start=0 assign=i}
{foreach from=$EASYCAPTCHA_CONF.selection item=image}
  <div class="drag_item" style="left:{math equation='10+(x+5)*y' x=$EASYCAPTCHA_CONF.size y=$i}px;" data-id="{$image}">
    <img src="{$ROOT_URL}{$EASYCAPTCHA_PATH}drag/get.php?{$EASYCAPTCHA_CONF.theme}&amp;{$image}">
  </div>
  {counter}
{/foreach}
  <div class="drop_zone">{'Drop'|translate}</div>
</div>

{* <!-- fields are not type "hidden" for LiveValidation in GuestBook and ContactForm --> *}
<input type="text" name="easycaptcha" value="" style="display:none;">
<input type="text" name="easycaptcha_key" value="{$EASYCAPTCHA_CONF.key}" style="display:none;">
{/capture}


{* <!-- TIC TAC TOE --> *}
{else if $EASYCAPTCHA_CHALLENGE == 'tictac'}
{html_style}
#easycaptcha table {ldelim}
  width:{$EASYCAPTCHA_CONF.size}px;
  height:{$EASYCAPTCHA_CONF.size}px;
  border-collapse:collapse;
  display:inline-block;
  margin:0;
  background:url('{$ROOT_URL}{$EASYCAPTCHA_PATH}tictac/gen.php?t={$smarty.now}') no-repeat;
}
#easycaptcha td {ldelim}
  border:none;
  padding:0;
}
#easycaptcha label {ldelim}
  display:block;
  width:{math equation="floor(x/3)" x=$EASYCAPTCHA_CONF.size}px;
  height:{math equation="floor(x/3)" x=$EASYCAPTCHA_CONF.size}px;
  cursor:url('{$ROOT_URL}{$EASYCAPTCHA_PATH}tictac/gen.php?cross=96') 16 16, pointer;
}
#easycaptcha input {ldelim}
  display:none;
}
#easycaptcha label.selected {ldelim}
  background:url('{$ROOT_URL}{$EASYCAPTCHA_PATH}tictac/gen.php?cross={$EASYCAPTCHA_CONF.size}') no-repeat;
}
{/html_style}

{footer_script require='jquery'}{literal}
(function($){
$('#easycaptcha input').on('change', function() {
    $('#easycaptcha label').removeClass('selected');
    $(this).parent('label').addClass('selected');
});
}(jQuery));
{/literal}{/footer_script}

{capture name=easycaptcha}
<div id="easycaptcha">
  <table>
    <tr>
      <td><label><input type="radio" name="easycaptcha" value="00"></label></td>
      <td><label><input type="radio" name="easycaptcha" value="10"></label></td>
      <td><label><input type="radio" name="easycaptcha" value="20"></label></td>
    </tr>
    <tr>
      <td><label><input type="radio" name="easycaptcha" value="01"></label></td>
      <td><label><input type="radio" name="easycaptcha" value="11"></label></td>
      <td><label><input type="radio" name="easycaptcha" value="21"></label></td>
    </tr>
    <tr>
      <td><label><input type="radio" name="easycaptcha" value="02"></label></td>
      <td><label><input type="radio" name="easycaptcha" value="12"></label></td>
      <td><label><input type="radio" name="easycaptcha" value="22"></label></td>
    </tr>
  </table>
</div>

<input type="text" name="easycaptcha_key" value="{$EASYCAPTCHA_CONF.key}" style="display:none;">
{/capture}

{/if}