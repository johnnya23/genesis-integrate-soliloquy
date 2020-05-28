<?php
if (!defined('ABSPATH')) {
    die('No direct access.');
}
/*
Plugin Name: JMA Integrate Soliloquy Slider into Genesis Bootstrap
Description: This plugin integrates the soliloquy slider plugin with jma Genesis Bootstrap
Version: 1.0
Author: John Antonacci
Author URI: http://cleansupersites.com
License: GPL2
*/

if (! defined('JMASOL_DIR')) {
    define('JMASOL_DIR', plugin_dir_path(__FILE__));
}
if (! defined('JMASOL_URL')) {
    define('JMASOL_URL', plugin_dir_url(__FILE__));
}

/* returns true if the soliloquy dynamic slider is selected for
the page (don't change default slug) */
function jma_dynamic_selected($id = 0)
{
    $return = false;
    if (!$id) {
        $id = get_the_ID();
    }
    if ($data = get_post_meta($id, '_jma_ghb_header_footer_key', false)) {
        if (isset($data[0]['slider_id']) && $data[0]['slider_id']) {
            $slider_id = $data[0]['slider_id'];
            $slider = get_post($slider_id);
            if (is_object($slider)) {
                $post_name = $slider->post_name;
                $return =  $slider->post_type == 'soliloquy' && strpos($post_name, 'dynamic') !== false;
            }
        }
    }
    return $return;
}

function jma_soliloquy_files()
{
    wp_enqueue_script('jma_soliloquy_js', JMASOL_URL .  'jma-soliloquy.js');

    wp_enqueue_style('jma_soliloquy_css', JMASOL_URL .  'jma_soliloquy_css.min.css');
    $mods = jma_gbs_get_theme_mods('jma_gbs_');
    $adjusted_icon_color = function_exists('first_is_lighter') && first_is_lighter($mods['footer_bg_color'], $mods['footer_font_color'])? $mods['footer_font_color']: $mods['footer_bg_color'];

    $data = '
        body .soliloquy-container .jma-dynamic-slide .soliloquy-caption {
            width: ' . $mods['site_width'] . 'px;
            max-width: 100%;
        }
        body .soliloquy-container .soliloquy-controls-direction>a {
            background-color: ' . $adjusted_icon_color . '
        }';
    wp_add_inline_style('jma_soliloquy_css', $data);
}

spl_autoload_register('jma_soliloquy_autoloader');
function jma_soliloquy_autoloader($class_name)
{
    if (false !== strpos($class_name, 'JMASol')) {
        $classes_dir = JMASOL_DIR . DIRECTORY_SEPARATOR . 'classes';
        $class_file = $class_name . '.php';
        require_once $classes_dir . DIRECTORY_SEPARATOR . $class_file;
    }
}
new JMASoliloquyPostTypeSelector();

//adds soliloquy sliders to the dropdown
function soliloquy_slider_array_filter($slider_selections)
{
    $sliders = array();
    $posts = get_posts(array(
        'post_type' => 'soliloquy',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'ASC',
        'posts_per_page' => -1
    ));
    if (count($posts)) {
        foreach ($posts as $post) {
            $sliders[$post->ID] = $post->post_title;
        }
    }

    if (count($sliders)) {
        $slider_selections = $sliders;
    }
    return $slider_selections;
}

function jma_integrate_soliloquy_options()
{
    add_filter('jma_ghb_slider_array_filter', 'soliloquy_slider_array_filter');
}
add_action('after_setup_theme', 'jma_integrate_soliloquy_options');


/* displays the slider */
function jma_soliloquy_slider_filter($return, $page_vals)
{
    if (function_exists('soliloquy')) {
        //grab the slider height and width
        $sol_data = get_post_meta($page_vals['slider_id'], '_sol_slider_data', true);
        $return = '<div class="jma-sol-featured-display" style="width:' . $sol_data['config']['slider_width'] . 'px;height:' . $sol_data['config']['slider_height'] . 'px;">' . do_shortcode('[soliloquy id="' . $page_vals['slider_id'] . '"]') . '</div>';
    }
    return $return;
}

function jma_integrate_soliloquy_sliders()
{
    //either display the dynamic slider (true) normal slider selection (false)
    $filter = jma_dynamic_selected() ? 'jma_soliloquy_dynamic_header': 'jma_soliloquy_slider_filter';
    add_filter('jma_ghb_features_image', $filter, 10, 2);
    add_action('wp_enqueue_scripts', 'jma_soliloquy_files', 1000);
}
add_action('template_redirect', 'jma_integrate_soliloquy_sliders', 9999);

