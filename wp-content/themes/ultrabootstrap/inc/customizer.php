<?php
/**
 * ultrabootstrap Theme Customizer
 *
 * @package ultrabootstrap
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */


function ultrabootstrap_customize_register( $wp_customize ) {
  $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
  $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'ultrabootstrap_customize_register' );


function ultrabootstrap_customizer_register( $wp_customize ) 
    {
      // Do stuff with $wp_customize, the WP_Customize_Manager object.

      $wp_customize->add_panel( 'theme_option', array(
        'priority' => 220,
        'title' => __( 'Ultrabootstrap Options', 'ultrabootstrap' ),
        'description' => __( 'ultrabootstrap Options', 'ultrabootstrap' ),
      ));
      

      /**********************************************/
      /************* MAIN SLIDER SECTION *************/
      /**********************************************/     

      $wp_customize->add_section('main_slider_category',array(
        'priority' => 50,
        'title' => __('Slider Categories','ultrabootstrap'),
        'description' => __('Select the Slide Category for Homepage.','ultrabootstrap'),
        'panel' => 'theme_option'
      ));

      $wp_customize->add_setting('slider_category_display',array(
        'sanitize_callback' => 'ultrabootstrap_sanitize_category',
        'default' => ''
      ));

      $wp_customize->add_control(new ultrabootstrap_Customize_Dropdown_Taxonomies_Control($wp_customize,'slider_category_display',array(
        'label' => __('Choose category','ultrabootstrap'),
        'section' => 'main_slider_category',
        'settings' => 'slider_category_display',
        'type'=> 'dropdown-taxonomies',
        )  
      ));

       // no of posts to show on slider
    $wp_customize->add_setting( 'slider_no_of_posts', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'esc_attr',
    'default'           => '3'
    ) );

    $wp_customize->add_control( 'slider_no_of_posts', array(
    'settings' => 'slider_no_of_posts',
    'label'                 =>  __( 'No Of Posts To Show On Slider', 'ultrabootstrap' ),
    'section'               => 'main_slider_category',
      
    'type'                  => 'select',
    'choices'               => array(
         '1' => __( '1', 'ultrabootstrap' ),
         '2' => __( '2 ', 'ultrabootstrap' ),
         '3' => __( '3', 'ultrabootstrap' ),
         '4' => __( '4', 'ultrabootstrap' ),
         '5' => __( '5', 'ultrabootstrap' ),
         '6' => __( '6', 'ultrabootstrap' ),
         '7' => __( '7', 'ultrabootstrap' ),
         '8' => __( '8', 'ultrabootstrap' ),
         '9' => __( '9', 'ultrabootstrap' )
                        ),
    'priority'              => '220'
    ) );



      /**********************************************/
      /*************** WELCOME SECTION ***************/
      /**********************************************/

      $wp_customize->add_section('welcome_text',array(
        'priority' => 60,
        'title' => __('Welcome Section','ultrabootstrap'),
        'description' => __('Write Some Words for Welcome Section in Homepage','ultrabootstrap'),
        'panel' => 'theme_option'
      ));

      $wp_customize->add_setting(
        'welcome_textbox1',
          array(
            'sanitize_callback' => 'ultrabootstrap_sanitize_text',
            'default' => '',
          )
      );

      $wp_customize->add_control(
        'welcome_textbox1',
          array(
          'label' => __('Welcome Heading','ultrabootstrap'),
          'section' => 'welcome_text',
          'settings' => 'welcome_textbox1',
          'type' => 'text',
         )
      );

      $wp_customize->add_setting(
        'welcome_textbox2',
          array(
            'sanitize_callback' => 'ultrabootstrap_sanitize_text',
            'default' => '',
          )
      );

      $wp_customize->add_control(
        'welcome_textbox2',
          array(
          'label' => __('Welcome Second Heading','ultrabootstrap'),
          'section' => 'welcome_text',
          'settings' => 'welcome_textbox2',
          'type' => 'text',
         )
      );


      $wp_customize->add_setting( 
        'textarea_setting' ,
          array(
            'sanitize_callback' => 'ultrabootstrap_sanitize_text',
            'default' => '', 
        )); 
   
      $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'textarea_setting', array( 
        'label' => __( 'Welcome Text Content', 'ultrabootstrap' ),
        'section' => 'welcome_text',
        'settings' => 'textarea_setting', 
        'type'     => 'textarea', 
        )));    


      $wp_customize->add_section('content' , array(
        'title' => __('Content','ultrabootstrap'),
      ));


      $wp_customize->add_setting(
        'welcome_button',
            array(
              'sanitize_callback' => 'esc_url_raw',
              'default' => '',
          )
      );

      $wp_customize->add_control(
        'welcome_button',
         array(
          'label' => __('Welcome Button Link','ultrabootstrap'),
          'section' => 'welcome_text',
          'settings' => 'welcome_button',
          'type' => 'text',
         )
      );      


      /**********************************************/
      /*************** FEATURES SECTION ****************/
      /**********************************************/

      $wp_customize->add_section('features_category',array(
        'priority' => 70,
        'title' => __('Features Categories','ultrabootstrap'),
        'description' => __('Select the Category for Features Section in Homepage','ultrabootstrap'),
        'panel' => 'theme_option'
      ));

      $wp_customize->add_setting(
        'features_title',
          array(
          'sanitize_callback' => 'ultrabootstrap_sanitize_text',
          'default' => '',
          
          )
       );
      $wp_customize->add_control(
        'features_title',
          array(
          'label' => __('Title','ultrabootstrap'),
          'section' => 'features_category',
          'settings' => 'features_title',
           'type' => 'text',
         )
      );

      $wp_customize->add_setting('features_display',array(
        'sanitize_callback' => 'ultrabootstrap_sanitize_category',
        'default' => ''
      ));

      $wp_customize->add_control(new ultrabootstrap_Customize_Dropdown_Taxonomies_Control($wp_customize,'features_display',array(
        'label' => __('Choose category','ultrabootstrap'),
        'section' => 'features_category',
        'settings' => 'features_display',
        'type'=> 'dropdown-taxonomies',
        )  
      ));
      // no of posts to show on Featured
    $wp_customize->add_setting( 'featured_no_of_posts', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'esc_attr',
    'default'           => '4'
    ) );

    $wp_customize->add_control( 'featured_no_of_posts', array(
    'settings' => 'featured_no_of_posts',
    'label'                 =>  __( 'No Of Posts To Show On Feature', 'ultrabootstrap' ),
    'section'               => 'features_category',
      
    'type'                  => 'select',
    'choices'               => array(
         '4' => __( '4', 'ultrabootstrap' ),
         '8' => __( '8 ', 'ultrabootstrap' ),
         '12' => __( '12', 'ultrabootstrap' )
                        ),
    'priority'              => '220'
    ) );


      /**********************************************/
      /*************** SOCIAL SECTION ***************/
      /**********************************************/

       $wp_customize->add_section(
        'social_section',
          array(
            'priority' => 80,
            'title' => __('Social Info','ultrabootstrap'),
            'description' => 'Customize your Social Info',
            'panel' => 'theme_option'
        )
      );


      /**********************************************/
      /********** SOCIAL ICON LINKS SECTION ***********/
      /**********************************************/

      $wp_customize->add_setting(
        'facebook_textbox',
          array(
            'sanitize_callback' => 'esc_url_raw',
            'default' => '#',
          )
      );

      $wp_customize->add_control(
        'facebook_textbox',
          array(
            'label' => __('Facebook','ultrabootstrap'),
            'section' => 'social_section',
            'settings' => 'facebook_textbox',
            'type' => 'text',
            'default' =>'#'
          )
      );

      $wp_customize->add_setting(
        'twitter_textbox',
          array(
            'sanitize_callback' => 'esc_url_raw',
            'default' => '#',
          )
      );

      $wp_customize->add_control(
        'twitter_textbox',
         array(
          'label' => __('Twitter','ultrabootstrap'),
          'section' => 'social_section',
          'settings' => 'twitter_textbox',
          'type' => 'text',
         )
      );

      $wp_customize->add_setting(
        'googleplus_textbox',
          array(
            'sanitize_callback' => 'esc_url_raw',
            'default' => '',
          )
      );

      $wp_customize->add_control(
        'googleplus_textbox',
          array(
          'label' => __('Googleplus','ultrabootstrap'),
          'section' => 'social_section',
          'settings' => 'googleplus_textbox',
          'type' => 'text',
         )
      );

      $wp_customize->add_setting(
        'linkedin_textbox',
            array(
              'sanitize_callback' => 'esc_url_raw',
              'default' => '',
          )
      );

      $wp_customize->add_control(
        'linkedin_textbox',
         array(
          'label' => __('Linkedin','ultrabootstrap'),
          'section' => 'social_section',
          'settings' => 'linkedin_textbox',
          'type' => 'text',
         )
      );

      $wp_customize->add_setting(
        'pinterest_textbox',
          array(
            'sanitize_callback' => 'esc_url_raw',
          'default' => '',
          )
      );
      
      $wp_customize->add_control(
        'pinterest_textbox',
          array(
            'label' => __('Pinterest','ultrabootstrap'),
            'section' => 'social_section',
            'settings' => 'pinterest_textbox',
            'type' => 'text',
         )
      );
     
    }

add_action( 'customize_register', 'ultrabootstrap_customizer_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ultrabootstrap_customize_preview_js() {
  wp_enqueue_script( 'ultrabootstrap_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'ultrabootstrap_customize_preview_js' );

function ultrabootstrap_customizer_js() {
    wp_enqueue_script('ultra-customizer-js', get_template_directory_uri() . '/js/ultra-customizer.js', array('jquery'), '1.3.0', true);

    wp_localize_script( 'ultra-customizer-js', 'ultrabootstrap_customizer_js_obj', array(
        'pro' => __('Upgrade To Ultrabootstrap Pro','ultrabootstrap')
    ) );
    wp_enqueue_style( 'ultra-customizer-css', get_template_directory_uri() . '/css/ultra-customizer.css');
}
add_action( 'customize_controls_enqueue_scripts', 'ultrabootstrap_customizer_js' );

function ultrabootstrap_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

function ultrabootstrap_sanitize_textarea( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

function ultrabootstrap_sanitize_category($input){
  $output=intval($input);
  return $output;

}