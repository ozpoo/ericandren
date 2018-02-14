<?php
/*
 *  Author: Eric Andren | @ozpoo
 */

if (function_exists('add_theme_support')) {
    add_theme_support('menus');

    add_theme_support('post-thumbnails');
    add_image_size('x-large', 2400, '', true);
    add_image_size('large', 1200, '', true);
    add_image_size('medium', 800, '', true);
    add_image_size('small', 600, '', true);
    add_image_size('small-ratio', 600, 400, true);
    add_image_size('micro', 300, '', true);
    add_image_size('micro-ratio', 300, 200, true);
    add_image_size('tile', 512, 512, true);
    add_image_size('holder', 2, '', true);

    add_theme_support('automatic-feed-links');
    load_theme_textdomain('oz', get_template_directory() . '/languages');
}

function header_scripts() {
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
    	wp_register_script('conditionizr',
        get_template_directory_uri() . '/assets/js/_lib/conditionizr-4.3.0.min.js',
        array(), '4.3.0');
        wp_enqueue_script('conditionizr');

        wp_register_script('modernizr',
          get_template_directory_uri() . '/assets/js/_lib/modernizr-2.7.1.min.js',
          array(), '2.7.1');
        wp_enqueue_script('modernizr');

        wp_register_script('fontawesome',
          get_template_directory_uri() . '/assets/font/Font Awesome/svg-with-js/js/fontawesome-all.min.js',
          array('jquery'), '1.0.0');
        wp_enqueue_script('fontawesome');

        wp_register_script('js-cookie',
          get_template_directory_uri() . '/assets/js/_lib/js-cookie/js.cookie.js',
          array(), '1.0.0');
        wp_enqueue_script('js-cookie');

        wp_register_script('tinycolor',
          get_template_directory_uri() . '/assets/js/_lib/tinycolor2/dist/tinycolor-min.js',
          array(), '1.0.0');
        wp_enqueue_script('tinycolor');

        wp_register_script('script',
          get_template_directory_uri() . '/assets/js/build/build.js?v='.time(),
          array('jquery'), '1.0.0');
        wp_enqueue_script('script');

        // wp_register_script('instafeed',
        //   get_template_directory_uri() . '/assets/js/_lib/instafeed.min.js',
        //   array(), '1.0.0');
        // wp_enqueue_script('instafeed');
    }
}

