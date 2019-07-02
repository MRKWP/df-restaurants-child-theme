<?php

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function theme_enqueue_styles()
{
    wp_enqueue_style('divi', get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'df-rest-fonts',
        'https://fonts.googleapis.com/css?family=Cabin|Herr+Von+Muellerhoff|Source+Sans+Pro'
    );
}

if (is_admin()) {
    include_once get_stylesheet_directory() . '/class.mrkwp.plugin.dependency.php';
    $dependency_checker = new MRKWP_Plugin_Dependency();

    $plugins = [
        [
            'name'     => 'One Click Demo Import',
            'slug'     => 'one-click-demo-import/one-click-demo-import.php',
            'url'      => 'https://wordpress.org/plugins/one-click-demo-import/',
            'trial' => false,
        ],
        [
            'name'     => 'Extra Icons Plugin for Divi',
            'slug'     => 'mrkwp-extra-icons-divi/mrkwp-extra-icons-divi.php',
            'url'      => 'https://wordpress.org/plugins/mrkwp-extra-icons-divi',
            'trial' => false,
        ],
        [
            'name'     => 'FAQ Plugin',
            'slug'     => 'divi-framework-faq-premium/divi-framework-faq.php',
            'url'      => 'https://www.mrkwp.com/wp/faq-plugin/',
            'trial' => true,
        ],
        [
            'name'     => 'Testimonials for Divi',
            'slug'     => 'df-testimonials-premium/df-testimonials.php',
            'url'      => 'https://www.mrkwp.com/wp/testimonials-plugin/',
            'trial' => true,
        ],
        [
            'name'     => 'Footer Plugin for Divi',
            'slug'     => 'mrkwp-footer-for-divi/mrkwp-footer-for-divi.php',
            'url'      => 'https://wordpress.org/plugins/mrkwp-footer-for-divi/',
            'trial' => false,
        ],
        [
            'name'     => 'OpenTable Module',
            'slug'     => 'df-opentable-widget/df-opentable-widget.php',
            'url'   => 'https://github.com/MRKWP/df-opentable-widget',
            'trial' => false,
        ],
        [
            'name'     => 'Restaurant Menu for Divi',
            'slug'     => 'df-menu-items-premium/df-menu-items.php',
            'url'   => 'https://www.mrkwp.com/wp/restaurant-menu-plugin/',
            'trial' => true,
        ],
    ];

    foreach ($plugins as $plugin) {
        if (!$dependency_checker->is_plugin_active($plugin['slug'])) {
            $message = sprintf(
                'Plugin `%s` needs to be installed and activated. Get the plugin from <a target="_blank" href="%s">%s</a>',
                $plugin['name'],
                $plugin['url'],
                $plugin['url']
            );

            if ($plugin['trial']) {
                $message .= ". This plugin has a 7 day free trial!";
            }

            $dependency_checker->add_notification($message);
        }
    }
}

function ocdi_import_files()
{
    return [
        [
            'import_file_name'           => 'Restaurant Child Theme',
            'categories'                 => ['Restaurant Child Theme'],
            'import_file_url'            => get_stylesheet_directory_uri() . '/data/content.xml',
            'import_widget_file_url'     => get_stylesheet_directory_uri() . '/data/widgets.wie',
            'import_customizer_file_url' => get_stylesheet_directory_uri() . '/data/customizer.dat',
            'import_notice'              => __('Please wait for a few minutes. Do not close the window or refresh the page until the data is imported.', 'your_theme_name'),
        ],
    ];
}

add_filter('pt-ocdi/import_files', 'ocdi_import_files');

// Reset the standard WordPress widgets
function ocdi_before_widgets_import($selected_import)
{
    if (!get_option('acme_cleared_widgets')) {
        update_option('sidebars_widgets', []);
        update_option('acme_cleared_widgets', true);
    }
}

add_action('pt-ocdi/before_widgets_import', 'ocdi_before_widgets_import');

