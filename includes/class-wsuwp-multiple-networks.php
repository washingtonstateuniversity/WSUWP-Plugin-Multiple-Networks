<?php

class WSUWP_Multiple_Networks {
	/**
	 * @var WSUWP_Multiple_Networks
	 */
	private static $instance;

	/**
	 * @var string The plugin version number.
	 */
	public static $version = '1.7.10';

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 1.6.0
	 *
	 * @return \WSUWP_Multiple_Networks
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Multiple_Networks();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 1.6.0
	 */
	public function setup_hooks() {
		require_once dirname( __FILE__ ) . '/class-wsuwp-roles-and-capabilities.php';
		require_once dirname( __FILE__ ) . '/class-wsuwp-user-management.php';
		require_once dirname( __FILE__ ) . '/class-wsuwp-network-users.php';
		require_once dirname( __FILE__ ) . '/class-wsuwp-network-admin.php';
		require_once dirname( __FILE__ ) . '/class-wsuwp-admin-header.php';
		require_once dirname( __FILE__ ) . '/class-wsuwp-network-site-new.php';

		add_action( 'plugins_loaded', array( 'WSUWP_Roles_And_Capabilities', 'get_instance' ), 11 );
		add_action( 'plugins_loaded', array( 'WSUWP_User_Management', 'get_instance' ), 12 );
		add_action( 'plugins_loaded', array( 'WSUWP_Network_Users', 'get_instance' ), 13 );
		add_action( 'plugins_loaded', array( 'WSUWP_Network_Admin', 'get_instance' ), 14 );
		add_action( 'plugins_loaded', array( 'WSUWP_Admin_Header', 'get_instance' ), 15 );
		add_action( 'plugins_loaded', array( 'WSUWP_Network_Site_New', 'get_instance' ), 16 );

		add_action( 'admin_init', array( $this, 'load_network_sites_list' ), 5 );
	}

	public function load_network_sites_list() {
		require_once dirname( __FILE__ ) . '/class-wsuwp-network-sites-list.php';
		require_once dirname( __FILE__ ) . '/class-wsuwp-network-site-info.php';

		add_action( 'admin_init', array( 'WSUWP_Network_Sites_List', 'get_instance' ), 10 );
		add_action( 'admin_init', array( 'WSUWP_Network_Site_Info', 'get_instance' ), 10 );
	}
}
