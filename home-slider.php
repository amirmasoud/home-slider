<?php
/* 
Plugin Name: home slider
Plugin URI: http://github.com/amirmasoud/home-slider/
Description: free home slider for wordpress
Version: 1.0
Author: AmirMasoud Sheidayi
Author URI: http://chakosh.ir/
License: GPLv2 or later
*/

/**
 * Add Home slider menu
 */
add_action('init', 'hs_home_slider');
function hs_home_slider() 
{
	//Set up labels
	$labels = array('name' => __('Slides', 'home_slider'),
	'singular_name' => __('Slide', 'home_slider'),
	'add_new' => __('New Slide', 'home_slider'),
	'add_new_item' => __('Add New Slide', 'home_slider'),
    'add_new_item' => __('setting', 'home_slider'),            
	'edit_item' => __('Edit Slide', 'home_slider'),
	'new_item' => __('New Slide', 'home_slider'),
	'view_item' => __('View Slide', 'home_slider'),
	'search_items' => __('Search Slides', 'home_slider'),
	'not_found' =>  __('No slides were found.', 'home_slider'),
	'not_found_in_trash' => __('No slides were found in the trash.', 'home_slider'), 
	'parent_item_colon' => '');
	
	$fields = array('labels' => $labels,
	'public' => false,
	'publicly_queryable' => false,
	'show_ui' => true, 
	'query_var' => true,
	'rewrite' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'menu_icon' => home_url().'/wp-admin/images/generic.png',
	'menu_position' => null,
	'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')); 
	
	register_post_type('home_slide', $fields);
}

add_action('admin_menu', 'hs_register_submenu_page');

/**
 * resgiter submenu of the main menu
 * @return none
 */
function hs_register_submenu_page() 
{
	add_submenu_page( 'edit.php?post_type=home_slide', 'Animation', 'Animation', 'manage_options', 'Animation', 'hs_animation' );
    add_submenu_page( 'edit.php?post_type=home_slide', 'Controls', 'Controls', 'manage_options', 'Controls', 'hs_controls' );
    add_submenu_page( 'edit.php?post_type=home_slide', 'Behaviour', 'Behaviour', 'manage_options', 'Behaviour', 'hs_behaviour' );
    add_submenu_page( 'edit.php?post_type=home_slide', 'Captions', 'Captions', 'manage_options', 'Captions', 'hs_captions' );
    add_submenu_page( 'edit.php?post_type=home_slide', 'Setting', 'Setting', 'manage_options', 'setting', 'hs_setting' );
}

/**
 * helper function for selecting selected options
 * @param  string $option
 * @param  string $value
 * @return boolean
 */
function hs_selected_option ($option, $value)
{
    $selected = 'selected="selected"';
    if($option == $value)
        return $selected;
}

/**
 * make select options form
 * @param  array $options
 * @param  string $type
 * @return html form
 */
function hs_select_options ($options, $type)
{
    $output = "";
    $option = 'home_slider_' . $type;
    $option = get_option($option);

    foreach ($options as $O)
        $output .= '<option value="' . $O . '" ' . hs_selected_option($option, $O) . '>' . $O . '</option>';        

    return $output;
}

/**
 * make input form
 * @param  string $name      input form name
 * @param  string $form_type input form type
 * @return html form
 */
function hs_form_input ($name, $form_type = "text"){
    // make form name
    $option_name = 'home_slider_' . $name;

    // if value of the input set priviously
    $option = get_option($option_name);
    $output = '<input type="'.$form_type.'" value="'.$option.'" name="'.$option_name.'" class="regular-text" />';

    return $output;
}

/**
 * helper function for submit only filled inputs
 * @param  name $name
 * @return none
 */
function hs_submit_form ($name)
{
    $name = "home_slider_" . $name;
    return (get_option($name) == '') ? "" : get_option($name);    
}

/**
 * make animation options panel
 * @return html table
 */
