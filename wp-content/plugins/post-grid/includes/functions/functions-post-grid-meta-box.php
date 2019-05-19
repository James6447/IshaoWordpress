<?php

/*
* @Author 		PickPlugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('post_grid_settings_tabs_content_shortcode', 'post_grid_settings_tabs_content_shortcode',10, 2);

function post_grid_settings_tabs_content_shortcode($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();


    ?>
    <div class="section">
        <div class="section-title">Shortcodes</div>
        <p class="description section-description">Simply copy these shortcode and user under content</p>


        <?php


        ob_start();

        ?>

        <div class="copy-to-clipboard">
            <input type="text" value="[post_grid id='<?php echo $post_id;  ?>']"> <span class="copied">Copied</span>
            <p class="description">You can use this shortcode under post content</p>
        </div>

        <div class="copy-to-clipboard">
            To avoid conflict:<br>
            <input type="text" value="[post_grid_pickplugins id='<?php echo $post_id;  ?>']"> <span class="copied">Copied</span>
            <p class="description">To avoid conflict with 3rd party shortcode also used same <code>[post_grid]</code>You can use this shortcode under post content</p>
        </div>

        <div class="copy-to-clipboard">
            <textarea cols="50" rows="1" style="background:#bfefff" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[post_grid id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea> <span class="copied">Copied</span>
            <p class="description">PHP Code, you can use under theme .php files.</p>
        </div>

        <div class="copy-to-clipboard">
            <textarea cols="50" rows="1" style="background:#bfefff" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[post_grid_pickplugins id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea> <span class="copied">Copied</span>
            <p class="description">To avoid conflict, PHP code you can use under theme .php files.</p>
        </div>

        <style type="text/css">
            .copy-to-clipboard{}
            .copy-to-clipboard .copied{
                display: none;
                background: #e5e5e5;
                padding: 4px 10px;
                line-height: normal;
            }
        </style>

        <script>
            jQuery(document).ready(function($){
                $(document).on('click', '.copy-to-clipboard input, .copy-to-clipboard textarea', function () {
                    $(this).focus();
                    $(this).select();
                    document.execCommand('copy');
                    $(this).parent().children('.copied').fadeIn().fadeOut(2000);
                })
            })
        </script>
        <?php
        $html = ob_get_clean();
        $args = array(
            'id'		=> 'post_grid_shortcodes',
            'title'		=> __('Post Grid Shortcode','post-grid'),
            'details'	=> '',
            'type'		=> 'custom_html',
            'html'		=> $html,
        );
        $settings_tabs_field->generate_field($args, $post_id);


        ?>
    </div>
    <?php
}




add_action('post_grid_settings_tabs_content_query_post', 'post_grid_settings_tabs_content_query_post', 10, 2);

function post_grid_settings_tabs_content_query_post($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_meta_box = new class_post_grid_meta_box();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);


    //var_dump($post_grid_categories_array);



    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);



    $post_types = !empty($post_grid_meta_options['post_types']) ? $post_grid_meta_options['post_types'] : array();
    $categories = !empty($post_grid_meta_options['categories']) ? $post_grid_meta_options['categories'] : array();
    $categories_relation = !empty($post_grid_meta_options['categories_relation']) ? $post_grid_meta_options['categories_relation'] : 'OR';
    $taxonomies = !empty($post_grid_meta_options['taxonomies']) ? $post_grid_meta_options['taxonomies'] : array();

    $meta_query = !empty($post_grid_meta_options['meta_query']) ? $post_grid_meta_options['meta_query'] : array();
    $meta_query_relation = !empty($post_grid_meta_options['meta_query_relation']) ? $post_grid_meta_options['meta_query_relation'] : 'OR';

    $extra_query_parameter = !empty($post_grid_meta_options['extra_query_parameter']) ? $post_grid_meta_options['extra_query_parameter'] : '';

    $post_status = !empty($post_grid_meta_options['post_status']) ? $post_grid_meta_options['post_status'] : array();
    $query_order = !empty($post_grid_meta_options['query_order']) ? $post_grid_meta_options['query_order'] : '';
    $query_orderby = !empty($post_grid_meta_options['query_orderby']) ? $post_grid_meta_options['query_orderby'] : '';
    $query_orderby_meta_key = !empty($post_grid_meta_options['query_orderby_meta_key']) ? $post_grid_meta_options['query_orderby_meta_key'] : '';

    $posts_per_page = !empty($post_grid_meta_options['posts_per_page']) ? $post_grid_meta_options['posts_per_page'] : '';
    $offset = isset($post_grid_meta_options['offset']) ? $post_grid_meta_options['offset'] : '0';
    $exclude_post_id = isset($post_grid_meta_options['exclude_post_id']) ? $post_grid_meta_options['exclude_post_id'] : '';

    $keyword = !empty($post_grid_meta_options['keyword']) ? $post_grid_meta_options['keyword'] :'';
    $permission_query = !empty($post_grid_meta_options['permission_query']) ? $post_grid_meta_options['permission_query'] :'';
    $ignore_archive = !empty($post_grid_meta_options['ignore_archive']) ? $post_grid_meta_options['ignore_archive'] :'';
    $sticky_post_query_type = !empty($post_grid_meta_options['sticky_post_query']['type']) ? $post_grid_meta_options['sticky_post_query']['type'] :'none';
    $ignore_sticky_posts = !empty($post_grid_meta_options['sticky_post_query']['ignore_sticky_posts']) ? $post_grid_meta_options['sticky_post_query']['ignore_sticky_posts'] :0;
    $date_query_type = !empty($post_grid_meta_options['date_query']['type']) ? $post_grid_meta_options['date_query']['type'] : 'none';

    $extact_date_year = !empty($post_grid_meta_options['date_query']['extact_date']['year']) ? $post_grid_meta_options['date_query']['extact_date']['year'] : '';
    $extact_date_month = !empty($post_grid_meta_options['date_query']['extact_date']['month']) ? $post_grid_meta_options['date_query']['extact_date']['month'] : '';
    $extact_date_day = !empty($post_grid_meta_options['date_query']['extact_date']['day']) ? $post_grid_meta_options['date_query']['extact_date']['day'] : '';
    $between_two_date_after_year = !empty($post_grid_meta_options['date_query']['between_two_date']['after']['year']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['year'] : '';
    $between_two_date_after_month = !empty($post_grid_meta_options['date_query']['between_two_date']['after']['month']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['month'] : '';
    $between_two_date_after_day = !empty($post_grid_meta_options['date_query']['between_two_date']['after']['day']) ? $post_grid_meta_options['date_query']['between_two_date']['after']['day'] : '';
    $between_two_date_before_year = !empty($post_grid_meta_options['date_query']['between_two_date']['before']['year']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['year'] : '';
    $between_two_date_before_month = !empty($post_grid_meta_options['date_query']['between_two_date']['before']['month']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['month'] : '';
    $between_two_date_before_day = !empty($post_grid_meta_options['date_query']['between_two_date']['before']['day']) ? $post_grid_meta_options['date_query']['between_two_date']['before']['day'] : '';
    $between_two_date_inclusive = !empty($post_grid_meta_options['date_query']['between_two_date']['inclusive']) ? $post_grid_meta_options['date_query']['between_two_date']['inclusive'] : 'true';


    $author_query_type = !empty($post_grid_meta_options['author_query']['type']) ? $post_grid_meta_options['author_query']['type'] : 'none';
    $author__in = !empty($post_grid_meta_options['author_query']['author__in']) ? $post_grid_meta_options['author_query']['author__in'] : '';
    $author__not_in = !empty($post_grid_meta_options['author_query']['author__not_in']) ? $post_grid_meta_options['author_query']['author__not_in'] : '';

    $password_query_type = !empty($post_grid_meta_options['password_query']['type']) ? $post_grid_meta_options['password_query']['type'] : 'none';
    $password_query_has_password = !empty($post_grid_meta_options['password_query']['has_password']) ? $post_grid_meta_options['password_query']['has_password'] : 'null';
    $password_query_post_password = !empty($post_grid_meta_options['password_query']['post_password']) ? $post_grid_meta_options['password_query']['post_password'] : '';




    $post_taxonomies_arr = post_grid_get_taxonomies($post_types)



    ?>



    <div class="section">
        <div class="section-title">Query Post</div>
        <p class="description section-description">Set the option for display and query posts.</p>


        <?php
        $args = array(
            'id'		=> 'post_types',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post types','post-grid'),
            'details'	=> __('Select your desired post types here you want to display post from, you can choose multiple post type.','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> true,
            'value'		=> $post_types,
            'default'		=> array('post'),
            'args'		=> $post_grid_posttypes_array,
        );

        $settings_tabs_field->generate_field($args, $post_id);




        ?>
        <div class="setting-field">
            <div class="field-lable">Post Taxonomies & terms</div>

            <div class="field-input">


                <?php
               // echo post_grid_get_categories($post_id);
                ?>
                <div class="pg-expandable">

                    <?php
                    if(!empty($post_taxonomies_arr)):
                    foreach ($post_taxonomies_arr as $taxonomyIndex => $taxonomy){

                        $taxonomy_term_arr = array();
                        $the_taxonomy = get_taxonomy($taxonomy);

                        $terms_relation = isset($taxonomies[$taxonomy]['terms_relation']) ? $taxonomies[$taxonomy]['terms_relation'] : 'IN';
                        $terms = isset($taxonomies[$taxonomy]['terms']) ? $taxonomies[$taxonomy]['terms'] : array();
                        $checked = isset($taxonomies[$taxonomy]['checked']) ? $taxonomies[$taxonomy]['checked'] : '';
                        //var_dump($terms_relation);
                        $taxonomy_terms = get_terms( $taxonomy, array(
                            'hide_empty' => false,
                        ) );


                        //var_dump($taxonomy_terms);

                        if(!empty($taxonomy_terms))
                        foreach ($taxonomy_terms as $taxonomy_term){


                            $taxonomy_term_arr[$taxonomy_term->term_id] =$taxonomy_term->name.'('.$taxonomy_term->count.')';
                        }

                        $taxonomy_term_arr = !empty($taxonomy_term_arr) ? $taxonomy_term_arr : array();

                        ?>




                        <div class="item">
                            <div class="header">
                            <span class="expand-collapse pg-tooltip tooltipstered">
                                <i class="fa fa-expand"></i>
                                <i class="fas fa-compress"></i>
                            </span>
                                <label><input type="checkbox" <?php if(!empty($checked)) echo 'checked'; ?>  name="post_grid_meta_options[taxonomies][<?php echo $taxonomy; ?>][checked]" value="<?php echo $taxonomy; ?>" /> <?php echo $the_taxonomy->labels->name; ?>(<?php echo $taxonomy; ?>)</label>
                            </div>
                            <div class="options">
                                <pre><?php //echo var_export($taxonomy, true); ?></pre>


                                <?php






                                $args = array(
                                    'id'		=> 'terms',
                                    'css_id'		=> 'terms-'.$taxonomyIndex,
                                    'parent'		=> 'post_grid_meta_options[taxonomies]['.$taxonomy.']',
                                    'title'		=> __('Categories or Terms','post-grid'),
                                    'details'	=> __('Select post terms or categories','post-grid'),
                                    'type'		=> 'select2',
                                    'multiple'		=> true,
                                    'value'		=> $terms,
                                    'default'		=> array(),
                                    'args'		=> $taxonomy_term_arr,
                                );

                                $settings_tabs_field->generate_field($args, $post_id);





                                $args = array(
                                    'id'		=> 'terms_relation',
                                    'parent'		=> 'post_grid_meta_options[taxonomies]['.$taxonomy.']',
                                    'title'		=> __('Terms relation','post-grid'),
                                    'details'	=> __('Choose term relation. some option only available in pro','post-grid'),
                                    'type'		=> 'radio',
                                    'for'		=> $taxonomy,
                                    'multiple'		=> true,
                                    'value'		=> $terms_relation,
                                    'default'		=> 'IN',
                                    'args'		=> array(
                                        'IN'=>__('IN','post-grid'),
                                        'NOT IN'=>__('NOT IN','post-grid'),
                                        'AND'=>__('AND','post-grid'),
                                    ),
                                );

                                $settings_tabs_field->generate_field($args, $post_id);

                                ?>

                            </div>
                        </div>
                        <?php




                    }else:
                        echo __('Please choose at least one post types. save/update post grid','post-grid');

                    endif;


                    ?>





                </div>



                <p class="description">Select post categories & terms.</p>
            </div>




        </div>

        <?php
        $args = array(
            'id'		=> 'categories_relation',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Taxonomies relation','post-grid'),
            'details'	=> __('Choose Taxonomies relation.','post-grid'),
            'type'		=> 'radio',
            //'for'		=> $taxonomy,
            'multiple'		=> true,
            'value'		=> $categories_relation,
            'default'		=> 'IN',
            'args'		=> array(
                'OR'=>__('OR','post-grid'),
                'AND'=>__('AND','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>

        <?php


        ob_start();



        //echo '<pre>'.var_export($meta_query, true).'</pre>';
        ?>

        <script>
            jQuery(document).ready(function($){

                $(document).on('click', '.add-meta-query', function(e){
                    var arg_type  = $(this).attr('arg_type');
                    var index = $.now();

                    var arg_single_html = '<div class="item">\n' +
                            '                <div class="header">\n' +
                            '                    <span class="remove"><i class="fa fa-times"></i></span>\n' +
                            '                    <span class="move pg-tooltip" title="<?php echo __("Move", 'post-grid'); ?>"><i class="fa fa-bars"></i></span>\n' +
                            '                    <span class="expand-collapse pg-tooltip" title="<?php echo __("Expand or collapse", 'post-grid'); ?>">\n' +
                            '                            <i class="fa fa-expand"></i>\n' +
                            '                            <i class="fa fa-compress"></i>\n' +
                            '                    </span>Single Query\n' +
                            '                </div>\n' +
                            '                <div class="options">\n' +
                            '                    <input type="hidden" name="post_grid_meta_options[meta_query]['+index+'][arg_type]" value="single" /><br>\n' +
                            '                    <?php echo __("Key", 'post-grid'); ?><br />\n' +
                            '                    <input type="text" name="post_grid_meta_options[meta_query]['+index+'][key]" value="" /><br>\n' +
                            '                    <?php echo __("Value", 'post-grid'); ?><br />\n' +
                            '                    <input type="text" name="post_grid_meta_options[meta_query]['+index+'][value]" value="" /><br>\n' +
                            '                    <?php echo __("Compare", 'post-grid'); ?><br />\n' +
                            '                    <input type="text" name="post_grid_meta_options[meta_query]['+index+'][compare]" value="" /><br>\n' +
                            '                    <?php echo __("Type", 'post-grid'); ?><br />\n' +
                            '                    <input type="text" name="post_grid_meta_options[meta_query]['+index+'][type]" value="" /><br>\n' +
                            '                </div>\n' +
                            '            </div>';


                    var arg_group_html = '<div class="item">\n'+
                            '                <div class="header">\n'+
                            '                    <span class="remove"><i class="fa fa-times"></i></span>\n'+
                            '                    <span class="move pg-tooltip" title="<?php echo __("Move", 'post-grid'); ?>"><i class="fa fa-bars"></i></span>\n'+
                            '                    <span class="expand-collapse pg-tooltip" title="<?php echo __("Expand or collapse", 'post-grid'); ?>">\n'+
                            '                            <i class="fa fa-expand"></i>\n'+
                            '                            <i class="fa fa-compress"></i>\n'+
                            '                    </span> Group Query\n'+
                            '                </div>\n'+
                            '                <div class="options">\n'+
                            '                    <input type="hidden" name="post_grid_meta_options[meta_query]['+index+'][arg_type]" value="group" />\n'+
                            '                           Relation: <label>\n'+
                            '                               <input checked type="radio" name="post_grid_meta_options[meta_query]['+index+'][relation]" value="OR" />OR\n'+
                            '                          </label>\n'+
                            '                           <label>\n'+
                            '                               <input type="radio" name="post_grid_meta_options[meta_query]['+index+'][relation]" value="AND" />AND\n'+
                            '                           </label><br>\n'+
                            '                    <div class="button add-meta-query-child " index="'+index+'"><?php _e("Add", 'post-grid'); ?></div><br><br>\n'+
                            '                    <div class="group-query-list group-query-list-'+index+' pg-expandable">\n'+
                            '                    </div>\n'+
                            '                </div>\n'+
                            '            </div>';


                            if(arg_type == 'single'){
                                $('.meta-query-list').append(arg_single_html);

                            }else if(arg_type == 'group'){
                                $('.meta-query-list').append(arg_group_html);
                            }

                })


                $(document).on('click', '.add-meta-query-child', function(e){

                    var index  = $(this).attr('index');
                    var child_index = $.now();

                    var arg_html = '<div><div><span class="remove"><i class="fa fa-times"></i></span><br><?php echo __("Key", 'post-grid'); ?><br />\n' +
                        '        <input type="text" name="post_grid_meta_options[meta_query]['+index+'][args]['+child_index+'][key]" value="" /><br>\n' +
                        '        <?php echo __("Value", 'post-grid'); ?><br />\n' +
                        '        <input type="text" name="post_grid_meta_options[meta_query]['+index+'][args]['+child_index+'][value]" value="" /><br>\n' +
                        '        <?php echo __("Compare", 'post-grid'); ?><br />\n' +
                        '        <input type="text" name="post_grid_meta_options[meta_query]['+index+'][args]['+child_index+'][compare]" value="" /><br>\n' +
                        '        <?php echo __("Type", 'post-grid'); ?><br />\n' +
                        '        <input type="text" name="post_grid_meta_options[meta_query]['+index+'][args]['+child_index+'][type]" value="" /><br><hr></div></div>';

                    $('.group-query-list-'+index).append(arg_html);
                })










            })
        </script>



















        <div class="button add-meta-query" arg_type="single"><?php _e('Add Single', 'post-grid'); ?></div>
        <div class="button add-meta-query" arg_type="group"><?php _e('Add Group', 'post-grid'); ?></div>
        <br><br>
        <div class="meta-query-list pg-expandable">

            <?php

            if(!empty($meta_query)):
                foreach ($meta_query as  $meta_queryIndex=>$meta_queryData):
                    $arg_type = $meta_queryData['arg_type'];



                    if($arg_type == 'single'):
                        ?>
                        <div class="item">
                            <div class="header">
                                <span class="remove"><i class="fa fa-times"></i></span>
                                <span class="move pg-tooltip" title="<?php echo __("Move", 'post-grid'); ?>"><i class="fa fa-bars"></i></span>
                                <span class="expand-collapse pg-tooltip" title="<?php echo __("Expand or collapse", 'post-grid'); ?>">
                                    <i class="fa fa-expand"></i>
                                    <i class="fa fa-compress"></i>
                                </span>Single Query
                            </div>
                            <div class="options">
                                <input type="hidden" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][arg_type]" value="single" /><br>
                                <?php echo __("Key", 'post-grid'); ?><br />
                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][key]" value="<?php echo $meta_queryData['key']; ?>" /><br>
                                <?php echo __("Value", 'post-grid'); ?><br />
                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][value]" value="<?php echo $meta_queryData['value']; ?>" /><br>
                                <?php echo __("Compare", 'post-grid'); ?><br />
                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][compare]" value="<?php echo $meta_queryData['compare']; ?>" /><br>
                                <?php echo __("Type", 'post-grid'); ?><br />
                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][type]" value="<?php echo $meta_queryData['type']; ?>" /><br>
                            </div>
                        </div>
                        <?php


                    elseif($arg_type == 'group'):
                        $args = isset($meta_queryData['args']) ? $meta_queryData['args'] : array();
                        $relation = isset($meta_queryData['relation']) ? $meta_queryData['relation'] : array();

                        ?>
                        <div class="item">
                            <div class="header">
                                <span class="remove"><i class="fa fa-times"></i></span>
                                <span class="move pg-tooltip" title="<?php echo __('Move', 'post-grid'); ?>"><i class="fa fa-bars"></i></span>
                                <span class="expand-collapse pg-tooltip" title="<?php echo __('Expand or collapse', 'post-grid'); ?>">
                                    <i class="fa fa-expand"></i>
                                    <i class="fa fa-compress"></i>
                                </span> Group Query
                            </div>
                            <div class="options">
                                <input type="hidden" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][arg_type]" value="group" />
                                Relation:<br>
                                <label><input <?php if($relation == 'OR') echo 'checked'; ?> type="radio" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][relation]" value="OR" />OR</label>
                                <label><input <?php if($relation == 'AND') echo 'checked'; ?> type="radio" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][relation]" value="AND" />AND</label>
                                <br><br />
                                <div class="button add-meta-query-child" index="<?php echo $meta_queryIndex; ?>"><?php _e('Add', 'post-grid'); ?></div><br /><br />
                                <div class="group-query-list group-query-list-<?php echo $meta_queryIndex; ?> pg-expandable">

                                    <?php
                                    if(!empty($args)):
                                        foreach ($args as $argIndex=>$arg){

                                            ?>
                                            <div class="">
                                                <div class="">
                                                    <span class="remove"><i class="fa fa-times"></i></span><br />
                                                <input type="hidden" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][args][<?php echo $argIndex; ?>][arg_type]" value="single" /><br>
                                                <?php echo __("Key", 'post-grid'); ?><br />
                                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][args][<?php echo $argIndex; ?>][key]" value="<?php echo $arg['key']; ?>" /><br>
                                                <?php echo __("Value", 'post-grid'); ?><br />
                                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][args][<?php echo $argIndex; ?>][value]" value="<?php echo $arg['value']; ?>" /><br>
                                                <?php echo __("Compare", 'post-grid'); ?><br />
                                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][args][<?php echo $argIndex; ?>][compare]" value="<?php echo $arg['compare']; ?>" /><br>
                                                <?php echo __("Type", 'post-grid'); ?><br />
                                                <input type="text" name="post_grid_meta_options[meta_query][<?php echo $meta_queryIndex; ?>][args][<?php echo $argIndex; ?>][type]" value="<?php echo $arg['type']; ?>" /><br>
                                            </div>
                                            </div>
                                            <?php

                                        }
                                    endif;
                                    ?>



                                </div>
                            </div>
                        </div>


                        <?php

                    endif;

                endforeach;
            else:

            endif;
            ?>

        </div>






        <script>
            jQuery(document).ready(function($)
            {
                $( ".meta-query-list" ).sortable({revert: "invalid", handle: '.header'});

            })
        </script>
        <?php

        $html = ob_get_clean();






        $args = array(
            'id'		=> 'post_status',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post status','post-grid'),
            'details'	=> __('Display post from following post status.','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> true,
            'value'		=> $post_status,
            'default'		=> array('publish'),
            'args'		=> $class_post_grid_meta_box->get_post_status(),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'query_order',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post query order','post-grid'),
            'details'	=> __('Query order ascending or descending.','post-grid'),
            'type'		=> 'select',
            //'for'		=> $taxonomy,
            //'multiple'		=> true,
            'value'		=> $query_order,
            'default'		=> 'DESC',
            'args'		=> array(
                'ASC'=>__('Ascending','post-grid'),
                'DESC'=>__('Descending','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'query_orderby',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Post query orderby','post-grid'),
            'details'	=> __('Select post query orderby','post-grid'),
            'type'		=> 'select2',
            'multiple'		=> true,
            'value'		=> $query_orderby,
            'default'		=> array('date'),
            'args'		=> $class_post_grid_meta_box->get_query_orderby(),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'query_orderby_meta_key',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Query orderby meta key','post-grid'),
            'details'	=> __('You can use custom meta field key for orderby meta key','post-grid'),
            'type'		=> 'text',
            'value'		=> $query_orderby_meta_key,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);




        $args = array(
            'id'		=> 'posts_per_page',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Posts per page','post-grid'),
            'details'	=> __('Number of post each pagination. -1 to display all. default is 10 if you left empty.','post-grid'),
            'type'		=> 'text',
            'value'		=> $posts_per_page,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'offset',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Offset','post-grid'),
            'details'	=> __('Display posts from the n\'th, if you set Posts per page to -1 will not work offset.','post-grid'),
            'type'		=> 'text',
            'value'		=> $offset,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'exclude_post_id',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Exclude by post ID','post-grid'),
            'details'	=> __('You can exclude any post by ids here, use comma separate post id value, ex: 45,48','post-grid'),
            'type'		=> 'text',
            'value'		=> $exclude_post_id,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'keyword',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Search parameter','post-grid'),
            'details'	=> __('Query post by search keyword, please follow the reference https://codex.wordpress.org/Class_Reference/WP_Query#Search_Parameter','post-grid'),
            'type'		=> 'text',
            'value'		=> $keyword,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args, $post_id);



        ?>










    </div>

    <?php


}







add_action('post_grid_settings_tabs_content_skin_layout', 'post_grid_settings_tabs_content_skin_layout', 10, 2);

function post_grid_settings_tabs_content_skin_layout($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_functions = new class_post_grid_functions();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);
    $layout_content = !empty($post_grid_meta_options['layout']['content']) ? $post_grid_meta_options['layout']['content'] : 'OR';

    $enable_multi_skin = !empty($post_grid_meta_options['enable_multi_skin']) ? $post_grid_meta_options['enable_multi_skin'] : 'no';
    $skin = !empty($post_grid_meta_options['skin']) ? $post_grid_meta_options['skin'] : 'flat';

?>
        <div class="section">
            <div class="section-title">Slin & Layout</div>
            <p class="description section-description">Choose skin and customize layout.</p>
            <?php



            $class_post_grid_functions = new class_post_grid_functions();
            ob_start();

            ?>
            <div class="layout-list">
                <div class="idle  ">
                    <div class="name">
                        <select class="select-layout-content" name="post_grid_meta_options[layout][content]" >
                            <?php

                            $post_grid_layout_content = get_option('post_grid_layout_content');
                            if(empty($post_grid_layout_content)){

                                $layout_content_list = $class_post_grid_functions->layout_content_list();
                            }
                            else{

                                $layout_content_list = $post_grid_layout_content;

                            }





                            foreach($layout_content_list as $layout_key=>$layout_info){
                                ?>
                                <option <?php if($layout_content==$layout_key) echo 'selected'; ?>  value="<?php echo $layout_key; ?>"><?php echo $layout_key; ?></option>
                                <?php

                            }
                            ?>
                        </select>
                        <a target="_blank" class="edit-layout button" href="<?php echo admin_url().'edit.php?post_type=post_grid&page=layout_editor&layout_content='.$layout_content;?>" ><?php echo __('Edit' , 'post-grid'); ?></a>
                    </div>

                    <script>
                        jQuery(document).ready(function($)
                        {
                            $(document).on('change', '.select-layout-content', function()
                            {


                                var layout = $(this).val();

                                $('.edit-layout').attr('href', '<?php echo admin_url().'edit.php?post_type=post_grid&page=layout_editor&layout_content='; ?>'+layout);
                            })

                        })
                    </script>







                    <?php

                    if(empty($layout_content)){
                        $layout_content = 'flat-left';
                    }


                    ?>


                    <div class="layer-content">
                        <div class="<?php echo $layout_content; ?>">
                            <?php
                            $post_grid_layout_content = get_option( 'post_grid_layout_content' );

                            if(empty($post_grid_layout_content)){
                                $layout = $class_post_grid_functions->layout_content($layout_content);
                            }
                            else{
                                if(!empty($post_grid_layout_content[$layout_content])){
                                    $layout = $post_grid_layout_content[$layout_content];
                                }
                                else{
                                    $layout = array();
                                }


                            }

                            //  $layout = $class_post_grid_functions->layout_content($layout_content);

                            //var_dump($layout);

                            foreach($layout as $item_key=>$item_info){

                                $item_key = $item_info['key'];



                                ?>


                                <div class="item <?php echo $item_key; ?>" style=" <?php echo $item_info['css']; ?> ">

                                    <?php

                                    if($item_key=='thumb'){

                                        ?>
                                        <img style="width:100%; height:auto;" src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" />
                                        <?php
                                    }

                                    elseif($item_key=='title'){

                                        ?>
                                        Lorem Ipsum is simply

                                        <?php
                                    }

                                    elseif($item_key=='excerpt'){

                                        ?>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text
                                        <?php
                                    }



                                    else{

                                        echo $item_info['name'];

                                    }

                                    ?>



                                </div>
                                <?php
                            }


                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <?php


            $html = ob_get_clean();

            $args = array(
                'id'		=> 'password_query',
                'title'		=> __('Content Layout','post-grid'),
                'details'	=> 'Choose Content Layout',
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);



            $skins = $class_post_grid_functions->skins();

           // ob_start();

            ?>

            <div class="setting-field">
                <div class="field-lable">Skins</div>
                <p class="description">Select grid skins</p>
                <div class="field-input">





                </div>
            </div>



            <div class="skin-list">

                <?php
                //var_dump($skin);
                foreach($skins as $skin_slug=>$skin_info){

                    ?>
                    <div class="skin-container">


                        <?php

                        if($skin==$skin_slug){

                            $checked = 'checked';
                            $selected_skin = 'selected';
                        }
                        else{
                            $checked = '';
                            $selected_skin = '';
                        }

                        ?>
                        <div class="header <?php echo $selected_skin; ?>">
<!--                            <span class="edit-link"><a href="#">Edit</a></span>-->

                            <label><input <?php echo $checked; ?> type="radio" name="post_grid_meta_options[skin]" value="<?php echo $skin_slug; ?>" ><?php echo $skin_info['name']; ?></label>


                        </div>


                        <div class="skin <?php echo $skin_slug; ?>">


                            <?php

                            include post_grid_plugin_dir.'skins/index.php';

                            ?>
                        </div>
                    </div>
                    <?php

                }

                ?>



            </div>
            <?php

//            $html = ob_get_clean();
//
//            $args = array(
//                'id'		=> 'skins',
//                'title'		=> __('Skins','post-grid'),
//                'details'	=> 'Select grid Skins',
//                'type'		=> 'custom_html',
//                'html'		=> $html,
//
//
//            );
//
//            $settings_tabs_field->generate_field($args, $post_id);




            ?>
        </div>
    <?php

}



add_action('post_grid_settings_tabs_content_layout_settings', 'post_grid_settings_tabs_content_layout_settings', 10, 2);

function post_grid_settings_tabs_content_layout_settings($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_functions = new class_post_grid_functions();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $items_width_desktop = !empty($post_grid_meta_options['width']['desktop']) ? $post_grid_meta_options['width']['desktop'] : '280px';
    $items_width_tablet = !empty($post_grid_meta_options['width']['tablet']) ? $post_grid_meta_options['width']['tablet'] : '280px';
    $items_width_mobile = !empty($post_grid_meta_options['width']['mobile']) ? $post_grid_meta_options['width']['mobile'] : '90%';

    $items_height_style = !empty($post_grid_meta_options['item_height']['style']) ? $post_grid_meta_options['item_height']['style'] : 'auto_height';
    $items_height_style_tablet = !empty($post_grid_meta_options['item_height']['style_tablet']) ? $post_grid_meta_options['item_height']['style_tablet'] : 'auto_height';
    $items_height_style_mobile = !empty($post_grid_meta_options['item_height']['style_mobile']) ? $post_grid_meta_options['item_height']['style_mobile'] : 'auto_height';

    $items_fixed_height = !empty($post_grid_meta_options['item_height']['fixed_height']) ? $post_grid_meta_options['item_height']['fixed_height'] : '220px';
    $items_fixed_height_tablet = !empty($post_grid_meta_options['item_height']['fixed_height_tablet']) ? $post_grid_meta_options['item_height']['fixed_height_tablet'] : '220px';
    $items_fixed_height_mobile = !empty($post_grid_meta_options['item_height']['fixed_height_mobile']) ? $post_grid_meta_options['item_height']['fixed_height_mobile'] : '220px';

    $items_media_height_style = !empty($post_grid_meta_options['media_height']['style']) ? $post_grid_meta_options['media_height']['style'] : 'auto_height';
    $items_media_fixed_height = !empty($post_grid_meta_options['media_height']['fixed_height']) ? $post_grid_meta_options['media_height']['fixed_height'] : '220px';


    $lazy_load_enable = !empty($post_grid_meta_options['lazy_load_enable']) ? $post_grid_meta_options['lazy_load_enable'] : 'yes';
    $lazy_load_image_src = !empty($post_grid_meta_options['lazy_load_image_src']) ? $post_grid_meta_options['lazy_load_image_src'] : '';

    $items_bg_color_type = !empty($post_grid_meta_options['items_bg_color_type']) ? $post_grid_meta_options['items_bg_color_type'] : 'fixed';
    $items_bg_color = !empty($post_grid_meta_options['items_bg_color']) ? $post_grid_meta_options['items_bg_color'] : '#fff';

    $items_margin = !empty($post_grid_meta_options['margin']) ? $post_grid_meta_options['margin'] : '10px';
    $item_padding = !empty($post_grid_meta_options['item_padding']) ? $post_grid_meta_options['item_padding'] : '0px';

    $featured_img_size = !empty($post_grid_meta_options['featured_img_size']) ? $post_grid_meta_options['featured_img_size'] : '';
    $thumb_linked = !empty($post_grid_meta_options['thumb_linked']) ? $post_grid_meta_options['thumb_linked'] : 'no';
    $media_source = !empty($post_grid_meta_options['media_source']) ? $post_grid_meta_options['media_source'] : array();


    $container_padding = !empty($post_grid_meta_options['container']['padding']) ? $post_grid_meta_options['container']['padding'] : '10px';
    $container_bg_color = !empty($post_grid_meta_options['container']['bg_color']) ? $post_grid_meta_options['container']['bg_color'] : '';
    $container_bg_image = !empty($post_grid_meta_options['container']['bg_image']) ? $post_grid_meta_options['container']['bg_image'] : '';




    ?>
        <div class="section">
            <div class="section-title">Layout settings</div>
            <p class="description section-description">Customize the layout</p>

            <?php

            ob_start();

            ?>
            <div class="">
                Desktop:(min-width:1024px)<br>
                <input placeholder="250px or 30%" type="text" name="post_grid_meta_options[width][desktop]" value="<?php echo $items_width_desktop; ?>" />
            </div>
            <br>
            <div class="">
                Tablet:( min-width:768px )<br>
                <input placeholder="250px or 30%" type="text" name="post_grid_meta_options[width][tablet]" value="<?php echo $items_width_tablet; ?>" />
            </div>
            <br>
            <div class="">
                Mobile:( min-width : 320px, )<br>
                <input placeholder="250px or 30%" type="text" name="post_grid_meta_options[width][mobile]" value="<?php echo $items_width_mobile; ?>" />
            </div>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Grid items width','post-grid'),
                'details'	=> __('Grid item width for different device, you can use % or px, em and etc, example: 80% or 250px','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);




            ob_start();

            ?>
            <table>
                <tr>
                    <td style="padding: 0 20px 0  0">

                        <div class="">
                            <p><b>Desktop:</b>(min-width:1024px)</p>
                            <label><input <?php if($items_height_style=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style]" value="auto_height" /><?php _e('Auto height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style]" value="fixed_height" /><?php _e('Fixed height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style]" value="max_height" /><?php _e('Max height','post-grid'); ?></label><br />

                            <input type="text" name="post_grid_meta_options[item_height][fixed_height]" value="<?php echo $items_fixed_height; ?>" />

                        </div>


                    </td>
                </tr>
                <tr>
                    <td style="padding:  0 20px 0  0">
                        <div class="">
                            <p><b>Tablet:</b>( min-width:768px )</p>
                            <label><input <?php if($items_height_style_tablet=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_tablet]" value="auto_height" /><?php _e('Auto height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_tablet=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_tablet]" value="fixed_height" /><?php _e('Fixed height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_tablet=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_tablet]" value="max_height" /><?php _e('Max height','post-grid'); ?></label><br />

                            <input type="text" name="post_grid_meta_options[item_height][fixed_height_tablet]" value="<?php echo $items_fixed_height_tablet; ?>" />

                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0 20px 0  0">
                        <div class="">
                            <p><b>Mobile:</b>( min-width : 320px, )</p>
                            <label><input <?php if($items_height_style_mobile=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_mobile]" value="auto_height" /><?php _e('Auto height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_mobile=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_mobile]" value="fixed_height" /><?php _e('Fixed height','post-grid'); ?></label><br />
                            <label><input <?php if($items_height_style_mobile=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[item_height][style_mobile]" value="max_height" /><?php _e('Max height','post-grid'); ?></label><br />

                            <input type="text" name="post_grid_meta_options[item_height][fixed_height_mobile]" value="<?php echo $items_fixed_height_mobile; ?>" />

                        </div>
                    </td>
                </tr>

            </table>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Grid items height','post-grid'),
                'details'	=> __('Grid item height for different device, you can use % or px, em and etc, example: 80% or 250px','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);





            ob_start();

            ?>
            <label><input <?php if($items_media_height_style=='auto_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[media_height][style]" value="auto_height" /><?php _e('Auto height', 'post-grid'); ?></label><br />
            <label><input <?php if($items_media_height_style=='fixed_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[media_height][style]" value="fixed_height" /><?php _e('Fixed height', 'post-grid'); ?></label><br />
            <label><input <?php if($items_media_height_style=='max_height') echo 'checked'; ?> type="radio" name="post_grid_meta_options[media_height][style]" value="max_height" /><?php _e('Max height', 'post-grid'); ?></label><br />

            <div class="">

                <input type="text" name="post_grid_meta_options[media_height][fixed_height]" value="<?php echo $items_media_fixed_height; ?>" />
            </div>
            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Media height','post-grid'),
                'details'	=> __('Grid item media height for different device, you can use % or px, em and etc, example: 80% or 250px','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);








            ob_start();

            ?>
            <script>
                function lazy_load_image_src(img){
                    src =img.src;
                    document.getElementById('lazy_load_image_src').value  = src;
                }

                function clear_lazy_load_src(){
                    document.getElementById('lazy_load_image_src').value  = '';
                }
            </script>


            <label><input <?php if($lazy_load_enable=='yes') echo 'checked'; ?> type="radio" name="post_grid_meta_options[lazy_load_enable]" value="yes" /><?php _e('Yes', 'post-grid'); ?></label><br />
            <label><input <?php if($lazy_load_enable=='no') echo 'checked'; ?> type="radio" name="post_grid_meta_options[lazy_load_enable]" value="no" /><?php _e('No', 'post-grid'); ?></label><br />




            <p class="option-info"><?php _e('Gif image source:', 'post-grid'); ?></p>
            <img class="lazy_load_image" onClick="lazy_load_image_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/gif/ajax-loader-1.gif" />
            <img class="lazy_load_image" onClick="lazy_load_image_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/gif/ajax-loader-2.gif" />
            <img class="lazy_load_image" onClick="lazy_load_image_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/gif/ajax-loader-3.gif" />

            <br>

            <input type="text" id="lazy_load_image_src" class="lazy_load_image_src" name="post_grid_meta_options[lazy_load_image_src]" value="<?php echo $lazy_load_image_src; ?>" /> <div onClick="clear_lazy_load_src()" class="button clear-lazy-load-src"> <?php echo __('Clear', 'post-grid'); ?></div>


            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'lazy_load_enable',
                'title'		=> __('Lazy load','post-grid'),
                'details'	=> __('Gif animation when loading grid','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);







            ob_start();

            ?>
            Background color type:<br>
            <label><input <?php if($items_bg_color_type=='fixed') echo 'checked'; ?> type="radio" name="post_grid_meta_options[items_bg_color_type]" value="fixed" /><?php _e('Fixed', 'post-grid'); ?></label><br />

            <br>
            <?php _e('Fixed Background color:', 'post-grid'); ?> <br>
            <input type="text" class="post-grid-color" name="post_grid_meta_options[items_bg_color]" value="<?php echo $items_bg_color; ?>" />

            <?php

            $html = ob_get_clean();

            $args = array(
                'id'		=> 'items_bg_color_type',
                'title'		=> __('Grid items background color','post-grid'),
                'details'	=> __('Set custom background color for grid item wrapper.','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);




            $args = array(
                'id'		=> 'margin',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Grid items margin','post-grid'),
                'details'	=> __('Grid item wrapper margin, you can use top right bottom left style, ex: 10px 15px 10px 15px','post-grid'),
                'type'		=> 'text',
                'value'		=> $items_margin,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args, $post_id);


            $args = array(
                'id'		=> 'item_padding',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Grid items padding','post-grid'),
                'details'	=> __('Grid item wrapper padding, you can use top right bottom left style, ex: 10px 15px 10px 15px','post-grid'),
                'type'		=> 'text',
                'value'		=> $item_padding,
                'default'		=> '',
            );

            $settings_tabs_field->generate_field($args, $post_id);


            ob_start();

            ?>
            <div class="">
                Padding: <br>
                <input type="text" name="post_grid_meta_options[container][padding]" value="<?php echo $container_padding; ?>" />
            </div>
            <br>
            <div class="">
                Background color: <br>
                <input type="text" class="post-grid-color" name="post_grid_meta_options[container][bg_color]" value="<?php echo $container_bg_color; ?>" />
            </div>
            <br>
            <div class="">
                Background image: <br>
                <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/dark_embroidery.png" />
                <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/dimension.png" />
                <img class="bg_image_src" onClick="bg_img_src(this)" src="<?php echo post_grid_plugin_url; ?>assets/admin/bg/eight_horns.png" />

                <br>

                <input type="text" id="container_bg_image" class="container_bg_image" name="post_grid_meta_options[container][bg_image]" value="<?php echo $container_bg_image; ?>" /> <div onClick="clear_container_bg_image()" class="button clear-container-bg-image"><?php echo __('Clear', 'post-grid'); ?></div>

                <script>

                    function bg_img_src(img){

                        src =img.src;

                        document.getElementById('container_bg_image').value  = src;

                    }

                    function clear_container_bg_image(){

                        document.getElementById('container_bg_image').value  = '';

                    }


                </script>




            </div>
            <?php

            $html = ob_get_clean();



            $args = array(
                'id'		=> 'items_bg_color_type',
                'title'		=> __('Grid container options','post-grid'),
                'details'	=> __('Set custom background color for grid container wrapper.','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);



            $args = array(
                'id'		=> 'featured_img_size',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Featured image size','post-grid'),
                'details'	=> __('Select media image size','post-grid'),
                'type'		=> 'select',
                //'multiple'		=> true,
                'value'		=> $featured_img_size,
                'default'		=> array('date'),
                'args'		=> post_grid_image_sizes(),
            );

            $settings_tabs_field->generate_field($args, $post_id);



            $args = array(
                'id'		=> 'thumb_linked',
                'parent'		=> 'post_grid_meta_options',
                'title'		=> __('Featured image linked to post','post-grid'),
                'details'	=> __('Select if you want to link to post with featured image.','post-grid'),
                'type'		=> 'radio',
                'multiple'		=> true,
                'value'		=> $thumb_linked,
                'default'		=> 'yes',
                'args'		=> array(
                    'yes'=>__('Yes','post-grid'),
                    'no'=>__('No','post-grid'),
                ),
            );

            $settings_tabs_field->generate_field($args, $post_id);





            ob_start();


            ?>
            <?php
            if(empty($media_source)){

                $media_source = $class_post_grid_functions->media_source();
            }
            else{
                //$media_source_main = $class_post_grid_functions->media_source();
                $media_source = $media_source;

            }


            ?>

            <div class="media-source-list pg-expandable">
                <?php
                foreach($media_source as $source_key=>$source_info){
                    ?>
                    <div class="item">
                        <div class="header">
                            <span class="move pg-tooltip" title="<?php echo __('Move', 'post-grid'); ?>"><i class="fa fa-bars"></i></span>
                            <input type="hidden" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][id]" value="<?php echo $source_info['id']; ?>" />
                            <input type="hidden" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][title]" value="<?php echo $source_info['title']; ?>" />
                            <label>
                                <input <?php if(!empty($source_info['checked'])) echo 'checked'; ?> type="checkbox" name="post_grid_meta_options[media_source][<?php echo $source_info['id']; ?>][checked]" value="yes" /><?php echo $source_info['title']; ?>
                            </label>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <script>
                jQuery(document).ready(function($)
                {
                    $( ".media-source-list" ).sortable({revert: "invalid", handle: '.move'});

                })
            </script>
            <?php




            $html = ob_get_clean();

            $args = array(
                'id'		=> 'skins',
                'title'		=> __('Media source','post-grid'),
                'details'	=> __('Choose media source you want to display from.','post-grid'),
                'type'		=> 'custom_html',
                'html'		=> $html,


            );

            $settings_tabs_field->generate_field($args, $post_id);



            ?>



        </div>

    <?php

}

add_action('post_grid_settings_tabs_content_slider', 'post_grid_settings_tabs_content_slider', 10, 2);

function post_grid_settings_tabs_content_slider($tab, $post_id)
{

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_functions = new class_post_grid_functions();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $slider_navs = !empty($post_grid_meta_options['slider_navs']) ? $post_grid_meta_options['slider_navs'] : 'true';
    $slider_navs_position = !empty($post_grid_meta_options['slider_navs_position']) ? $post_grid_meta_options['slider_navs_position'] : 'top-left';
    $slider_navs_style = !empty($post_grid_meta_options['slider_navs_style']) ? $post_grid_meta_options['slider_navs_style'] : 'round';

    $slider_dots = !empty($post_grid_meta_options['slider_dots']) ? $post_grid_meta_options['slider_dots'] : 'true';
    $slider_dots_style = !empty($post_grid_meta_options['slider_dots_style']) ? $post_grid_meta_options['slider_dots_style'] : 'round';

    $slider_auto_play = !empty($post_grid_meta_options['slider_auto_play']) ? $post_grid_meta_options['slider_auto_play'] : 'true';
    $slider_auto_play_timeout = !empty($post_grid_meta_options['slider_auto_play_timeout']) ? $post_grid_meta_options['slider_auto_play_timeout'] : '2000';
    $slider_auto_play_speed = !empty($post_grid_meta_options['slider_auto_play_speed']) ? $post_grid_meta_options['slider_auto_play_speed'] : '3000';

    $slider_rewind = !empty($post_grid_meta_options['slider_rewind']) ? $post_grid_meta_options['slider_rewind'] : 'false';
    $slider_loop = !empty($post_grid_meta_options['slider_loop']) ? $post_grid_meta_options['slider_loop'] : 'false';
    $slider_center = !empty($post_grid_meta_options['slider_center']) ? $post_grid_meta_options['slider_center'] : 'false';
    $slider_autoplayHoverPause = !empty($post_grid_meta_options['slider_autoplayHoverPause']) ? $post_grid_meta_options['slider_autoplayHoverPause'] : 'true';
    $slider_navSpeed = !empty($post_grid_meta_options['slider_navSpeed']) ? $post_grid_meta_options['slider_navSpeed'] : '2000';
    $slider_dotsSpeed = !empty($post_grid_meta_options['slider_dotsSpeed']) ? $post_grid_meta_options['slider_dotsSpeed'] : '3000';
    $slider_touchDrag = !empty($post_grid_meta_options['slider_touchDrag']) ? $post_grid_meta_options['slider_touchDrag'] : 'true';
    $slider_mouseDrag = !empty($post_grid_meta_options['slider_mouseDrag']) ? $post_grid_meta_options['slider_mouseDrag'] : 'true';

    $slider_column_desktop = !empty($post_grid_meta_options['slider_column_desktop']) ? $post_grid_meta_options['slider_column_desktop'] : '4';
    $slider_column_tablet = !empty($post_grid_meta_options['slider_column_tablet']) ? $post_grid_meta_options['slider_column_tablet'] : '2';
    $slider_column_mobile = !empty($post_grid_meta_options['slider_column_mobile']) ? $post_grid_meta_options['slider_column_mobile'] : '1';




    ?>
    <div class="section">
        <div class="section-title">Carousel slider settings</div>
        <p class="description section-description">Customize the carousel settings</p>

        <?php


        ob_start();

        ?>
        <div class="">
            <?php echo __('Desktop:(min-width:1024px)', 'post-grid'); ?><br>
            <input type="text" name="post_grid_meta_options[slider_column_desktop]" value="<?php echo $slider_column_desktop; ?>" />
        </div>
        <br>
        <div class="">
            <?php echo __('Tablet:( min-width:768px )', 'post-grid'); ?><br>
            <input type="text" name="post_grid_meta_options[slider_column_tablet]" value="<?php echo $slider_column_tablet; ?>" />
        </div>
        <br>
        <div class="">
            <?php echo __(' Mobile:( min-width : 320px )', 'post-grid'); ?><br>
            <input type="text" name="post_grid_meta_options[slider_column_mobile]" value="<?php echo $slider_column_mobile; ?>" />
        </div>
        <?php

        $html = ob_get_clean();

        $args = array(
            'id'		=> 'slider_column',
            'title'		=> __('Slider column number','post-grid'),
            'details'	=> __('Set custom number of column count for different devices','post-grid'),
            'type'		=> 'custom_html',
            'html'		=> $html,


        );

        $settings_tabs_field->generate_field($args, $post_id);






        $args = array(
            'id'		=> 'slider_navs',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Display slider navs','post-grid'),
            'details'	=> __('Display or hide slider navigation.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_navs,
            'default'		=> 'true',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'slider_navs_position',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider navs position','post-grid'),
            'details'	=> __('Set position you want to display navs.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_navs_position,
            'default'		=> 'top-left',
            'args'		=> array(
                'top-left'=>__('Top Left','post-grid'),
                'top-right'=>__('Top Right','post-grid'),
                'middle'=>__('Middle','post-grid'),
                'bottom-left'=>__('Bottom Left','post-grid'),
                'bottom-right'=>__('Bottom Right','post-grid'),

            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'slider_navs_style',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider navs style','post-grid'),
            'details'	=> __('Set style you want to display navs.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_navs_style,
            'default'		=> 'round',
            'args'		=> array(
                'round'=>__('Round','post-grid'),
                'round-border'=>__('Round border','post-grid'),
                'semi-round'=>__('Semi round','post-grid'),
                'square'=>__('Square','post-grid'),
                'square-border'=>__('Square border','post-grid'),
                'square-shadow'=>__('Square shadow','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_dots',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Display slider dots','post-grid'),
            'details'	=> __('Display or hide slider dots.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_dots,
            'default'		=> 'true',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'slider_dots_style',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider dots style','post-grid'),
            'details'	=> __('Set style you want to display dots.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_dots_style,
            'default'		=> 'round',
            'args'		=> array(
                'round'=>__('Round','post-grid'),
                'round-border'=>__('Round border','post-grid'),
                'semi-round'=>__('Semi round','post-grid'),
                'square'=>__('Square','post-grid'),
                'square-border'=>__('Square border','post-grid'),
                'square-shadow'=>__('Square shadow','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'slider_auto_play',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider auto play','post-grid'),
            'details'	=> __('Enable or disable slider auto play.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_auto_play,
            'default'		=> 'true',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'slider_auto_play_timeout',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slide auto play timeout','post-grid'),
            'details'	=> __('Set custom value for slide auto play timeout, ex: 2000, 1000 = 1 second','post-grid'),
            'type'		=> 'text',
            'value'		=> $slider_auto_play_timeout,
            'default'		=> '2000',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_auto_play_speed',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slide auto play speed','post-grid'),
            'details'	=> __('Set custom value for slide auto play speed, ex: 2000, 1000 = 1 second','post-grid'),
            'type'		=> 'text',
            'value'		=> $slider_auto_play_speed,
            'default'		=> '3000',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'slider_navSpeed',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slide speed','post-grid'),
            'details'	=> __('Set custom value for slide speed, ex: 2000, 1000 = 1 second','post-grid'),
            'type'		=> 'text',
            'value'		=> $slider_navSpeed,
            'default'		=> '3000',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'slider_dotsSpeed',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Pagination slide speed','post-grid'),
            'details'	=> __('Set custom value for pagination/dots slide speed, ex: 2000, 1000 = 1 second','post-grid'),
            'type'		=> 'text',
            'value'		=> $slider_dotsSpeed,
            'default'		=> '3000',
        );

        $settings_tabs_field->generate_field($args, $post_id);






        $args = array(
            'id'		=> 'slider_rewind',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider rewind','post-grid'),
            'details'	=> __('Enable or disable slider rewind.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_rewind,
            'default'		=> 'false',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_loop',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider loop','post-grid'),
            'details'	=> __('Enable or disable slider loop.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_loop,
            'default'		=> 'false',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_center',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider center','post-grid'),
            'details'	=> __('Enable or disable slider center, you will need to set column count odd number to enable this.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_center,
            'default'		=> 'false',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_autoplayHoverPause',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider stop on Hover','post-grid'),
            'details'	=> __('Enable or disable slider stop on hover','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_autoplayHoverPause,
            'default'		=> 'true',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_touchDrag',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider touch drag enable','post-grid'),
            'details'	=> __('Enable or disable slider touch drag','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_touchDrag,
            'default'		=> 'true',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'slider_mouseDrag',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Slider mouse drag enable','post-grid'),
            'details'	=> __('Enable or disable slider mouse drag','post-grid'),
            'type'		=> 'radio',
            'value'		=> $slider_mouseDrag,
            'default'		=> 'true',
            'args'		=> array(
                'true'=>__('Yes','post-grid'),
                'false'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);









        ?>

    </div>
    <?php


}



add_action('post_grid_settings_tabs_content_filterable', 'post_grid_settings_tabs_content_filterable', 10, 2);

function post_grid_settings_tabs_content_filterable($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_post_grid_functions = new class_post_grid_functions();

    //var_dump($class_post_grid_meta_box->get_query_orderby());

    $post_grid_posttypes_array = post_grid_posttypes_array();
    //$post_grid_categories_array = post_grid_categories_array($post_id);

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $taxonomies = !empty($post_grid_meta_options['taxonomies']) ? $post_grid_meta_options['taxonomies'] : array();

    $nav_top_filter = !empty($post_grid_meta_options['nav_top']['filter']) ? $post_grid_meta_options['nav_top']['filter'] : 'yes';
    $nav_top_filter_style = !empty($post_grid_meta_options['nav_top']['filter_style']) ? $post_grid_meta_options['nav_top']['filter_style'] : 'inline';
    $filterable_post_per_page = !empty($post_grid_meta_options['nav_top']['filterable_post_per_page']) ? $post_grid_meta_options['nav_top']['filterable_post_per_page'] : '6';
    $filterable_font_size = !empty($post_grid_meta_options['nav_top']['filterable_font_size']) ? $post_grid_meta_options['nav_top']['filterable_font_size'] : '14px';
    $filterable_navs_margin = !empty($post_grid_meta_options['nav_top']['filterable_navs_margin']) ? $post_grid_meta_options['nav_top']['filterable_navs_margin'] : '5px';

    $filterable_font_color = !empty($post_grid_meta_options['nav_top']['filterable_font_color']) ? $post_grid_meta_options['nav_top']['filterable_font_color'] : '#999';
    $filterable_bg_color = !empty($post_grid_meta_options['nav_top']['filterable_bg_color']) ? $post_grid_meta_options['nav_top']['filterable_bg_color'] : '#fff';
    $filterable_active_bg_color = !empty($post_grid_meta_options['nav_top']['filterable_active_bg_color']) ? $post_grid_meta_options['nav_top']['filterable_active_bg_color'] : '#ddd';
    $filter_all_text = !empty($post_grid_meta_options['nav_top']['filter_all_text']) ? $post_grid_meta_options['nav_top']['filter_all_text'] : 'All';
    $active_filter = !empty($post_grid_meta_options['nav_top']['active_filter']) ? $post_grid_meta_options['nav_top']['active_filter'] : '';



    $categories = !empty($post_grid_meta_options['categories']) ? $post_grid_meta_options['categories'] : array();








    ?>
    <div class="section">
        <div class="section-title">Filterable grid settings</div>
        <p class="description section-description">Customize the filterable settings</p>
        <?php


        $args = array(
            'id'		=> 'filter',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Filterable menu display','post-grid'),
            'details'	=> __('Hide or display filterable menu.','post-grid'),
            'type'		=> 'radio',
            'multiple'		=> true,
            'value'		=> $nav_top_filter,
            'default'		=> 'yes',
            'args'		=> array(
                'yes'=>__('Yes','post-grid'),
                'no'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'filter_style',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Filterable menu style','post-grid'),
            'details'	=> __('Display inline or dropdown style filterable menu.','post-grid'),
            'type'		=> 'radio',
            'multiple'		=> true,
            'value'		=> $nav_top_filter_style,
            'default'		=> 'inline',
            'args'		=> array(
                'inline'=>__('Inline','post-grid'),
                'dropdown'=>__('Dropdown','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'filterable_post_per_page',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Number of items per page','post-grid'),
            'details'	=> __('Set custom value post per page for filterable.','post-grid'),
            'type'		=> 'text',
            'value'		=> $filterable_post_per_page,
            'default'		=> '6',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'filterable_font_size',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Navs font size','post-grid'),
            'details'	=> __('Set custom value filterable nav item font size.','post-grid'),
            'type'		=> 'text',
            'value'		=> $filterable_font_size,
            'default'		=> '14px',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'filterable_navs_margin',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Navs margin','post-grid'),
            'details'	=> __('Set custom value filterable nav item margin. ex: 5px or 5px 10px','post-grid'),
            'type'		=> 'text',
            'value'		=> $filterable_navs_margin,
            'default'		=> '5px',
        );



        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'filterable_font_color',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Navs font color','post-grid'),
            'details'	=> __('Set custom value filterable nav item font color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $filterable_font_color,
            'default'		=> '#999',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'filterable_bg_color',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Navs background color','post-grid'),
            'details'	=> __('Set custom value filterable nav item background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $filterable_bg_color,
            'default'		=> '#fff',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'filterable_active_bg_color',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Navs active background color','post-grid'),
            'details'	=> __('Set custom value filterable nav item active background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $filterable_active_bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'filter_all_text',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Custom text for All','post-grid'),
            'details'	=> __('Set custom text for default all text.','post-grid'),
            'type'		=> 'text',
            'value'		=> $filter_all_text,
            'default'		=> 'All',
        );

        $settings_tabs_field->generate_field($args, $post_id);




        ob_start();


    if(!empty($taxonomies)){
        foreach($taxonomies as $taxonomy => $taxonomyData){
            $terms = !empty($taxonomyData['terms']) ? $taxonomyData['terms'] : array();
            $terms_relation = !empty($taxonomyData['terms_relation']) ? $taxonomyData['terms_relation'] : 'OR';
            $checked = !empty($taxonomyData['checked']) ? $taxonomyData['checked'] : '';
            if(!empty($terms) && !empty($checked)){
                $terms_ids[$taxonomy] = $terms;
            }
        }
    }

        //var_dump($terms_ids);
        ?>

        <div class="active-filter-container">
            <select class="" name="post_grid_meta_options[nav_top][active_filter]">
                <?php

                echo '<option  value="all">'.__('All', 'post-grid').'</option>';


                if(!empty($terms_ids))
                foreach($terms_ids as $taxonomy=>$ids){
                    foreach($ids as $index=>$term_id){

                    //$tax_terms = explode(',',$tax_terms);

                    //$terms_info = get_term_by('id', $term_id, $taxonomy);
                    $terms_info = get_term( $term_id, $taxonomy );
                    //var_dump($terms_info);

                    if($active_filter ==$terms_info->slug ) {
                        $selected = 'selected';

                    }
                    else{
                        $selected = '';
                    }

                    echo '<option '.$selected.'  value="'.$terms_info->slug.'">'.$terms_info->name.'</option>';
                    }
                }





















                ?>
            </select>


        </div>
        <?php


        $html = ob_get_clean();

        $args = array(
            'id'		=> 'active_filter',
            'title'		=> __('Default active filter for filterable grid','post-grid'),
            'details'	=> __('Set custom number of column count for different devices','post-grid'),
            'type'		=> 'custom_html',
            'html'		=> $html,


        );

        $settings_tabs_field->generate_field($args, $post_id);












        ?>
    </div>
    <?php
}

add_action('post_grid_settings_tabs_content_grid', 'post_grid_settings_tabs_content_grid', 10, 2);

function post_grid_settings_tabs_content_grid($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $grid_layout_name = !empty($post_grid_meta_options['grid_layout']['name']) ? $post_grid_meta_options['grid_layout']['name'] : 'layout_grid';
    $grid_layout_col_multi = !empty($post_grid_meta_options['grid_layout']['col_multi']) ? $post_grid_meta_options['grid_layout']['col_multi'] : '2';

    ?>
    <div class="section">
        <div class="section-title">Grid Settings</div>
        <p class="description section-description">Customize the Grid.</p>


        <?php
        ob_start();

        ?>

        <label>
            <input  type="radio" <?php if($grid_layout_name=='layout_grid') echo 'checked' ?> name="post_grid_meta_options[grid_layout][name]" value="layout_grid"><img title="N - N" src="<?php echo post_grid_plugin_url; ?>assets/admin/images/layout_grid.png" />
        </label>













        <?php

        $html = ob_get_clean();

        $args = array(
            'id'		=> 'grid_layout',
            'title'		=> __('Grid layout','post-grid'),
            'details'	=> __('Choose grid layout','post-grid'),
            'type'		=> 'custom_html',
            'html'		=> $html,


        );

        $settings_tabs_field->generate_field($args, $post_id);

        ?>


    </div>

    <?php

}


add_action('post_grid_settings_tabs_content_mixitup', 'post_grid_settings_tabs_content_mixitup', 10, 2);

function post_grid_settings_tabs_content_mixitup($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();


    ?>
    <div class="section">
        <div class="section-title">Mixitup Settings</div>
        <p class="description section-description">Customize the mixitup.</p>


        <div class="setting-field">
            <div class="field-lable"></div>
            <p class="description">Will coming soon</p>
            <div class="field-input">





            </div>
        </div>
    </div>

    <?php

}


add_action('post_grid_settings_tabs_content_pagination', 'post_grid_settings_tabs_content_pagination', 10, 2);

function post_grid_settings_tabs_content_pagination($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $pagination_type = !empty($post_grid_meta_options['nav_bottom']['pagination_type']) ? $post_grid_meta_options['nav_bottom']['pagination_type'] : 'normal';
    $max_num_pages = !empty($post_grid_meta_options['pagination']['max_num_pages']) ? $post_grid_meta_options['pagination']['max_num_pages'] : '0';
    $prev_text = !empty($post_grid_meta_options['pagination']['prev_text']) ? $post_grid_meta_options['pagination']['prev_text'] : __('« Previous','post-grid');
    $next_text = !empty($post_grid_meta_options['pagination']['next_text']) ? $post_grid_meta_options['pagination']['next_text'] : __('Next »','post-grid');
    $font_size = !empty($post_grid_meta_options['pagination']['font_size']) ? $post_grid_meta_options['pagination']['font_size'] : '16px';
    $font_color = !empty($post_grid_meta_options['pagination']['font_color']) ? $post_grid_meta_options['pagination']['font_color'] : '#fff';
    $bg_color = !empty($post_grid_meta_options['pagination']['bg_color']) ? $post_grid_meta_options['pagination']['bg_color'] : '#646464';
    $active_bg_color = !empty($post_grid_meta_options['pagination']['active_bg_color']) ? $post_grid_meta_options['pagination']['active_bg_color'] : '#4b4b4b';




    ?>
    <div class="section">
        <div class="section-title">Pagination Settings</div>
        <p class="description section-description">Customize the pagination.</p>

        <?php
        $args = array(
            'id'		=> 'pagination_type',
            'parent'		=> 'post_grid_meta_options[nav_bottom]',
            'title'		=> __('Pagination type','post-grid'),
            'details'	=> __('Select pagination you want to display.','post-grid'),
            'type'		=> 'radio',
            'multiple'		=> true,
            'value'		=> $pagination_type,
            'default'		=> 'inline',
            'args'		=> array(
                'none'=>__('None','post-grid'),
                'normal'=>__('Normal Pagination','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'max_num_pages',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Max number of pagination','post-grid'),
            'details'	=> __('keep 0 (zero) for auto','post-grid'),
            'type'		=> 'text',
            'value'		=> $max_num_pages,
            'default'		=> '0',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'prev_text',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination Previous text','post-grid'),
            'details'	=> __('Custom text for previous page','post-grid'),
            'type'		=> 'text',
            'value'		=> $prev_text,
            'default'		=> '0',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'next_text',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination Next text','post-grid'),
            'details'	=> __('Custom text for next page','post-grid'),
            'type'		=> 'text',
            'value'		=> $next_text,
            'default'		=> '0',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'font_size',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination font size','post-grid'),
            'details'	=> __('Custom font size for pagination','post-grid'),
            'type'		=> 'text',
            'value'		=> $font_size,
            'default'		=> '16px',
        );

        $settings_tabs_field->generate_field($args, $post_id);




        $args = array(
            'id'		=> 'font_color',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination font color','post-grid'),
            'details'	=> __('Set custom color for pagination text.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $font_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'bg_color',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination default background color','post-grid'),
            'details'	=> __('Set custom value for pagination background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'active_bg_color',
            'parent'		=> 'post_grid_meta_options[pagination]',
            'title'		=> __('Pagination active/hover background color','post-grid'),
            'details'	=> __('Set custom value filterable pagination item active background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $active_bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);





        ?>



    </div>

    <?php

}




add_action('post_grid_settings_tabs_content_glossary', 'post_grid_settings_tabs_content_glossary', 10, 2);

function post_grid_settings_tabs_content_glossary($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    //$glossary_load_type = !empty($post_grid_meta_options['glossary']['load_type']) ? $post_grid_meta_options['glossary']['load_type'] : 'refresh';

    ?>
    <div class="section">
        <div class="section-title">Glossary Settings</div>
        <p class="description section-description">Customize the glossary.</p>


        <?php

//        $args = array(
//            'id'		=> 'load_type',
//            'parent'		=> 'post_grid_meta_options[glossary]',
//            'title'		=> __('Glossary load type','post-grid'),
//            'details'	=> __('Select how you want to load post item.','post-grid'),
//            'type'		=> 'radio',
//            'multiple'		=> true,
//            'value'		=> $glossary_load_type,
//            'default'		=> 'normal',
//            'args'		=> array(
//                'refresh'=>__('Refresh','post-grid'),
//                'filterable'=>__('Filterable','post-grid'),
//
//            ),
//        );
//
//        $settings_tabs_field->generate_field($args, $post_id);

        ?>

        <div class="setting-field">
            <div class="field-lable"></div>
            <p class="description">Please use "Filterable" & "Pagination" nav for styling glossary navs</p>
            <div class="field-input">





            </div>
        </div>
    </div>

    <?php

}


add_action('post_grid_settings_tabs_content_timeline', 'post_grid_settings_tabs_content_timeline', 10, 2);

function post_grid_settings_tabs_content_timeline($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $timeline_arrow_bg_color = !empty($post_grid_meta_options['timeline']['arrow_bg_color']) ? $post_grid_meta_options['timeline']['arrow_bg_color'] : '#eee';
    $timeline_arrow_size = !empty($post_grid_meta_options['timeline']['arrow_size']) ? $post_grid_meta_options['timeline']['arrow_size'] : '13px';
    $timeline_bg_color = !empty($post_grid_meta_options['timeline']['timeline_bg_color']) ? $post_grid_meta_options['timeline']['timeline_bg_color'] : '#eee';


    $timeline_bubble_bg_color = !empty($post_grid_meta_options['timeline']['bubble_bg_color']) ? $post_grid_meta_options['timeline']['bubble_bg_color'] : '#ddd';
    $timeline_bubble_border_color = !empty($post_grid_meta_options['timeline']['bubble_border_color']) ? $post_grid_meta_options['timeline']['bubble_border_color'] : '#fff';




    ?>
    <div class="section">
    <div class="section-title">Timeline Settings</div>
    <p class="description section-description">Customize the timeline.</p>



        <?php

        $args = array(
            'id'		=> 'timeline_bg_color',
            'parent'		=> 'post_grid_meta_options[timeline]',
            'title'		=> __('Timeline color','post-grid'),
            'details'	=> __('Choose timeline color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $timeline_bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'arrow_bg_color',
            'parent'		=> 'post_grid_meta_options[timeline]',
            'title'		=> __('Timeline arrow background color','post-grid'),
            'details'	=> __('Choose timeline arrow background color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $timeline_arrow_bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'arrow_size',
            'parent'		=> 'post_grid_meta_options[timeline]',
            'title'		=> __('Timeline arrow size','post-grid'),
            'details'	=> __('Custom arrow size for arrow','post-grid'),
            'type'		=> 'text',
            'value'		=> $timeline_arrow_size,
            'default'		=> '13px',
        );

        $settings_tabs_field->generate_field($args, $post_id);


        $args = array(
            'id'		=> 'bubble_bg_color',
            'parent'		=> 'post_grid_meta_options[timeline]',
            'title'		=> __('Timeline bubble color','post-grid'),
            'details'	=> __('Choose timeline bubble color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $timeline_bubble_bg_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);

        $args = array(
            'id'		=> 'bubble_border_color',
            'parent'		=> 'post_grid_meta_options[timeline]',
            'title'		=> __('Timeline bubble border color','post-grid'),
            'details'	=> __('Choose timeline bubble border color.','post-grid'),
            'type'		=> 'colorpicker',
            'value'		=> $timeline_bubble_border_color,
            'default'		=> '#ddd',
        );

        $settings_tabs_field->generate_field($args, $post_id);



        ?>

    </div>

    <?php

}


add_action('post_grid_settings_tabs_content_search', 'post_grid_settings_tabs_content_search', 10, 2);

function post_grid_settings_tabs_content_search($tab, $post_id){


    $settings_tabs_field = new settings_tabs_field();
    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $nav_top_search = !empty($post_grid_meta_options['nav_top']['search']) ? $post_grid_meta_options['nav_top']['search'] : 'no';
    $nav_top_search_placeholder = !empty($post_grid_meta_options['nav_top']['search_placeholder']) ? $post_grid_meta_options['nav_top']['search_placeholder'] : __('Start typing', 'post-grid');
    $nav_top_search_icon = !empty($post_grid_meta_options['nav_top']['search_icon']) ? $post_grid_meta_options['nav_top']['search_icon'] : '<i class="fas fa-search"></i>';

    ?>
    <div class="section">
        <div class="section-title">Search Settings</div>
        <p class="description section-description">Choose option for search.</p>

        <?php

        $args = array(
            'id'		=> 'search',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Display search input','post-grid'),
            'details'	=> __('Display or hide search input field at top.','post-grid'),
            'type'		=> 'radio',
            'value'		=> $nav_top_search,
            'default'		=> 'no',
            'args'		=> array(
                'yes'=>__('Yes','post-grid'),
                'no'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'search_placeholder',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Search input placeholder text','post-grid'),
            'details'	=> __('Custom text for search input field','post-grid'),
            'type'		=> 'text',
            'value'		=> $nav_top_search_placeholder,
            'default'		=> __('Start typing', 'post-grid'),
        );

        $settings_tabs_field->generate_field($args, $post_id);



        $args = array(
            'id'		=> 'search_icon',
            'parent'		=> 'post_grid_meta_options[nav_top]',
            'title'		=> __('Search icon','post-grid'),
            'details'	=> __('Custom icon for search input field, you can use fontawesome icons.','post-grid'),
            'type'		=> 'text',
            'value'		=> $nav_top_search_icon,
            'default'		=> '<i class="fas fa-search"></i>',
        );

        $settings_tabs_field->generate_field($args, $post_id);












        ?>

    </div>

    <?php

}


add_action('post_grid_settings_tabs_content_masonry', 'post_grid_settings_tabs_content_masonry', 10, 2);

function post_grid_settings_tabs_content_masonry($tab, $post_id){

    $settings_tabs_field = new settings_tabs_field();

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $masonry_enable = !empty($post_grid_meta_options['masonry_enable']) ? $post_grid_meta_options['masonry_enable'] : 'yes';

    ?>
    <div class="section">
        <div class="section-title">Masonry Settings</div>
        <p class="description section-description">Customize the masonry.</p>



        <?php
        $args = array(
            'id'		=> 'masonry_enable',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Masonry enable','post-grid'),
            'details'	=> __('Enable or disable masonry style grid.','post-grid'),
            'type'		=> 'radio',
            'multiple'		=> true,
            'value'		=> $masonry_enable,
            'default'		=> 'inline',
            'args'		=> array(
                'yes'=>__('Yes','post-grid'),
                'no'=>__('No','post-grid'),
            ),
        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>


    </div>

    <?php

}















add_action('post_grid_settings_tabs_content_custom_scripts', 'post_grid_settings_tabs_content_custom_scripts', 10, 2);

function post_grid_settings_tabs_content_custom_scripts($tab, $post_id){


    $settings_tabs_field = new settings_tabs_field();

    $post_grid_meta_options = get_post_meta($post_id, 'post_grid_meta_options', true);

    $custom_js = !empty($post_grid_meta_options['custom_js']) ? $post_grid_meta_options['custom_js'] : '';
    $custom_css = !empty($post_grid_meta_options['custom_css']) ? $post_grid_meta_options['custom_css'] : '';

    ?>
    <div class="section">
        <div class="section-title">Custom Scripts & CSS</div>
        <p class="description section-description">Track where user click on accordion.</p>





        <?php
        $args = array(
            'id'		=> 'custom_js',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Custom Js.','post-grid'),
            'details'	=> __('You can add custom scripts here, do not use <code>&lt;script&gt; &lt;/script&gt;</code> tag','post-grid'),
            'type'		=> 'scripts_js',
            'default'		=> '',
            'value'		=> $custom_js,

        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>

        <?php
        $args = array(
            'id'		=> 'custom_css',
            'parent'		=> 'post_grid_meta_options',
            'title'		=> __('Custom CSS.','post-grid'),
            'details'	=> __('You can add custom css here, do not use <code>  &lt;style&gt; &lt;/style&gt;</code> tag','post-grid'),
            'type'		=> 'scripts_css',
            'value'		=> $custom_css,
            'default'		=> '',

        );

        $settings_tabs_field->generate_field($args, $post_id);
        ?>

    </div>
    <?php


}
