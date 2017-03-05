<?php

function enqueue_gmap_init() {
  wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAsDLvvoRZuTG2k8tbW7XEmn7QyuzQQBeM', '', null );
	wp_enqueue_script( 'gmap-script', plugins_url( 'gmaps.min.js', __FILE__ ), array( 'jquery' ), null );
	}
add_action( 'init', 'enqueue_gmap_init' );

function enqueue_scripts_styles_init() {
	wp_enqueue_script( 'ajax-script', plugins_url( 'ajax.js', __FILE__ ), array( 'jquery' ), 1.0 );
	wp_localize_script( 'ajax-script', 'ajax_uaf', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'init', 'enqueue_scripts_styles_init' );

// Ajax action for display addresses
add_action( 'wp_ajax_ajax_postcode', 'ajax_postcode' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_ajax_postcode', 'ajax_postcode' ); // ajax for not logged in users

// Ajax action for display google map
add_action( 'wp_ajax_ajax_gmap', 'ajax_gmap' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_ajax_gmap', 'ajax_gmap' ); // ajax for not logged in users

// Ajax function for display addresses
function ajax_postcode() {
  check_ajax_referer( 'uaf_nonce', 'security' );
  $uaf_api_key = !empty(get_option( 'uaf_settings' )) ? get_option( 'uaf_settings' ) : '';
  if (isset( $_POST['uaf_postcode'] ) ) {
    $uaf_postcode = wp_unslash( $_POST['uaf_postcode'] ); //
    $response = wp_remote_get( esc_url_raw( 'https://api.getAddress.io/v2/uk/' . $uaf_postcode . '?api-key=' . $uaf_api_key['uaf_API_key'] ) );
    $response_code = wp_remote_retrieve_response_code( $response );
    $get_addresses = json_decode( wp_remote_retrieve_body( $response ), true );
    $addresses  = $get_addresses['Addresses'];
    natsort( $addresses );
    $latitude   = $get_addresses['Latitude'];
    $longitude  = $get_addresses['Longitude'];
  }
  ?>

<?php
switch ($response_code) {
  case '200':
  ?>
  <input id="response_200" type="hidden" value="<?php echo $response_code ?>">
  <input id="latitude" type="hidden" value="<?php echo $latitude ?>">
  <input id="longitude" type="hidden" value="<?php echo $longitude ?>">
  <select id="address_sel" name="address_sel">
    <option value="">Select your Address</option>
    <?php
    if (is_array($addresses)) {
      foreach ($addresses as $address) {
        ?>
        <option value="<?php echo esc_attr( $address ) ?>" <?php selected( $address_sel, $address ) ?>><?php echo esc_attr( str_replace( ' ,', '', $address ) ) ?></option>
        <?php
      }
    }
    ?>
  </select>
  <?php
    break;

  case '404':
    echo "No addresses could be found for this postcode.";
    break;

  case '400':
    echo "Your postcode is not valid.";
    break;

  case '429':
    echo "You have made more requests than your allowed limit.";
    break;

  case '401':
    echo "Your API Key is not valid.";
    break;

  default:
    break;
} //End switch statement

wp_die(); // stop executing script
}

// Ajax function for display google map
function ajax_gmap() {

wp_die(); // stop executing script
}