function hs_animation() {
    // Available effects 
    $effects = array('none', 'fade', 'slide', 'kick', 'transfer', 'shuffle', 'explode', 'turnOver', 'chewyBars');

    // Available easing options
    $easing  = array('linear','swing','easeInQuad','easeOutQuad','easeInOutQuad','easeInCubic','easeOutCubic','easeInOutCubic','easeInQuart','easeOutQuart','easeInOutQuart','easeInQuint','easeOutQuint','easeInOutQuint','easeInSine','easeOutSine','easeInOutSine','easeInExpo','easeOutExpo','easeInOutExpo','easeInCirc','easeOutCirc','easeInOutCirc','easeInElastic','easeOutElastic','easeInOutElastic','easeInBack','easeOutBack','easeInOutBack','easeInBounce','easeOutBounce','easeInOutBounce');

    // Direction options
    $slideNextDirection = array('toLeft', 'toRight', 'toTop', 'toBottom');
    $slidePrevDirection = array('toLeft', 'toRight', 'toTop', 'toBottom');
    
    $html = '
        <div class="wrap"><form action="options.php" method="post" name="options">' . wp_nonce_field('update-options') . '
        <table class="form-table" width="100%" cellpadding="10">
        <thead><tr><th><h2>Animation</h2></th></tr></thead>
        <tbody>
        <tr>
        <td>
        <label>Effect</label>
        </td>
        <td>
        <select name="home_slider_effect" id="effect">' .
           hs_select_options($effects,'effect')
        . '</select>
            <p class="description">name of the effect which is used to blend between the slides</p>
        </td>
        </tr>    
        <tr>
        <td>
        <label>Easing</label>
        </td>
        <td>
        <select name="home_slider_easing" id="easing">' .
           hs_select_options($easing,'easing')
        . '</select>
            <p class="description">easing of the animations</p>
        </td>
        </tr>
        <tr>
        <td>
            <label>Effect time</label>
        </td>
        <td>
        '.
        hs_form_input('effecttime','number')
        .'
        <p class="description">duration in ms of the animation to blend the slides</p>
        </td>
        </tr>
        <tr>
        <td>
            <label>Show time</label>
            
        </td>
        <td>'.
            hs_form_input('showtime', 'number')
        .'<p class="description">time in ms how long a single slide is shown, before the animation of the next slide starts</p></td>
        </tr>        
        <tr>
        <td>
            <label>animate active</label>
        </td>
        <td>            
            <select name="home_slider_animateActive">
                <option value="true">true</option>
                <option value="false">false</option>
            </select>
            <p class="description">defines whether the current slide should been animated or not</p>
        </td>
        </tr>
        <tr>
        <td>
            <label>part Delay</label>
            
        </td>
        <td>'.
            hs_form_input('partDelay', 'number')
        .'<p class="description">delay for the animation of each part, only bars effect</p></td>
        </tr>
        <tr>
        <td>
            <label>parts</label>
            
        </td>
        <td>'.
            hs_form_input('parts')
        .'<p class="description">defines the amount of parts into which each slide is sliced, can be a comma-seperated string (x,y) or one integer if you want to have the same amount of parts for x-axis and for y-axis</p></td>
        </tr>        
        <tr>
        <td>
            <label>shiftValue</label>
            
        </td>
        <td>'.
            hs_form_input('shiftValue')
        .'<p class="description">distance which defines the spacing of the slides for some animations, can be a comma-seperated string (x,y) or one integer if you want to have the same distance for x-axis and for y-axis</p></td>
        </tr>
      
        <tr>
        <td>
        <label>slideNextDirection</label>
        </td>
        <td>        
        <select name="home_slider_slideNextDirection" id="slideNextDirection">' .
           hs_select_options($slideNextDirection,'slideNextDirection')
        . '</select>
            <p class="description">the direction for animating the slide, if the next slide should be displayed</p>
        </td>
        </tr>
        <tr>
        <td>
        <label>slidePrevDirection</label>
        </td>
        <td>        
        <select name="home_slider_slidePrevDirection" id="slidePrevDirection">' .
           hs_select_options($slidePrevDirection,'slidePrevDirection')
        . '</select>
           <p class="description">the direction for animating the slide, if the previous slide should be displayed</p>
        </td>
        </tr>
        </tbody>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="home_slider_effect,home_slider_easing,home_slider_effecttime,home_slider_showtime,home_slider_animateActive,home_slider_partDelay,home_slider_parts,home_slider_shiftValue,home_slider_slideNextDirection,home_slider_slidePrevDirection" />

         <button class="button" type="submit">Update</button>
         </form>
         </div>
    ';

    echo $html;

}

