{footer_script}
// Drag & drop preview
var drag_themes = {
{foreach from=$DRAG_THEMES key=theme item=params}
  '{$theme}': '{$EASYCAPTCHA_PATH}drag/themes/{$theme}/{$params.image}',
{/foreach}
};

$('.preview-drag').on('change', function() {
    var inputs = {
      'theme': $('input[name="drag[theme]"]').val(),
      'size': $('input[name="drag[size]"]').val(),
      'nb': $('input[name="drag[nb]"]').val(),
      'bd': $('input[name="drag[bd]"]').val() || 'transparent',
      'bg1': $('input[name="drag[bg1]"]').val(),
      'bg2': $('input[name="drag[bg2]"]').val(),
      'obj': $('input[name="drag[obj]"]').val() || 'transparent',
      'sel': $('input[name="drag[sel]"]').val(),
      'bd1': $('input[name="drag[bd1]"]').val() || 'transparent',
      'bd2': $('input[name="drag[bd2]"]').val() || 'transparent',
      'txt': $('input[name="drag[txt]"]').val(),
    };
    
    preview_css_template({
      id: '#drag_style',
      prefix: 'EASYCAPTCHA.drag',
      inputs: inputs
    });

    // content
    $('.easycaptcha.drag .drag_item').remove();
    var html = '',
        s = parseInt(inputs.size),
        nb = parseInt(inputs.nb),
        image = drag_themes[inputs.theme];

    for (var i=0; i<nb; i++) {
        html+=
        '<div class="drag_item" style="left:'+ (s*0.2+s*1.2*i) +'px;">'+
          '<img src="'+ image +'">'+
        '</div>';
    }

    $('.easycaptcha.drag').prepend(html);
});

$('#drag_style').appendTo('head'); // move to last position to have priority
$('.preview-drag').eq(0).trigger('change');

$('.easycaptcha.drag .drop_zone').on({
    'mouseenter': function() { $(this).addClass('valid'); },
    'mouseleave': function() { $(this).removeClass('valid'); },
});

// Drag & drop theme
$('.drag-theme').on('click', function() {
  $('.drag-theme').removeClass('selected');
  $(this).addClass('selected');
  $('input[name="drag[theme]"]').val($(this).attr('title')).trigger('change');
});
{/footer_script}

{* <!-- weird thing to update bunch of CSS --> *}
{html_head}
<style id="drag_style"></style>
<script type="text/template" id="drag_style_src">
{$DRAG_CSS}
.easycaptcha.drag { display:inline-block !important; }
</script>
{/html_head}

<fieldset>
  <legend>{'Drag & drop options'|translate}</legend>

  <ul>
    <li>
      <b>{'Theme'|translate}</b>
      {foreach from=$DRAG_THEMES key=theme item=params}
      <a class="theme drag-theme {if $easycaptcha.drag.theme == $theme}selected{/if}" title="{$theme}">
        <div class="title"><span>{$theme|ucfirst}</span></div>
        <div class="count"><span>({$params.count})</span></div>
        <img src="{$EASYCAPTCHA_PATH}drag/themes/{$theme}/{$params.image}">
      </a>
      {/foreach}
      <input type="hidden" name="drag[theme]" value="{$easycaptcha.drag.theme}" class="preview-drag">
    </li>
    <li>
      <b>{'Image size'|translate}</b>
      <label><input type="number" name="drag[size]" value="{$easycaptcha.drag.size}" min=24 max=128 class="preview-drag"></label>
    </li>
    <li>
      <b>{'Number of images'|translate}</b>
      <label><input type="number" name="drag[nb]" value="{$easycaptcha.drag.nb}" min=3 max=10 class="preview-drag"></label>
    </li>
    <li>
      <b>{'Colors'|translate}</b>
      <table class="colors">
        <tr>
          <td>{'Main border'|translate}</td>
          <td>{'Background'|translate} 1</td>
          <td>{'Background'|translate} 2</td>
          <td>{'Image'|translate}</td>
          <td>{'Image border'|translate}</td>
          <td>{'Drop'|translate}</td>
          <td>{'Drop border'|translate}</td>
          <td>{'Text'|translate}</td>
        </tr>
        <tr>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="drag[bd]" value="{$easycaptcha.drag.bd}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker name="drag[bg1]" value="{$easycaptcha.drag.bg1}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker name="drag[bg2]" value="{$easycaptcha.drag.bg2}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="drag[obj]" value="{$easycaptcha.drag.obj}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="drag[bd1]" value="{$easycaptcha.drag.bd1}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker name="drag[sel]" value="{$easycaptcha.drag.sel}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="drag[bd2]" value="{$easycaptcha.drag.bd2}" class="preview-drag" size="7"></td>
          <td><input type="text" data-colorpicker name="drag[txt]" value="{$easycaptcha.drag.txt}" class="preview-drag" size="7"></td>
        </tr>
      </table>
    </li>
    <li>
      <b>&nbsp;</b>
      <a class="buttonLike">{'Preview'|translate}</a>
      <div class="preview">
        {include file=$EASYCAPTCHA_ABS_PATH|cat:'drag/template/captcha.tpl' no_dep=true}
        {$smarty.capture.easycaptcha}
      </div>
    </li>
  </ul>
</fieldset>