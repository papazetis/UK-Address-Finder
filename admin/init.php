<?php
add_action( 'admin_menu', 'uaf_add_admin_menu' );
add_action( 'admin_init', 'uaf_settings_init' );

function uaf_add_admin_menu(  ) {
  add_options_page(
    'UK Address Finder from GetAddress.io',
    'UK Address Finder',
    'manage_options',
    'uk-address-finder',
    'uaf_options_page'
  );
}

function uaf_settings_init(  ) {
  register_setting( 'pluginPage', 'uaf_settings', 'uaf_validate' );

  add_settings_section(
    'uaf_pluginPage_section',
    '',
    'uaf_settings_section_callback',
    'pluginPage'
  );

  add_settings_field(
    'uaf_API_key',
    'GetAddress.io API Key',
    'uaf_API_key_render',
    'pluginPage',
    'uaf_pluginPage_section'
  );
}

function uaf_API_key_render() {
  $uaf_api_key = !empty(get_option( 'uaf_settings' )['uaf_API_key']) ? get_option( 'uaf_settings' )['uaf_API_key'] : '';
  $response = wp_remote_get( esc_url_raw( 'https://api.getAddress.io/usage/?api-key=' . $uaf_api_key ) );
	$response_code = wp_remote_retrieve_response_code( $response );
	$api_message = json_decode( wp_remote_retrieve_body( $response ), true );
  $dailyrequestqount = isset( $api_message['DailyRequestCount'] ) ? $api_message['DailyRequestCount'] : '';
	$dailyrequestlimit1 = isset( $api_message['DailyRequestLimit1'] ) ? $api_message['DailyRequestLimit1'] : '';
	$dailyrequestlimit2 = isset( $api_message['DailyRequestLimit2'] ) ? $api_message['DailyRequestLimit2'] : '';
	$requests_after_limit = $dailyrequestlimit2 - $dailyrequestlimit1;
  ?>
  <input type='text' name='uaf_settings[uaf_API_key]' value='<?php echo isset( $uaf_api_key ) ?  esc_attr( $uaf_api_key )  : ''; ?>' size='50'><br><hr width="340px" align="left">
  <?php
  echo '<strong>Daily Requests: </strong>' . esc_attr( $dailyrequestqount ) . '<br>';
	echo '<strong>Daily Requests Limit: </strong>' . esc_attr( $dailyrequestlimit1 ) . '<br>';
	echo '<strong>After Daily Requests Limit: </strong>' . esc_attr( $requests_after_limit ) . ' more with a 5 second delay';
}

function uaf_settings_section_callback() {
  echo "You can get your API Key by selecting a plan from <a href='https://getaddress.io/#pricing-table' target='_blank'>www.getaddress.io</a> and then use it in every page or post via shortcode <strong>[uk-address-finder]</strong>";
}

function uaf_options_page() {
  ?>
  <form action='options.php' method='post'>
    <h1>UK Address Finder</h1>
    <?php
    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button('Save API Key');
    ?>

  </form>

  <?php
}

function uaf_validate($data) {
  $uaf_api_key = $data['uaf_API_key'];
  $response = wp_remote_get( esc_url_raw( 'https://api.getAddress.io/usage/?api-key=' . $uaf_api_key ) );
	$response_code = wp_remote_retrieve_response_code( $response );
  $message = null;
  $type = null;

    if ( 401 != $response_code ) {
            $type = 'updated';
            $message = 'API Key successfully added';
        } else {
        $type = 'error';
        $message = 'Your API Key is not valid.';
    }

    add_settings_error(
        'uaf_validate_msg',
        esc_attr( 'settings_updated' ),
        $message,
        $type
    );
    if ( 'updated' === $type ) {
    return $data;
  }
  }