/**
 * make controls options
 * @return html table
 */
function hs_controls()
{
    $TF = array('true','false');
    
    $html = '
        <div class="wrap"><form action="options.php" method="post" name="options">' . wp_nonce_field('update-options') . '
        <table class="form-table" width="100%" cellpadding="10">
        <thead><tr><th><h2>Controls</h2></th></tr></thead>
        <tbody>
        <tr>
        <td>
            <label>Effect</label>
        </td>
        <td>
            <select name="home_slider_changeBullets">' .
           hs_select_options($TF,'effect')
        . '</select>
            <p class="description">defines whether the active bullet should be changed before or after the animation</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>controlFadeTime</label>
        </td>
        <td>'.
            hs_form_input('controlFadeTime','number')
        .'<p class="description">duration of the animation for fading the controls</p></td>
        </tr>
        
        <tr>
        <td>
            <label>controlsKeyboard</label>
        </td>
        <td>
            <select name="home_slider_controlsKeyboard">' .
           hs_select_options($TF,'controlsKeyboard')
        . '</select>
            <p class="description">enable/ disable keyboard navigation</p>
        </td>
        </tr>

        
        <tr>
        <td>
            <label>controlsMousewheel</label>
        </td>
        <td>
            <select name="home_slider_controlsMousewheel">' .
           hs_select_options($TF,'controlsMousewheel')
        . '</select>
            <p class="description">enable/ disable mousewheel navigation</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>controlsPlayPause</label>
        </td>
        <td>
            <select name="home_slider_controlsPlayPause">' .
           hs_select_options($TF,'controlsPlayPause')
        . '</select>
            <p class="description">show/ hide play-/ pause-controls</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>controlsPrevNext</label>
        </td>
        <td>
            <select name="home_slider_controlsPrevNext">' .
           hs_select_options($TF,'controlsPrevNext')
        . '</select>
            <p class="description">show/ hide prev-/ next-controls</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>nextText</label>
        </td>
        <td>'.
            hs_form_input('nextText')
        .'<p class="description">text for the next-button</p></td>
        </tr>
        
        <tr>
        <td>
            <label>pauseText</label>
        </td>
        <td>'.
            hs_form_input('pauseText')
        .'<p class="description">text for the pause-button</p></td>
        </tr>
        
        <tr>
        <td>
            <label>playText</label>
        </td>
        <td>'.
            hs_form_input('playText')
        .'<p class="description">text for the play-button</p></td>
        </tr>
        
        <tr>
        <td>
            <label>prevText</label>
        </td>
        <td>'.
            hs_form_input('prevText')
        .'<p class="description">text for the prev-button</p></td>
        </tr>
        
        <tr>
        <td>
            <label>showBullets</label>
        </td>
        <td>
            <select name="home_slider_showBullets">
                <option value="hover">hover</option>
                <option value="always">always</option>
                <option value="never">never</option>
            </select>
            <p class="description">describes whether the slide navigation should be displayed always, on hover or never</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>showControls</label>
        </td>
        <td>
            <select name="home_slider_showControls">
                <option value="hover">hover</option>
                <option value="always">always</option>
                <option value="never">never</option>
            </select>
            <p class="description">describes whether the controls should be displayed always, on hover or never</p>
        </td>
        </tr>
        
        </tbody>
        <table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="home_slider_changeBullets,home_slider_controlFadeTime,home_slider_controlsKeyboard,home_slider_controlsMousewheel,home_slider_controlsPlayPause,home_slider_controlsPrevNext,home_slider_nextText,home_slider_pauseText,home_slider_playText,home_slider_prevText,home_slider_showBullets,home_slider_showControls" />

         <button class="button" type="submit">Update</button>
         </form>
         </div>';
            
            
        echo $html;
    
}

