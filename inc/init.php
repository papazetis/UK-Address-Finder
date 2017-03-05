<?php


function uk_address_shortcode() {
  // $response = wp_remote_get( esc_url_raw( 'https://api.getAddress.io/usage/?api-key=' . $options ) );


  ?>

  <input type="text" name="uaf_postcode" class="uaf_postcode" autocomplete="off" placeholder="Please type your postcode">
  <?php wp_nonce_field( 'uaf_nonce', 'uaf_wpnonce' ); ?>

<form id="result"></form>
<div id="gmap" style="display:none; width: 100%; height: 300px;"></div>

<?php
  }

add_shortcode( 'uk-address-finder', 'uk_address_shortcode' );

include( dirname( __FILE__ ) . '/ajax.php' );