function jma_soliloquy_dynamic_header($return, $page_vals)
{
    $sol_data = get_post_meta($page_vals['slider_id'], '_sol_slider_data', true);
    // Put image IDs into correct format for Soliloquy
    if (!function_exists('get_field')) {
        return;
    }
    $settings = get_field('settings');
    $rows = get_field('soliloquy_slides');
    $image_array = array();
    $caption_array = array();

    if (is_array($rows) && count($rows)) {

        // if the field has content...

        foreach ($rows as $row) {//wrap_link
            if (!$row['hide'] && isset($row['image']) && $row['image']) {
                $this_caption = '';
                // Loop through each row of the project_images repeater field
            array_push($image_array, $row['image']); // add the sub-field 'image' to the $image_array array
            //caption_link contains title, target and url
            $link = $row['caption_link'];
                if ($row['caption_title']) {
                    $this_caption .= '<h2>' . $row['caption_title'] . '</h2>';
                }
                if ($row['caption_body']) {
                    $this_caption .= '<div>' . $row['caption_body'] . '</div>';
                }
                if (is_array($link)) {
                    $format = '<a class=\'gbs-btn btn-default\' href=\'%1$s\' target=\'%2$s\' title=\'%3$s\'>%3$s</a>';
                    $this_caption .= sprintf($format, $link['url'], $link['target'], $link['title']);
                }
                //need to temporarily replace commas
                array_push($caption_array, str_replace(',', '%c%', $this_caption));
            }
        }

        // After looping through each row, $image_array now holds an array of the relevant image IDs
    }

    // The Soliloquy Dynamic API needs image IDs to be passed as a comma separated list

    $img_ids = implode(',', $image_array); // Create a variable called $img_ids and populate it with comma separated values from the $image_array array
    $captions = implode(',', $caption_array);

    // Generate the Dynamic Soliloquy slider

    $height = isset($settings['slider_height']) && $settings['slider_height']? $settings['slider_height']: $sol_data['config']['slider_height'];

    $width = isset($settings['slider_width']) && $settings['slider_width']? $settings['slider_width']: $sol_data['config']['slider_width'];


    return '<div class="jma-sol-featured-display" style="width:' . $width . 'px;height:' . $height . 'px;">'.do_shortcode('[soliloquy dynamic="custom-project-images" images="' . $img_ids . '" captions="' . $captions . '"]</div>');
}

function jma_soliloquy_comma_recover($out)
{
    return str_replace('%c%', ',', $out);
}
add_filter('soliloquy_output_after_caption', 'jma_soliloquy_comma_recover');

//generate classes for each slide
function jma_soliloquy_output_item_classes($classes, $slide, $i, $data)
{
    global $post;
    if (!is_array($data)) {
        $data = array();
    }
    if ($data['id'] == 'custom_project_images') {
        $rows = get_field('soliloquy_slides');
        //if (is_page(2)) {
        //}
        if (!is_array($rows)) {
            return $classes;
        }       //get rid of hidden rows and rows with no image selected
        foreach ($rows as $y => $row) {
            if ((is_array($row['hide']) && isset($row['hide'][0]) && $row['hide'][0]) || !(isset($row['image']) && $row['image'])) {
                unset($rows[$y]);
            }
        }
        //renumber array 0,1,2..
        $rows = array_values($rows);

        //proceed with classes
        // $i is value from soliloquy for this slide
        // only slides that are displayed starting with 1,2,3...
        $this_row = $rows[($i-1)];
        $classes[] = 'jma-dynamic-slide';

        if (isset($this_row['class'])) {
            $items = explode(' ', $this_row['class']);
            foreach ($items as $item) {
                if ($item) {
                    $classes[] = $item;
                }
            }
        }
        $components = array('title', 'body', 'link');
        foreach ($components as $component) {
            if (isset($this_row['caption_' . $component]) && !empty($this_row['caption_' . $component])) {
                $classes[] = 'has-' . $component;
            }
        }
        //look in the row for a caption pos
        if (!empty($this_row['wrap_link'])) {
            $classes[] = 'jma-big-btn';
        }
        if (!empty($this_row['caption_position'])) {
            $items = explode('-', $this_row['caption_position']);
            foreach ($items as $item) {
                $classes[] = $item;
            }
        } else {//fall back to settings default caption pos
            $settings = get_field('settings');
            $items = explode('-', $settings['default_caption_position']);
            foreach ($items as $item) {
                $classes[] = $item;
            }
        }
    }
    return $classes;
}
add_filter('soliloquy_output_item_classes', 'jma_soliloquy_output_item_classes', 10, 4);

/* implememt the slider seetings (dimemsions, timing, pausing, autostart) */
function jma_dynamic_soliloquy_pre_data($data)
{
    if ($data['id'] == 'custom_project_images') {
        //force full width
        $data['config']['slider_size'] = 'full_width';

        //overcome bug in soliloquy
        $data['config']['lightbox'] = 0;
        $data['config']['enable_link'] = 0;

        //default to these values
        $data['config']['auto'] = 0;
        $data['config']['hover'] = 0;
        if (is_array(get_field('settings'))) {
            $settings = get_field('settings');
            foreach ($settings as $i => $setting) {
                // we handle caption pos above
                if ($i !== 'default_caption_position') {
                    //pause on hover and autostart
                    if (is_array($setting) && isset($setting[0])) {
                        $data['config'][$i] = $setting[0]? 1: 0;
                    } else {//dimensions and timing
                        if ($setting) {
                            $data['config'][$i] = $setting;
                        }
                    }
                }
            }
        }
    }
    return $data;
}
add_filter('soliloquy_pre_data', 'jma_dynamic_soliloquy_pre_data');


/* ADVANCED CUSTOM FIELDS INTEGATION */
// adds css for acf component display on edit screen
function jma_soliloquy_backend_custom_css()
{
    wp_enqueue_style('soliloquy-back-side-css', JMASOL_URL .'back-side.css');
}
add_action('admin_enqueue_scripts', 'jma_soliloquy_backend_custom_css');

//the settings page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' 	=> 'Header Slider Locations',
        'menu_title'	=> 'Header Slider',
        'menu_slug' 	=> 'jma-header-slider',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}

//all the options (settings and edit screen)

    include('jma-soliloquy-addfieldgroups.php');
