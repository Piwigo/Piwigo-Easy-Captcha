{if !$no_dep}
{combine_css id='easycaptcha.tictac' path=$EASYCAPTCHA_PATH|cat:'tictac/template/tictac.css' template=true version=$EASYCAPTCHA.lastmod}

{html_style}
.easycaptcha.tictac table {
  background: url('{$ROOT_URL}{$EASYCAPTCHA_PATH}tictac/gen.php?t={$smarty.now}') no-repeat;
}
{/html_style}

{footer_script require='jquery'}
(function($){
$('.easycaptcha.tictac input').on('change', function() {
    $('.easycaptcha.tictac label').removeClass('selected');
    $(this).parent('label').addClass('selected');
});
}(jQuery));
{/footer_script}
{/if}

{capture name=easycaptcha}
<div class="easycaptcha tictac">
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
{/capture}