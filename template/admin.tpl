{combine_css path=$EASYCAPTCHA_PATH|cat:'template/style.css'}

{combine_css id='jquery.selectize' path="themes/default/js/plugins/selectize.{$themeconf.colorscheme}.css"}
{combine_script id='jquery.selectize' load='footer' path='themes/default/js/plugins/selectize.min.js'}

{combine_css path=$EASYCAPTCHA_PATH|cat:'template/bgrins-spectrum/spectrum.css'}
{combine_script id='jquery.spectrum' load='footer' path=$EASYCAPTCHA_PATH|cat:'template/bgrins-spectrum/spectrum.js'}

{assign var="spectrum_language" value=$EASYCAPTCHA_PATH|cat:'template/bgrins-spectrum/i18n/jquery.spectrum-'|cat:$lang_info.code|cat:'.js'}
{if 'PHPWG_ROOT_PATH'|constant|cat:$spectrum_language|file_exists}
{combine_script id='jquery.spectrum.'|cat:$lang_info.code load='footer' require='jquery.spectrum' path=$spectrum_language}
{/if}


{footer_script}
// multiselect
$('[name="activate_on[]"], [name="challenges[]"]').selectize({
  plugins: ['remove_button']
});

// Spectrum settings
$("[data-colorpicker]").spectrum({
    showInput: true,
    localStorageKey: "spectrum.easycaptcha",
    showInitial: true,
    preferredFormat: "hex6"
});

// show previews
$('.preview').prevAll('a').on('click', function() {
    $(this).hide();
    $(this).nextAll('.preview').slideDown();
});

$('.theme .title span').css('background-color', $('#content').css('background-color'));
$('.theme .count span').css('background-color', $('#content').css('background-color'));

{literal}
function preview_css_template(options) {
    var style = jQuery(options.id + '_src').text();
    
    // replace direct variables
    jQuery.each(options.inputs, function(key, value) {
        var regex = '{\\$' + options.prefix.replace(/\./, '\\.') + '\\.' + key + '}';
        
        style = style.replace(new RegExp(regex, 'g'), value);
    });
    
    // parse equations
    var regex = '{math equation=["\']{1}([^"\']+)["\']{1}([^}]+)}';
    
    style = style.replace(new RegExp(regex, 'g'),
      function(string, equation, variables) {
          var vars = {},
              regex = '\\s+(\\w+)=\\$' + options.prefix.replace(/\./, '\\.') + '\\.(\\w+)';
              
          variables.replace(new RegExp(regex, 'g'), function(string, key, value) {
              vars[key] = parseFloat(options.inputs[value]);
          });
          console.log(options.prefix, vars);
          for (var key in vars) {
            eval('var ' + key + ' = ' + vars[key] + ';');
          }

          eval('var result = ' + equation + ';');
          return result;
      });
    
    jQuery(options.id).text(style);
}

function preview_image(options) {
    var url = options.url + '&t='+ new Date().getTime();
  
    $.each(options.inputs, function(key, value) {
        url+= '&' + key + '=' + encodeURIComponent(value);
    });
    $(options.img).attr('src', url);
}
{/literal}
{/footer_script}


<div class="titrePage">
  <h2>Easy Captcha</h2>
</div>

<form method="post" action="" class="properties">
<fieldset>
  <legend>{'Configuration'|translate}</legend>

  <ul>
    <li>
      <input type="checkbox" name="guest_only" id="guest_only" {if $easycaptcha.guest_only}checked{/if}>
      <b><label for="guest_only">{'Only for unauthenticated users'|translate}</label></b>
    </li>
    <li>
      <b>{'Comments action'|translate}</b>
      <label><input type="radio" name="comments_action" value="reject" {if $easycaptcha.comments_action == 'reject'}checked="checked"{/if}> {'Reject'|translate}</label>
      <label><input type="radio" name="comments_action" value="moderate" {if $easycaptcha.comments_action == 'moderate'}checked="checked"{/if}> {'Moderate'|translate}</label>
    </li>
    <li>
      <b>{'Activate on'|translate}</b>
      <select name="activate_on[]" multiple placeholder="{'Nowhere'|translate}">
        <option value="picture" {if $easycaptcha.activate_on.picture}selected{/if}>{'Picture comments'|translate}</option>
        {if $easycaptcha_loaded.category}<option value="category" {if $easycaptcha.activate_on.category}selected{/if}>{'Album comments'|translate}</option>{/if}
        <option value="register" {if $easycaptcha.activate_on.register}selected{/if}>{'Register form'|translate}</option>
        {if $easycaptcha_loaded.contactform}<option value="contactform" {if $easycaptcha.activate_on.contactform}selected{/if}>{'Contact form'|translate}</option>{/if}
        {if $easycaptcha_loaded.guestbook}<option value="guestbook" {if $easycaptcha.activate_on.guestbook}selected{/if}>{'Guestbook'|translate}</option>{/if}
      </select>
    </li>
    <li>
      <b>{'Challenge'|translate}</b>
      <select name="challenges[]" multiple placeholder="{'Choose at least one challenge'|translate}">
        <option value="tictac" {if in_array('tictac', $easycaptcha.challenges)}selected{/if}>{'Tic-tac-toe minigame'|translate}</option>
        <option value="drag" {if in_array('drag', $easycaptcha.challenges)}selected{/if}>{'Image drag & drop'|translate}</option>
        <option value="colors" {if in_array('colors', $easycaptcha.challenges)}selected{/if}>{'Colors selection'|translate}</option>
      </select>
    </li>
  </ul>

{foreach from=$easycaptcha_modules item=module}
  {include file=$EASYCAPTCHA_ABS_PATH|cat:$module|cat:'/template/admin.tpl'}
{/foreach}

</fieldset>

  <p class="formButtons"><input class="submit" type="submit" value="{'Submit'|translate}" name="submit"></p>
</form>

<div style="text-align:right;">
  Icons
    [<a href="https://www.iconfinder.com/iconsets/cutecritters" class="externalLink">#1</a>]
    [<a href="https://www.iconfinder.com/iconsets/crystalproject" class="externalLink">#2</a>]
    [<a href="https://www.iconfinder.com/iconsets/UrbanStories-png-Artdesigner-lv" class="externalLink">#3</a>]
    [<a href="https://www.iconfinder.com/iconsets/ie_Bright" class="externalLink">#4</a>]
  | Libraries
    [<a href="http://bgrins.github.io/spectrum" class="externalLink">Spectrum.js</a>]
    [<a href="http://threedubmedia.com" class="externalLink">jQuery.events</a>]
</div>