function conditional_scripts(){

  if ( is_page("home") ) {

		wp_register_script('three',
      get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('three');

    wp_register_script('ec',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/postprocessing/EffectComposer.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('ec');

    wp_register_script('rp',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/postprocessing/RenderPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('rp');

    wp_register_script('sp',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/postprocessing/ShaderPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('sp');

    wp_register_script('mp',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/postprocessing/MaskPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('mp');

    wp_register_script('cs',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/shaders/CopyShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('cs');

    wp_register_script('fs',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/shaders/FilmShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('fs');

    wp_register_script('rgbs',
      get_template_directory_uri() . '/assets/js/_lib/three/lib/shaders/RGBShiftShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('rgbs');

    wp_register_script('btvs',
      get_template_directory_uri() . '/assets/js/_lib/three/js/BadTVShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('btvs');

    wp_register_script('ss',
      get_template_directory_uri() . '/assets/js/_lib/three/js/StaticShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('ss');

    wp_register_script('script',
      get_template_directory_uri() . '/assets/js/script.js?v=' . time(),
      array('jquery'),
      '1.0.0');
    wp_enqueue_script('script');

  }

  elseif ( is_page("selfie-language") ) {

    global $results;

    $ID = get_the_ID();
    $time = get_field("time", $ID);
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    if ( $time_curr > $time_check ) {
      $results = instagram_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results, $ID);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr, $ID);
    } else {
      $results = get_field("cache", $ID);
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode($results, true);

    wp_localize_script( 'script', 'SELFIE_DATA', $results );

    wp_localize_script( 'script', 'POST_SUBMITTER', array(
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'success' => 'Thanks for your submission!',
        'failure' => 'Your submission could not be processed.',
        'current_user_id' => get_current_user_id()
      )
    );

  }

  elseif ( is_page("average-selfie") ) {

    global $results;
    global $time_curr;
    global $time_previous;
    global $time_check;

    $ID = get_the_ID();
    $time = get_field("time", $ID);
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    if ( $time_curr > $time_check ) {
      $results = instagram_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results, $ID);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr, $ID);
    } else {
      $results = get_field("cache", $ID);
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode($results, true);

    wp_localize_script( 'script', 'SELFIE_DATA', $results );

    wp_register_script('tracking',
      get_template_directory_uri() . '/assets/js/_lib/tracking/build/tracking-min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('tracking');

    wp_register_script('image',
      get_template_directory_uri() . '/assets/js/_lib/tracking/src/utils/Image.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('image');

    wp_register_script('face',
      get_template_directory_uri() . '/assets/js/_lib/tracking/build/data/face-min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('face');

    wp_register_script('eye',
      get_template_directory_uri() . '/assets/js/_lib/tracking/build/data/eye-min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('eye');

    wp_register_script('mouth',
      get_template_directory_uri() . '/assets/js/_lib/tracking/build/data/mouth-min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('mouth');

  }

  elseif ( is_page("selfieverse") ) {

    global $results;

    $ID = get_the_ID();
    $time = get_field("time", $ID);
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    if ( $time_curr > $time_check ) {
      $results = instagram_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results, $ID);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr, $ID);
    } else {
      $results = get_field("cache", $ID);
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode($results, true);

    wp_localize_script( 'script', 'SELFIE_DATA', $results );

    wp_register_script('three',
      get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('three');

    wp_register_script('fly-controls',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/controls/FlyControls.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('fly-controls');

    wp_register_script('CopyShader',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/shaders/CopyShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('CopyShader');

    wp_register_script('FilmShader',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/shaders/FilmShader.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('FilmShader');

    wp_register_script('EffectComposer',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/postprocessing/EffectComposer.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('EffectComposer');

    wp_register_script('ShaderPass',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/postprocessing/ShaderPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('ShaderPass');

    wp_register_script('MaskPass',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/postprocessing/MaskPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('MaskPass');

    wp_register_script('RenderPass',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/postprocessing/RenderPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('RenderPass');

    wp_register_script('FilmPass',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/postprocessing/FilmPass.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('FilmPass');

  }

  elseif ( is_page("contour") ) {

    // http://www.marine-geo.org/tools/gridserverinfo.php#!/tools/getGMRTGrid"
    // http://www.opentopography.org/developers
    // http://rmw.recordist.com/
    // http://dwtkns.com/srtm/

    $ID = get_the_ID();

    $north = "47.66";
    $west= "-122.36";
    $east= "-122.32";
    $south= "47.63";
    $format = "geotiff";
    $res = "high";

    $image_url =
      'http://www.marine-geo.org/services/GridServer'.
      '?north=' . $north .
      '&west='. $west .
      '&east=' . $east .
      '&south='. $south .
      '&layer=topo-mask' .
      '&format=' . $format .
      '&resolution=' . $res;

    $meta_url =
      'http://www.marine-geo.org/services/GridServer/metadata'.
      '?north=' . $north .
      '&west='. $west .
      '&east=' . $east .
      '&south='. $south .
      '&format=' . $format .
      '&mformat=json'.
      '&resolution=' . $res;

    $meta = topo_api_request($meta_url);

    // $att = update_attachment( $ID , $image_url );
    // $row = array(
    // 	'image'	=> $att['attach_id'],
    // 	'meta'	=> $meta
    // );
    // add_row('tiles', $row);

    $results = json_decode($meta, true);
    $results["src"] = $image_url;

    wp_localize_script( 'script', 'TOPO_IMAGE', $results );

    wp_register_script('three',
      get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('three');

    wp_register_script('detector',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/Detector.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('detector');

    wp_register_script('trackball',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/controls/TrackballControls.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('trackball');

    wp_register_script('chroma',
      get_template_directory_uri() . '/assets/js/_lib/chroma/chroma.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('chroma');

  }

  elseif ( is_page("google-maps") ) {

    // wp_register_style('base',
    //   'https://www.mapbox.com/base/latest/base.css',
    //   array(), '1.0', 'all');
    // wp_enqueue_style('base');
    //
    // wp_register_script('mapbox',
    //   'https://api.tiles.mapbox.com/mapbox.js/v2.1.9/mapbox.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('mapbox');
    //
    // wp_register_style('mapbox',
    //   'https://api.tiles.mapbox.com/mapbox.js/v2.1.9/mapbox.css',
    //   array(), '1.0', 'all');
    // wp_enqueue_style('mapbox');
    //
    // wp_register_script('sm',
    //   get_template_directory_uri() . '/assets/js/_lib/mapbox/libs/sm.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('sm');
    //
    // wp_register_script('utilities',
    //   get_template_directory_uri() . '/assets/js/_lib/mapbox/libs/utilities.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('utilities');
    //
    // wp_register_script('sidebar',
    //   get_template_directory_uri() . '/assets/js/_lib/mapbox/components/sidebar.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('sidebar');
    //
    // wp_register_script('detector',
    //   get_template_directory_uri() . '/assets/js/_lib/three/examples/js/Detector.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('detector');
    //
    // wp_register_script('three',
    //   get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('three');
    //
    // wp_register_script('app',
    //   get_template_directory_uri() . '/assets/js/_lib/mapbox/libs/app.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('app');
    //
    // wp_register_script('controls',
    //   get_template_directory_uri() . '/assets/js/_lib/three/examples/js/controls/OrbitControls.js',
    //   array('jquery'), '1.0.0');
    // wp_enqueue_script('controls');

  }

  elseif ( is_page("gleon-buoy") ) {

    global $results;

    $ID = get_the_ID();
    $time = get_field("time", $ID);
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    if ( $time_curr > $time_check ) {
      $results = gleon_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results, $ID);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr, $ID);
    } else {
      $results = get_field("cache", $ID);
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode($results, true);

    wp_localize_script( 'script', 'GLEON_DATA', $results );

    wp_register_script('three',
      get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('three');

    wp_register_script('trackball',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/controls/TrackballControls.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('trackball');

  }

  elseif ( is_page("gleon-buoy-water") ) {

    global $results;

    $ID = get_the_ID();
    $time = get_field("time", $ID);
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    if ( $time_curr > $time_check ) {
      $results = gleon_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results, $ID);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr, $ID);
    } else {
      $results = get_field("cache", $ID);
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode($results, true);

    wp_localize_script( 'script', 'GLEON_DATA', $results );

    wp_register_script('three',
      get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('three');

    wp_register_script('OrbitControls',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/controls/OrbitControls.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('OrbitControls');

    wp_register_script('OrbitControls',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/controls/OrbitControls.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('OrbitControls');

    wp_register_script('OceanShaders',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/shaders/OceanShaders.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('OceanShaders');

    wp_register_script('Ocean',
      get_template_directory_uri() . '/assets/js/_lib/three/examples/js/Ocean.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('Ocean');

  }

  elseif ( is_page("gleon-buoy-switch") ) {

    global $results;

    $ID = get_the_ID();
    $time = get_field("time", $ID);
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    // if ( $time_curr > $time_check ) {
    if ( false ) {
      $results = gleon_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results, $ID);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr, $ID);
    } else {
      $results = get_field("cache", $ID);
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode($results, true);

    wp_localize_script( 'script', 'GLEON_DATA', $results );

    wp_register_script('p5',
      get_template_directory_uri() . '/assets/js/_lib/p5/p5.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('p5');

  }

  else if ( is_page("earthquake") ) {

    global $results;

    $ID = get_the_ID();
    $time = get_field("time");
    $time_curr = time();
    $time_previous = date($time);
    $time_check = $time_previous + 60*60;

    if ( $time_curr > $time_check ) {
      $results = earthquake_api_request();
      $results = utf8_encode($results);
      $results = htmlspecialchars($results);
      update_field('cache', $results);
      $time_curr = utf8_encode($time_curr);
      update_field('time', $time_curr);
    } else {
      $results = get_field("cache");
    }

    $results = htmlspecialchars_decode($results);
    $results = json_decode( $results, true );

    wp_localize_script( 'script', 'EARTHQUAKE_DATA', $results );

    wp_register_script('three',
      get_template_directory_uri() . '/assets/js/_lib/three/build/three.min.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('three');

    wp_register_script('webglobe',
      get_template_directory_uri() . '/assets/js/_lib/webgl-globe/globe/globe.js?v='.time(),
      array('jquery'), '1.0.0');
    wp_enqueue_script('webglobe');

  }

  else if ( is_single() ) {

    wp_register_script('glitch',
      get_template_directory_uri() . '/assets/js/_lib/glitch.js',
      array('jquery'), '1.0.0');
    wp_enqueue_script('glitch');

  }

}

function styles() {
  wp_register_style('Space Mono',
    get_template_directory_uri() . '/assets/font/Space Mono Web/stylesheet.css?v='.time(),
    array(), '1.0', 'all');
  wp_enqueue_style('Space Mono');

  wp_register_style('Black Rock',
    get_template_directory_uri() . '/assets/font/Black Rock/stylesheet.css?v='.time(),
    array(), '1.0', 'all');
  wp_enqueue_style('Black Rock');

  // wp_register_style('Now Alt',
  //   get_template_directory_uri() . '/assets/font/Now Alt/stylesheet.css?v='.time(),
  //   array(), '1.0', 'all');
  // wp_enqueue_style('Now Alt');

  // wp_register_style('Sofia Pro',
  //   get_template_directory_uri() . '/assets/font/Sofia Pro/style.css?v='.time(),
  //   array(), '1.0', 'all');
  // wp_enqueue_style('Sofia Pro');

  wp_register_style('Sailec',
    get_template_directory_uri() . '/assets/font/Sailec/stylesheet.css?v='.time(),
    array(), '1.0', 'all');
  wp_enqueue_style('Sailec');

  // wp_register_style('Neutrif',
  // get_template_directory_uri() . '/assets/fonts/Neutrif Studio/stylesheet.css',
  // array(), '1.0', 'all');
  // wp_enqueue_style('Neutrif');
  //
  // wp_register_style('Maison',
  // get_template_directory_uri() . '/assets/fonts/Maison Neue/stylesheet.css',
  // array(), '1.0', 'all');
  // wp_enqueue_style('Maison');

  wp_register_style('style',
    get_template_directory_uri() . '/assets/css/build/build.css?v='.time(),
    array(), '1.0', 'all');
  wp_enqueue_style('style');
}

function my_wp_nav_menu_args($args = '') {
    $args['container'] = false;
    return $args;
}

function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}

function remove_category_rel_from_category_list($thelist) {
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

function add_slug_to_body_class($classes) {
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

function pagination() {
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

function html5wp_index($length) {
    return 20;
}

function html5wp_custom_post($length) {
    return 40;
}

function html5wp_excerpt($length_callback = '', $more_callback = '') {
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

function view_article($more) {
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

function remove_admin_bar() {
    return false;
}

function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

function gleon_api_request() {
  $results = [];
  $api = "https://wqdatalive.com/api/public/list";
  $response = get_curl($api);
  return $response;
}

function topo_api_request($api) {
  $results = [];
  $response = get_curl($api);
  return $response;
}

function earthquake_api_request() {
  // $results = [];
  // $api = "https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_hour.geojson";
  // $response = get_curl($api);
  //
  // $results = [];
  // $api = "https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_day.geojson";
  // $response = get_curl($api);
  //
  // $results = [];
  // $api = "https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_week.geojson";
  // $response = get_curl($api);

  $results = [];
  $api = "https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_month.geojson";
  $response = get_curl($api);

  return $response;
}

function instagram_api_request() {
  $results = [];
  $image = [];
  $client = "001b9288d8264d47bbb9fc434a90b421";
  $access = "6092238.e029fea.f79493609dea415398f04a9921bdd7d5";
  $user = "6092238";
  $query = 'selfie';
  $count = 30;
  $api = "https://api.instagram.com/v1/tags/".$query."/media/recent?count=".$count."&client_id=".$client."&access_token=".$access;
  $response = get_curl($api);
  // $next_url = json_decode($response)->pagination->next_url;
  //
  // foreach(json_decode($response)->data as $item) {
  //   $src = $item->images->standard_resolution->url;
  //   $thumb = $item->images->thumbnail->url;
  //   $url = $item->link;
  //   $results[] = array(
  //     "src" => htmlspecialchars($src),
  //     "thumb" => htmlspecialchars($thumb),
  //     "url" => htmlspecialchars($url),
  //     "raw" => $item
  //   );
  // }
  // return json_encode($results);
  return $response;
}

function get_curl($url) {
  if(function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    echo curl_error($ch);
    curl_close($ch);
    return $output;
  } else{
    return file_get_contents($url);
  }
}

function update_attachment( $pid, $url ){
  $wp_upload_dir = wp_upload_dir();
  $path = $wp_upload_dir['path'] . '/' . time() . '.tiff';
  $contents = file_get_contents($url);
  $savefile = fopen($path, 'w');
  fwrite($savefile, $contents);
  fclose($savefile);
	$attachment = array(
		'guid'           => $path,
		'post_parent' 	 => $pid,
		'post_title'     => basename( $url ),
		'post_type' 	 => 'attachment',
		'post_content'   => '',
		'post_status'    => 'inherit',
		'post_mime_type' => 'image/tiff',
	);
  $attach_id = wp_insert_attachment( $attachment, $path );
  $image = get_post( $attach_id );
  $full_path = get_attached_file( $image->ID );
  $attach_data = wp_generate_attachment_metadata( $attach_id, $full_path );
  wp_update_attachment_metadata( $attach_id, $attach_data );
	return array(
		'pid' => $pid,
		'url' => $path,
    'attach_id' => $attach_id
		);
}

add_action('init', 'header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'conditional_scripts'); // Add Conditional Page Scripts
add_action('wp_enqueue_scripts', 'styles'); // Add Theme Stylesheet
add_action('init', 'pagination'); // Add our HTML5 Pagination

remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

function fb_unautop_4_img( $content ) {
  $content = preg_replace(
    '/<p>\\s*?(<a rel=\"attachment.*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s',
    '<figure>$1</figure>',
    $content
  );
  return $content;
}
add_filter( 'the_content', 'fb_unautop_4_img', 99 );

function alx_embed_html( $html ) {
    return '<figure>' . $html . '</figure>';
}
add_filter( 'embed_oembed_html', 'alx_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'alx_embed_html' );

function wp_rest_api_alter() {
  register_api_field( 'selfies', 'fields',
    array(
      'get_callback'    => function($data, $field, $request, $type){
        if (function_exists('get_fields')) {
          return get_fields($data['id']);
        }
        return [];
      },
      'update_callback' => null,
      'schema'          => null,
    )
  );
}
add_action( 'rest_api_init', 'wp_rest_api_alter');

function wp_api_add_tax($post, $data, $update){
    foreach( $data['cat_selfies'] as $tax => $val ){
        wp_set_object_terms( $post['ID'], $data['cat_selfies'], 'cat_selfies' );
    }
}
add_filter('json_insert_post', 'wp_api_add_tax', 10, 3);
?>