/**
 * make behaviour options panel
 * @return html table
 */
function hs_behaviour() 
{
    $TF = array('true','false');
    
    $html = '
        <div class="wrap"><form action="options.php" method="post" name="options">' . wp_nonce_field('update-options') . '
        <table class="form-table" width="100%" cellpadding="10">
        <thead><tr><th><h2>Animation</h2></th></tr></thead>
        <tbody>

        <tr>
        <td>
            <label>autoPlay</label>
        </td>
        <td>            
            <select name="home_slider_autoPlay">' .
           hs_select_options($TF,'autoPlay')
        . '</select>
            <p class="description">determines whether the slideshow should start automatically on init or not</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>cycled</label>
        </td>
        <td>            
            <select name="home_slider_cycled">' .
           hs_select_options($TF,'cycled')
        . '</select>
            <p class="description">repeat the slideshow after the end was reached</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>pauseOnHover</label>
        </td>
        <td>            
            <select name="home_slider_pauseOnHover">' .
           hs_select_options($TF,'pauseOnHover')
        . '</select>
            <p class="description">pauses the animation on hover while auto play is running</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>randomOrder</label>
        </td>
        <td>            
            <select name="home_slider_randomOrder">' .
           hs_select_options($TF,'randomOrder')
        . '</select>
            <p class="description">linear or shuffled order for items</p>
        </td>
        </tr>

        </tbody>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="home_slider_autoPlay,home_slider_cycled,home_slider_pauseOnHover,home_slider_randomOrder" />

         <button class="button" type="submit">Update</button>
         </form>
         </div>
    ';

    echo $html;
}

/**
 * make caption options panel
 * @return html table
 */
function hs_captions()
{
    // available caption options
    $show_caption = array('hover','always','never');

    $html = '
        <div class="wrap"><form action="options.php" method="post" name="options">' . wp_nonce_field('update-options') . '
        <table class="form-table" width="100%" cellpadding="10">
        <thead><tr><th><h2>Animation</h2></th></tr></thead>
        <tbody>

        <tr>
        <td>
            <label>captionsFadeTime</label>
        </td>
        <td>            
            ' .
            hs_form_input('captionsFadeTime','number')
        . '
            <p class="description">duration of the animation for fading the captions</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>captionsOpacity</label>
        </td>
        <td>            
            ' .
        hs_form_input('captionsOpacity')
            . '
            <p class="description">transparency of the captions</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>showCaptions</label>
        </td>
        <td>            
            <select name="home_slider_pauseOnHover">' .
           hs_select_options($show_caption,'showCaptions')
        . '</select>
            <p class="description">describes whether the captions should be displayed always, on hover or never</p>
        </td>
        </tr>
        
        </tbody>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="home_slider_captionsFadeTime,home_slider_captionsOpacity,home_slider_showCaptions" />

         <button class="button" type="submit">Update</button>
         </form>
         </div>
    ';

    echo $html;    
}

/**
 * make setting options panel
 * @return html table
 */
