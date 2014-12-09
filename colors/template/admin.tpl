{footer_script}
// Colors selection preview
$('.preview-colors').on('change', function() {
    var inputs = {
      'size': $('input[name="colors[size]"]').val(),
      'bd': $('input[name="colors[bd]"]').val() || 'transparent',
      'bg1': $('input[name="colors[bg1]"]').val(),
      'bg2': $('input[name="colors[bg2]"]').val(),
      'ar1': $('input[name="colors[ar1]"]').val(),
      'ar2': $('input[name="colors[ar2]"]').val() || 'transparent',
      'bd1': $('input[name="colors[bd1]"]').val() || 'transparent',
      'bd2': $('input[name="colors[bd2]"]').val() || 'transparent',
      'nb': $('input[name="colors[nb]"]').val(),
    };
      
    preview_css_template({
      id: '#colors_style',
      prefix: 'EASYCAPTCHA.colors',
      inputs: inputs
    });
    
    preview_image({
      img: '.easycaptcha.colors .reference',
      url: '{$EASYCAPTCHA_PATH}colors/gen.php?admin',
      inputs: inputs
    });
    
    $('.easycaptcha.colors .item').remove();
    var html = '',
        nb = parseInt(inputs.nb);

    for (var i=0; i<nb; i++) {
        html+=
        '<div class="item">'+
          '<span class="item-next">&#9650;</span>'+
          '<span class="item-prev">&#9660;</span>'+
        '</div>';
    }

    $('.easycaptcha.colors .answer').append(html);
});

$('#colors_style').appendTo('head'); // move to last position to have priority
$('.preview-colors').eq(0).trigger('change');
{/footer_script}

{* <!-- weird thing to update bunch of CSS --> *}
{html_head}
<style id="colors_style"></style>
<script type="text/template" id="colors_style_src">
{$COLORS_CSS}
.easycaptcha.colors .item { background-color:#78E278; }
</script>
{/html_head}

<fieldset>
  <legend>{'Colors selector options'|translate}</legend>
  
  <ul>
    <li>
      <b>{'Image size'|translate}</b>
      <label><input type="number" name="colors[size]" value="{$easycaptcha.colors.size}" min=26 max=48 class="preview-colors"></label>
    </li>
    <li>
      <b>{'Number of colors'|translate}</b>
      <label><input type="number" name="colors[nb]" value="{$easycaptcha.colors.nb}" min=3 max=5 class="preview-colors"></label>
    </li>
    <li>
      <b>{'Colors'|translate}</b>
      <table class="colors">
        <tr>
          <td>{'Main border'|translate}</td>
          <td>{'Background'|translate} 1</td>
          <td>{'Background'|translate} 2</td>
          <td>{'Border'|translate} 1</td>
          <td>{'Border'|translate} 2</td>
          <td>{'Arrow'|translate}</td>
          <td>{'Arrow background'|translate}</td>
        </tr>
        <tr>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="colors[bd]" value="{$easycaptcha.colors.bd}" class="preview-colors" size="7"></td>
          <td><input type="text" data-colorpicker name="colors[bg1]" value="{$easycaptcha.colors.bg1}" class="preview-colors" size="7"></td>
          <td><input type="text" data-colorpicker name="colors[bg2]" value="{$easycaptcha.colors.bg2}" class="preview-colors" size="7"></td>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="colors[bd1]" value="{$easycaptcha.colors.bd1}" class="preview-colors" size="7"></td>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="colors[bd2]" value="{$easycaptcha.colors.bd2}" class="preview-colors" size="7"></td>
          <td><input type="text" data-colorpicker name="colors[ar1]" value="{$easycaptcha.colors.ar1}" class="preview-colors" size="7"></td>
          <td><input type="text" data-colorpicker data-allow-empty="true" name="colors[ar2]" value="{$easycaptcha.colors.ar2}" class="preview-colors" size="7"></td>
        </tr>
      </table>
    </li>
    <li>
      <b>&nbsp;</b>
      <a class="buttonLike">{'Preview'|translate}</a>
      <div class="preview">
        {include file=$EASYCAPTCHA_ABS_PATH|cat:'colors/template/captcha.tpl' no_dep=true}
        {$smarty.capture.easycaptcha}
      </div>
    </li>
  </ul>
</fieldset>