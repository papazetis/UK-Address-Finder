<?php
/**
Plugin Name: UK Address Finder
Description: UK Address Finder from postcode with GetAddress.io API
Version:     1.5
Author:      Nick Papazetis
Author URI:  http://www.papazetis.com
License:     GPL2

UK Address Finder is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

UK Address Finder is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with UK Address Finder. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
 */

if ( is_admin() ) {
  require( dirname( __FILE__ ) . '/admin/init.php' );
}

include( dirname( __FILE__ ) . '/inc/init.php' );
