{footer_script}
// Tic-tac-toe preview
$('.preview-tictac').on('change', function() {    
    preview_image({
      img: '#preview-tictac',
      url: '{$EASYCAPTCHA_PATH}tictac/gen.php?admin',
      inputs: {
        'size': $('input[name="tictac[size]"]').val(),
        'bg1': $('input[name="tictac[bg1]"]').val(),
        'bg2': $('input[name="tictac[bg2]"]').val(),
        'bd': $('input[name="tictac[bd]"]').val() || 'transparent',
        'obj': $('input[name="tictac[obj]"]').val(),
        'sel': $('input[name="tictac[sel]"]').val()
      }
    });
});

$('.preview-tictac').eq(0).trigger('change');
{/footer_script}

<fieldset>
  <legend>{'Tic-tac-toe options'|translate}</legend>

  <ul>
    <li>
      <b>{'Image size'|translate}</b>
      <input type="number" name="tictac[size]" value="{$easycaptcha.tictac.size}" min=32 step=8 max=256 class="preview-tictac">
    </li>
    <li>
      <b>{'Colors'|translate}</b>
      <table class="colors">
        <tr>
          <td>{'Background'|translate} 1</td>
          <td>{'Background'|translate} 2</td>
          <td>{'Border'|translate}</td>
          <td>{'Marks'|translate}</td>
          <td>{'Selection'|translate}</td>
        </tr>
        <tr>
          <td><input type="text" data-colorpicker name="tictac[bg1]" value="{$easycaptcha.tictac.bg1}" class="preview-tictac" size="7"></td>
          <td><input type="text" data-colorpicker name="tictac[bg2]" value="{$easycaptcha.tictac.bg2}" class="preview-tictac" size="7"></td>
          <td><input type="text" data-colorpicker name="tictac[bd]" value="{$easycaptcha.tictac.bd}" class="preview-tictac" size="7"></td>
          <td><input type="text" data-colorpicker name="tictac[obj]" value="{$easycaptcha.tictac.obj}" class="preview-tictac" size="7"></td>
          <td><input type="text" data-colorpicker name="tictac[sel]" value="{$easycaptcha.tictac.sel}" class="preview-tictac" size="7"></td>
        </tr>
      </table>
    </li>
    <li>
      <b>&nbsp;</b>
      <a class="buttonLike">{'Preview'|translate}</a>
      <div class="preview">
        <img id="preview-tictac" src="">
      </div>
    </li>
  </ul>
</fieldset>