function ocdi_after_import_setup()
{
    $main_menu      = get_term_by('name', 'Main Menu', 'nav_menu');
    $secondary_menu = get_term_by('name', 'Secondary Menu', 'nav_menu');

    set_theme_mod(
        'nav_menu_locations',
        [
            'primary-menu'   => $main_menu->term_id,
            'secondary-menu' => $secondary_menu->term_id,
        ]
    );

    // Assign home page and posts page (blog page).
    $front_page_id = get_page_by_title('Home');
    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_page_id->ID);

    $et_divi = json_decode('{"static_css_custom_css_safety_check_done":true,"2_5_flush_rewrite_rules":"done","3_0_flush_rewrite_rules_2":"done","divi_email_provider_credentials_migrated":true,"divi_2_4_documentation_message":"triggered","divi_1_3_images":"checked","et_pb_static_css_file":"off","et_pb_css_in_footer":"off","et_fb_pref_settings_bar_location":"bottom","et_fb_pref_modal_snap_location":"left","et_fb_pref_modal_snap":"true","et_fb_pref_modal_fullscreen":"false","et_fb_pref_modal_dimension_width":400,"et_fb_pref_modal_dimension_height":400,"et_fb_pref_modal_position_x":0,"et_fb_pref_modal_position_y":32,"footer_bg":"#262526","bottom_bar_background_color":"#262526","header_style":"left","fixed_primary_nav_bg":"#262526","menu_height":100,"logo_height":80,"primary_nav_bg":"#262526","menu_link":"#ffffff","show_header_social_icons":true,"show_search_icon":false,"phone_number":"+61 02 8000 1234","header_email":"hello@seafood-theme.com","fixed_secondary_nav_bg":"#ffffff","fixed_secondary_menu_link":"#9a9998","menu_link_active":"#c59d5f","primary_nav_dropdown_line_color":"#c59d5f","fixed_menu_link":"#ffffff","fixed_menu_link_active":"#c59d5f","secondary_nav_dropdown_bg":"#ffffff","secondary_nav_bg":"#ffffff","secondary_nav_text_color_new":"#9a9998","secondary_nav_dropdown_link_color":"#9a9998","link_color":"#c59d5f","bottom_bar_social_icon_color":"#c59d5f","hide_fixed_logo":false,"minimized_menu_height":80,"all_buttons_bg_color":"#c59d5f","all_buttons_border_width":2,"all_buttons_font_size":16,"all_buttons_border_color":"#c59d5f","all_buttons_spacing":1,"all_buttons_font_style":"bold|uppercase","all_buttons_icon":"no","all_buttons_bg_color_hover":"rgba(197,157,95,0.8)","all_buttons_spacing_hover":1,"primary_nav_dropdown_bg":"#262526","all_buttons_border_color_hover":"#c59d5f","all_buttons_border_radius_hover":50,"all_buttons_border_radius":50,"use_sidebar_width":true,"accent_color":"#c59d5f","primary_nav_font_style":"bold|uppercase","bottom_bar_social_icon_size":14,"custom_footer_credits":"Designed by <a href=\"http:\/\/www.diviframework.com\" title=\"Premium Divi Child themes and Plugins\" class=\"customize-unpreviewable\">Divi Framework<\/a> | Powered by <a href=\"http:\/\/www.wordpress.org\" class=\"customize-unpreviewable\">WordPress<\/a>","post_meta_font_size":"14","post_meta_height":"1","post_meta_spacing":"0","post_meta_style":"","post_header_font_size":"30","post_header_height":"1","post_header_spacing":"0","post_header_style":"","boxed_layout":"","content_width":"1080","gutter_width":"3","sidebar_width":"21","section_padding":"4","phone_section_height":"50","tablet_section_height":"50","row_padding":"2","phone_row_height":"30","tablet_row_height":"30","cover_background":"on","body_font_size":"14","body_font_height":"1.7","phone_body_font_size":"14","tablet_body_font_size":"14","body_header_size":"30","body_header_spacing":"0","body_header_height":"1","body_header_style":"","phone_header_font_size":"30","tablet_header_font_size":"30","heading_font":"none","body_font":"none","font_color":"#666666","header_color":"#666666","color_schemes":"none","vertical_nav":"","vertical_nav_orientation":"left","hide_nav":"","slide_nav_show_top_bar":"on","slide_nav_width":"320","slide_nav_font_size":"14","slide_nav_top_font_size":"14","fullscreen_nav_font_size":"30","fullscreen_nav_top_font_size":"18","slide_nav_font_spacing":"0","slide_nav_font":"none","slide_nav_font_style":"","slide_nav_bg":"#c59d5f","slide_nav_links_color":"#ffffff","slide_nav_links_color_active":"#ffffff","slide_nav_top_color":"rgba(255,255,255,0.6)","slide_nav_search":"rgba(255,255,255,0.6)","slide_nav_search_bg":"rgba(0,0,0,0.2)","nav_fullwidth":"","hide_primary_logo":"","menu_margin_top":"0","primary_nav_font_size":"14","primary_nav_font_spacing":"0","primary_nav_font":"none","secondary_nav_font_size":"12","secondary_nav_fullwidth":"","secondary_nav_font_spacing":"0","secondary_nav_font":"none","secondary_nav_font_style":"","hide_mobile_logo":"","mobile_menu_link":"#ffffff","primary_nav_dropdown_link_color":"#ffffff","primary_nav_dropdown_animation":"fade","mobile_primary_nav_bg":"#262526","secondary_nav_dropdown_animation":"fade","primary_nav_text_color":"dark","secondary_nav_text_color":"light","fixed_primary_nav_font_size":"14","show_footer_social_icons":"on","footer_columns":"4","widget_header_font_size":18,"widget_header_font_style":false,"widget_body_font_size":"14","widget_body_line_height":"1.7","widget_body_font_style":false,"footer_widget_text_color":"#ffffff","footer_widget_link_color":"#ffffff","footer_widget_header_color":"#c59d5f","footer_widget_bullet_color":"#c59d5f","footer_menu_background_color":"rgba(255,255,255,0.05)","footer_menu_text_color":"#bbbbbb","footer_menu_active_link_color":"#c59d5f","footer_menu_letter_spacing":"0","footer_menu_font_style":false,"footer_menu_font_size":"14","bottom_bar_text_color":"#666666","bottom_bar_font_style":false,"bottom_bar_font_size":"14","disable_custom_footer_credits":"","all_buttons_text_color":"#ffffff","all_buttons_font":"none","all_buttons_selected_icon":"5","all_buttons_icon_color":"#ffffff","all_buttons_icon_placement":"right","all_buttons_icon_hover":"yes","all_buttons_text_color_hover":"#ffffff","library_removed_legacy_layouts":true,"divi_previous_installed_version":"","divi_latest_installed_version":"3.13.1","et_pb_layouts_updated":true,"divi_gf_enable_all_character_sets":"on","divi_skip_font_subset_force":true,"divi_logo":"\/wp-content\/uploads\/2018\/09\/AdobeStock_69038922-Converted-b.png","divi_fixed_nav":"on","divi_gallery_layout_enable":"false","divi_color_palette":"#000000|#ffffff|#e02b20|#e09900|#edf000|#7cda24|#0c71c3|#8300e9","divi_grab_image":"false","divi_blog_style":"false","divi_sidebar":"et_right_sidebar","divi_shop_page_sidebar":"et_right_sidebar","divi_show_facebook_icon":"on","divi_show_twitter_icon":"false","divi_show_google_icon":"false","divi_show_linkedin_icon":"false","divi_show_youtube_icon":"false","divi_show_instagram_icon":"on","divi_show_tripadvisor_icon":"false","divi_show_houzz_icon":"false","divi_show_rss_icon":"false","divi_facebook_url":"https:\/\/www.facebook.com","divi_twitter_url":"#","divi_google_url":"#","divi_linkedin_url":"#","divi_youtube_url":"#","divi_instagram_url":"https:\/\/instagram.com","divi_tripadvisor_url":"#","divi_houzz_url":"#","divi_rss_url":"","divi_woocommerce_archive_num_posts":9,"divi_catnum_posts":6,"divi_archivenum_posts":5,"divi_searchnum_posts":5,"divi_tagnum_posts":5,"divi_date_format":"M j, Y","divi_use_excerpt":"false","divi_responsive_shortcodes":"on","divi_back_to_top":"false","divi_smooth_scroll":"false","divi_disable_translations":"false","divi_minify_combine_scripts":"on","divi_minify_combine_styles":"on","divi_custom_css":"","divi_enable_dropdowns":"on","divi_home_link":"on","divi_sort_pages":"post_title","divi_order_page":"asc","divi_tiers_shown_pages":3,"divi_enable_dropdowns_categories":"on","divi_categories_empty":"on","divi_tiers_shown_categories":3,"divi_sort_cat":"name","divi_order_cat":"asc","divi_disable_toptier":"false","divi_scroll_to_anchor_fix":"false","et_pb_post_type_integration":{"page":"on","post":"on","project":"on"},"et_pb_product_tour_global":"on","divi_postinfo2":["author","date","categories","comments"],"divi_show_postcomments":"on","divi_thumbnails":"on","divi_page_thumbnails":"false","divi_show_pagescomments":"false","divi_postinfo1":["author","date","categories"],"divi_thumbnails_index":"on","divi_seo_home_title":"false","divi_seo_home_description":"false","divi_seo_home_keywords":"false","divi_seo_home_canonical":"false","divi_seo_home_titletext":"","divi_seo_home_descriptiontext":"","divi_seo_home_keywordstext":"","divi_seo_home_type":"BlogName | Blog description","divi_seo_home_separate":" | ","divi_seo_single_title":"false","divi_seo_single_description":"false","divi_seo_single_keywords":"false","divi_seo_single_canonical":"false","divi_seo_single_field_title":"seo_title","divi_seo_single_field_description":"seo_description","divi_seo_single_field_keywords":"seo_keywords","divi_seo_single_type":"Post title | BlogName","divi_seo_single_separate":" | ","divi_seo_index_canonical":"false","divi_seo_index_description":"false","divi_seo_index_type":"Category name | BlogName","divi_seo_index_separate":" | ","divi_integrate_header_enable":"on","divi_integrate_body_enable":"on","divi_integrate_singletop_enable":"on","divi_integrate_singlebottom_enable":"on","divi_integration_head":"","divi_integration_body":"","divi_integration_single_top":"","divi_integration_single_bottom":"","divi_468_enable":"false","divi_468_image":"","divi_468_url":"","divi_468_adsense":"","divi_show_fa-instagram_icon":"on","divi_show_fa-youtube-square_icon":"false","divi_show_fa-pinterest_icon":"false","divi_show_fa-linkedin_icon":"false","divi_show_fa-skype_icon":"false","divi_show_fa-flickr_icon":"false","divi_show_fa-dribbble_icon":"false","divi_show_fa-vimeo_icon":"false","divi_show_fa-500px_icon":"false","divi_show_fa-behance_icon":"false","divi_show_fa-github_icon":"false","divi_show_fa-bitbucket_icon":"false","divi_show_fa-deviantart_icon":"false","divi_show_fa-medium_icon":"false","divi_show_fa-meetup_icon":"false","divi_show_fa-slack_icon":"false","divi_show_fa-snapchat_icon":"false","divi_show_fa-tripadvisor_icon":"false","divi_show_fa-twitch_icon":"false","divi_fa-instagram_url":"http:\/\/instagram.com","divi_fa-youtube-square_url":"#","divi_fa-pinterest_url":"#","divi_fa-linkedin_url":"#","divi_fa-skype_url":"#","divi_fa-flickr_url":"#","divi_fa-dribbble_url":"#","divi_fa-vimeo_url":"#","divi_fa-500px_url":"#","divi_fa-behance_url":"#","divi_fa-github_url":"#","divi_fa-bitbucket_url":"#","divi_fa-deviantart_url":"#","divi_fa-medium_url":"#","divi_fa-meetup_url":"#","divi_fa-slack_url":"#","divi_fa-snapchat_url":"#","divi_fa-tripadvisor_url":"#","divi_fa-twitch_url":"#","et_pb_clear_templates_cache":true}', true);

    update_option('et_divi', $et_divi);

    //restaurant seo options.
    $restaurant_options = json_decode('[{"option_id":"481","option_name":"df_restaurant_seo_enable_seo","option_value":"1","autoload":"no"},{"option_id":"482","option_name":"_df_restaurant_seo_enable_seo","option_value":"field_5a717ff202112","autoload":"no"},{"option_id":"483","option_name":"df_restaurant_seo_name","option_value":"Divi\'s Sea Food Restaurant","autoload":"no"},{"option_id":"484","option_name":"_df_restaurant_seo_name","option_value":"field_5a717c1c99684","autoload":"no"},{"option_id":"485","option_name":"df_restaurant_seo_logo","option_value":"7","autoload":"no"},{"option_id":"486","option_name":"_df_restaurant_seo_logo","option_value":"field_5a717c3999685","autoload":"no"},{"option_id":"487","option_name":"df_restaurant_seo_image","option_value":"455","autoload":"no"},{"option_id":"488","option_name":"_df_restaurant_seo_image","option_value":"field_5a717c6199686","autoload":"no"},{"option_id":"489","option_name":"df_restaurant_seo_address","option_value":"Circular Quay, Sydney, NSW, Australia 90007","autoload":"no"},{"option_id":"490","option_name":"_df_restaurant_seo_address","option_value":"field_5a717c8899687","autoload":"no"},{"option_id":"491","option_name":"df_restaurant_seo_cuisines_served_0_cuisine","option_value":"Grilled Sea Food","autoload":"no"},{"option_id":"492","option_name":"_df_restaurant_seo_cuisines_served_0_cuisine","option_value":"field_5a717cb399689","autoload":"no"},{"option_id":"493","option_name":"df_restaurant_seo_cuisines_served","option_value":"1","autoload":"no"},{"option_id":"494","option_name":"_df_restaurant_seo_cuisines_served","option_value":"field_5a717c9999688","autoload":"no"},{"option_id":"495","option_name":"df_restaurant_seo_telephone","option_value":"12345678","autoload":"no"},{"option_id":"496","option_name":"_df_restaurant_seo_telephone","option_value":"field_5a717cd69968a","autoload":"no"},{"option_id":"497","option_name":"df_restaurant_seo_price_range","option_value":"20 AUD to 100 AUD","autoload":"no"},{"option_id":"498","option_name":"_df_restaurant_seo_price_range","option_value":"field_5a71837a2797c","autoload":"no"},{"option_id":"499","option_name":"df_restaurant_seo_menu_pages_0_name","option_value":"Menu","autoload":"no"},{"option_id":"500","option_name":"_df_restaurant_seo_menu_pages_0_name","option_value":"field_5a7186b01c28a","autoload":"no"},{"option_id":"501","option_name":"df_restaurant_seo_menu_pages_0_menu_page","option_value":"454","autoload":"no"},{"option_id":"502","option_name":"_df_restaurant_seo_menu_pages_0_menu_page","option_value":"field_5a717d2c12d6c","autoload":"no"},{"option_id":"503","option_name":"df_restaurant_seo_menu_pages","option_value":"1","autoload":"no"},{"option_id":"504","option_name":"_df_restaurant_seo_menu_pages","option_value":"field_5a717d0412d6b","autoload":"no"},{"option_id":"505","option_name":"df_restaurant_seo_currency","option_value":"AUD","autoload":"no"},{"option_id":"506","option_name":"_df_restaurant_seo_currency","option_value":"field_5a71852def81b","autoload":"no"},{"option_id":"507","option_name":"df_restaurant_seo_language","option_value":"English","autoload":"no"},{"option_id":"508","option_name":"_df_restaurant_seo_language","option_value":"field_5a73fea498634","autoload":"no"}]', true);

    foreach ($restaurant_options as $restaurant_option) {
        update_option($restaurant_option['option_name'], $restaurant_option['option_value']);
    }
}

add_action('pt-ocdi/after_import', 'ocdi_after_import_setup');

add_filter('pt-ocdi/disable_pt_branding', '__return_true');

function ocdi_plugin_intro_text($default_text)
{
    $default_text .= '<div class="ocdi__intro-text">One click import of demo data, Divi theme customizer settings and WordPress widgets for the <b>Restaurant Child Theme</b></div>';

    return $default_text;
}

add_filter('pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text');