<?php

function soliloquy_post_group_options()
{
    $args = array(
       'public'   => true,
       '_builtin' => false
    );

    $post_types = get_post_types($args);
    array_unshift($post_types, "page", "post");

    /* general settings page */
    acf_add_local_field_group(array(
        'key' => 'group_5c72a2077eb8e',
        'title' => 'Defaults',
        'fields' => array(
            array(
                'key' => 'soliloquyfield_5c72a7ec47a2cyyy',
                'label' => 'Location',
                'name' => 'jma_soliloquy_location',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => $post_types,
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 1,
                'return_format' => 'object',
                'ui' => 1,
            ),
            array(
                'key' => 'soliloquyfield_5c72ae5531046',
                'label' => 'Post Type',
                'name' => 'jma_soliloquy_post_type',
                'type' => 'post_type_selector',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'select_type' => 'Checkboxes',
            ),
            array(
                'key' => 'soliloquyfield_5cd2ef990fa89',
                'label' => 'Default Class(es)',
                'name' => 'jma_soliloquy_default_class',
                'type' => 'textarea',
                'instructions' => 'these classes will be the defaults for new slides you are responsible for adding functionality for these classes. It might be a good idea to make these descriptive (ie big-brown-btn yellow-title)',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '30',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => 'outline-title special-title',
                'placeholder' => 'outline-title special-title',
                'maxlength' => '',
                'rows' => 3,
                'new_lines' => '',//'br'
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'jma-header-slider',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

    /* pull types from settings page to determine visibility on pages/posts */
    //$posts = get_soliloquyfield('location', 'option');
    $types = get_field('jma_soliloquy_post_type', 'option');
    $posts = get_field('jma_soliloquy_location', 'option');
    $jma_soliloquy_default_class = get_field('jma_soliloquy_default_class', 'option');
    $location = array();
    if (is_array($types)) {
        foreach ($types as $type) {
            $location[] = array(
                array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => $type
            )
        );
        }
    }
    if (is_array($posts)) {
        foreach ($posts as $post) {
            $param = is_page($post)?'page':'post';
            $location[] = array(
                array(
                'param' => $param,
                'operator' => '==',
                'value' => $post->ID
            )
        );
        }
    }

    acf_add_local_field_group(array(
    'key' => 'group_5cd2ea27b2ff3',
    'title' => 'Soliloquy Header',
    'fields' => array(
        array(
            'key' => 'field_5cd746b873744',
            'label' => 'Settings',
            'name' => 'settings',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
                array(
                    'key' => 'field_5cd746e373745',
                    'label' => 'Slider Width',
                    'name' => 'slider_width',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                ),
                array(
                    'key' => 'field_5cd7473e73746',
                    'label' => 'Slider Height',
                    'name' => 'slider_height',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                ),
                array(
                    'key' => 'field_5cd7475e73747',
                    'label' => 'Default Caption Position',
                    'name' => 'default_caption_position',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => 'check-grid',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left-top' => 'left-top',
                        'center-top' => 'center-top',
                        'right-top' => 'right-top',
                        'left-middle' => 'left-middle',
                        'center-middle' => 'center-middle',
                        'right-middle' => 'right-middle',
                        'left-bottom' => 'left-bottom',
                        'center-bottom' => 'center-bottom',
                        'right-bottom' => 'right-bottom',
                    ),
                    'allow_null' => 0,
                    'default_value' => 'center-middle',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_5cd7483e73748',
                    'label' => 'Duration',
                    'name' => 'duration',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                ),
                array(
                    'key' => 'field_5cd7485b73749',
                    'label' => 'Speed',
                    'name' => 'speed',
                    'type' => 'number',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '10',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'min' => '',
                    'max' => '',
                    'step' => '',
                ),
                array(
                    'key' => 'field_5cd7488e7374a',
                    'label' => 'Auto Start',
                    'name' => 'auto',
                    'type' => 'checkbox',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '10',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        1 => 'Start',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(
                        0 => 1,
                    ),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'save_custom' => 0,
                ),
                array(
                    'key' => 'field_5cd74a1b7374b',
                    'label' => 'Pause On Hover',
                    'name' => 'hover',
                    'type' => 'checkbox',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '10',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        1 => 'Pause',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(
                        0 => 0,
                    ),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'save_custom' => 0,
                ),
            ),
        ),
        array(
            'key' => 'field_5cd2eadc43d5f',
            'label' => 'Soliloquy Slides',
            'name' => 'soliloquy_slides',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => 'sol-slides',
                'id' => '',
            ),
            'collapsed' => 'field_5cd2ecebca8ef',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'Add Slide',
            'sub_fields' => array(
                array(
                    'key' => 'field_5cd2eb0f43d60',
                    'label' => 'Image',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => 'Keep in mind a	very wide aspect ratio',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '10',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'id',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => 2,
                    'mime_types' => 'jpeg,jpg,png',
                ),
                array(
                    'key' => 'field_5cd2ef990fa89',
                    'label' => 'Class',
                    'name' => 'class',
                    'type' => 'textarea',
                    'instructions' => 'SPECIAL CLASSES ' . $jma_soliloquy_default_class,
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => $jma_soliloquy_default_class,
                    'placeholder' => $jma_soliloquy_default_class,
                    'maxlength' => '',
                    'rows' => 3,
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_5cd2eba043d61',
                    'label' => 'Caption Position',
                    'name' => 'caption_position',
                    'type' => 'button_group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '8',
                        'class' => 'check-grid',
                        'id' => '',
                    ),
                    'choices' => array(
                        'left-top' => 'left-top',
                        'center-top' => 'center-top',
                        'right-top' => 'right-top',
                        'left-middle' => 'left-middle',
                        'center-middle' => 'center-middle',
                        'right-middle' => 'right-middle',
                        'left-bottom' => 'left-bottom',
                        'center-bottom' => 'center-bottom',
                        'right-bottom' => 'right-bottom',
                    ),
                    'allow_null' => 1,
                    'default_value' => '',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                array(
                    'key' => 'field_5cd2ecebca8ef',
                    'label' => 'Caption Title',
                    'name' => 'caption_title',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => 5,
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_5cd2ed01ca8f0',
                    'label' => 'Caption Body',
                    'name' => 'caption_body',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '27',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => 5,
                    'new_lines' => '',
                ),
                array(
                    'key' => 'field_5cd2ed6dca8f1',
                    'label' => 'Caption Link',
                    'name' => 'caption_link',
                    'type' => 'link',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '15',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_5cd832d23f07f',
                    'label' => 'Hide',
                    'name' => 'hide',
                    'type' => 'checkbox',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '7',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        1 => 'Hide',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(
                        0 => 0,
                    ),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'save_custom' => 0,
                ),
                array(
                    'key' => 'field_5cd832d23f123',
                    'label' => 'Wrap Link',
                    'name' => 'wrap_link',
                    'type' => 'checkbox',
                    'instructions' => 'link image',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '6',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        1 => 'Wrap',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(
                        0 => 0,
                    ),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'save_custom' => 0,
                ),
            ),
        ),
    ),
    'location' => $location,
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => 'Replaces your choice from above for header content',
));
}
add_action('acf/init', 'soliloquy_post_group_options', 999);