function hs_setting()
{
    $html = '
        <div class="wrap"><form action="options.php" method="post" name="options">' . wp_nonce_field('update-options') . '
        <table class="form-table" width="100%" cellpadding="10">
        <thead><tr><th><h2>Animation</h2></th></tr></thead>
        <tbody>

        <tr>
        <td>
            <label>width</label>
        </td>
        <td>            
            ' .
            hs_form_input('width')
        . '
            <p class="description">width of the slider( enter like: 600px or 90% )</p>
        </td>
        </tr>
        
        <tr>
        <td>
            <label>height</label>
        </td>
        <td>            
            ' .
        hs_form_input('height')
            . '
            <p class="description">height of the slider( enter like: 300px )</p>
        </td>
        </tr>

        </tbody>
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="home_slider_width,home_slider_height" />

         <button class="button" type="submit">Update</button>
         </form>
         </div>
    ';

    echo $html;
}

/**
 * add new shortcode [show_home_slider]
 */
add_shortcode("show_home_slider", "hs_show_slider");
function hs_show_slider()
{
    $option = array('px','%');
    $slider = '<ul id="home-slider" style="height:'. get_option('home_slider_height') . '; width:'. get_option('home_slider_width') . ';">';
    $query = new WP_Query('post_type=home_slide&posts_per_page=-1&order=ASC&orderby=menu_order');
    if($query->post_count > 0): 
        $query_count = 0;
        while($query->have_posts()): 
            $query->the_post();
            $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), array(1500, 7000), false, '');
            $slider .= '<li><img src="' . $image_url[0] . '" alt="" class="img-responsive" /></li>';
            endwhile;
    endif;
    $slider .= '</ul>';
    echo $slider;
}

/**
 * add home slider scripts
 */
add_action('wp_enqueue_scripts', 'hs_home_slider_scripts');
function hs_home_slider_scripts()
{
    wp_enqueue_script('jquery');
    
    wp_register_script('easing', plugins_url('js/rhinoslider/easing.js', __FILE__),array("jquery"));
    wp_enqueue_script('easing');
    
    wp_register_script('mousewheel', plugins_url('js/rhinoslider/mousewheel.js', __FILE__),array("jquery"));
    wp_enqueue_script('mousewheel');
    
    wp_register_script('rhinoslider-1.05.min', plugins_url('js/rhinoslider/rhinoslider-1.05.min.js', __FILE__),array("jquery"));
    wp_enqueue_script('rhinoslider-1.05.min');
    
    wp_register_script('rhinoslider-options', plugins_url('js/rhinoslider/rhinoslider-options.js', __FILE__),array("jquery"));
    wp_enqueue_script('rhinoslider-options');    
    
    wp_enqueue_script('rhinoslider-options');

    $avalable_options = array(
        'effect',
        'easing',
        'effecttime',
        'showtime',
        'animateActive',
        'partDelay',
        'parts',
        'shiftValue',
        'slideNextDirection',
        'slidePrevDirection',
        'changeBullets',
        'controlFadeTime',
        'controlsKeyboard',
        'controlsMousewheel',
        'controlsPlayPause',
        'controlsPrevNext',
        'nextText',
        'pauseText',
        'playText',
        'prevText',
        'showBullets',
        'showControls',
        'autoPlay',
        'cycled',
        'pauseOnHover',
        'randomOrder',
        'captionsFadeTime',
        'captionsOpacity',
        'showCaptions',
        'width',
        'height'
    );
    
    $config_array[] = array();
    
    foreach($avalable_options as $AO){
        $submited = submit_form($AO);
        $config_array[$AO] = $submited;
    }

    wp_localize_script('rhinoslider-options', 'setting', $config_array);
}

/**
 * add home slider styles
 */
add_action('wp_enqueue_scripts', 'hs_home_slider_styles');
function hs_home_slider_styles(){

    wp_register_style('rhinoslider-1.05', plugins_url('css/rhinoslider/rhinoslider-1.05.css', __FILE__));
    wp_register_style('home_slider_style', plugins_url('css/home_slider_style.css', __FILE__));

    wp_enqueue_style('rhinoslider-1.05');
    wp_enqueue_style('home_slider_style');
}

?>