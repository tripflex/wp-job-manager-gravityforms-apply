<?php
/**
 * Plugin Name: WP Job Manager - Apply With Gravity Forms
 * Plugin URI:  https://github.com/Astoundify/wp-job-manager-gravityforms-apply/
 * Description: Apply to jobs that have added an email address via Gravity Forms
 * Author:      Astoundify
 * Author URI:  http://astoundify.com
 * Version:     1.1.2
 * Text Domain: job_manager_gf_apply
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Astoundify_Job_Manager_Apply_GF {

	/**
	 * @var $instance
	 */
	private static $instance;

	/**
	 * @var $jobs_form_id
	 */
	private $jobs_form_id;

	/**
	 * @var $resumes_form_id
	 */
	private $resumes_form_id;

	/**
	 * Make sure only one instance is only running.
	 */
	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Start things up.
	 *
	 * @since WP Job Manager - Apply with Gravity Forms 1.0
	 */
	public function __construct() {
		$this->jobs_form_id    = get_option( 'job_manager_gravity_form' );
		$this->resumes_form_id = get_option( 'job_manager_gravity_form_resumes' );

		$this->setup_actions();
		$this->setup_globals();
		$this->load_textdomain();
	}

	/**
	 * Set some smart defaults to class variables. Allow some of them to be
	 * filtered to allow for early overriding.
	 *
	 * @since WP Job Manager - Apply with Gravity Forms 1.0
	 *
	 * @return void
	 */
	private function setup_globals() {
		$this->file         = __FILE__;

		$this->basename     = apply_filters( 'job_manager_gf_apply_plugin_basenname', plugin_basename( $this->file ) );
		$this->plugin_dir   = apply_filters( 'job_manager_gf_apply_plugin_dir_path',  plugin_dir_path( $this->file ) );
		$this->plugin_url   = apply_filters( 'job_manager_gf_apply_plugin_dir_url',   plugin_dir_url ( $this->file ) );

		$this->lang_dir     = apply_filters( 'job_manager_gf_apply_lang_dir',     trailingslashit( $this->plugin_dir . 'languages' ) );
		$this->domain       = 'job_manager_gf_apply';
	}

	/**
	 * Loads the plugin language files
	 *
 	 * @since WP Job Manager - Apply with Gravity Forms 1.0
	 */
	public function load_textdomain() {
		$locale        = apply_filters( 'plugin_locale', get_locale(), $this->domain );
		$mofile        = sprintf( '%1$s-%2$s.mo', $this->domain, $locale );

		$mofile_local  = $this->lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . $this->domain . '/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			return load_textdomain( $this->domain, $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			return load_textdomain( $this->domain, $mofile_local );
		}

		return false;
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since WP Job Manager - Apply with Gravity Forms 1.0
	 *
	 * @return void
	 */
	private function setup_actions() {
		add_filter( 'job_manager_settings', array( $this, 'job_manager_settings' ) );

		add_filter( 'gform_notification_' . $this->jobs_form_id, array( $this, 'notification_email' ), 10, 3 );
		add_filter( 'gform_notification_' . $this->resumes_form_id, array( $this, 'notification_email' ), 10, 3 );
	}

	/**
	 * Add a setting in the admin panel to enter the ID of the Gravity Form to use.
	 *
	 * @since WP Job Manager - Apply with Gravity Forms 1.0
	 *
	 * @param array $settings
	 * @return array $settings
	 */
	public function job_manager_settings( $settings ) {
		$settings[ 'job_listings' ][1][] = array(
			'name'       => 'job_manager_gravity_form',
			'std'        => null,
			'label'      => __( 'Jobs Gravity Form ID', 'job_manager_gf_apply' ),
			'desc'       => __( 'The ID of the Gravity Form you created for contacting employers.', 'job_manager_gf_apply' ),
			'attributes' => array()
		);

		if ( class_exists( 'WP_Resume_Manager' ) ) {
			$settings[ 'job_listings' ][1][] = array(
				'name'       => 'job_manager_gravity_form_resumes',
				'std'        => null,
				'label'      => __( 'Resumes Gravity Form ID', 'job_manager_gf_apply' ),
				'desc'       => __( 'The ID of the Gravity Form you created for contacting employees.', 'job_manager_gf_apply' ),
				'attributes' => array()
			);
		}

		return $settings;
	}

	/**
	 * Set the notification email when sending an email.
	 *
	 * @since WP Job Manager - Apply with Gravity Forms 1.0
	 *
	 * @return string The email to notify.
	 */
	public function notification_email( $notification, $form, $entry ) {
		if ( ! is_singular( array( 'resume', 'job_listing' ) ) ) {
			return $notification;
		}

		global $post;

		$notification[ 'toType' ] = 'email';

		if ( $form->ID == $this->jobs_form_id ) {
			$notification[ 'to' ]     = $post->_application;
		} else {
			$notification[ 'to' ]     = $post->_candidate_email;
		}

		return $notification;
	}
}
add_action( 'init', array( 'Astoundify_Job_Manager_Apply_GF', 'instance' ) );