<?php
/**
 * Class WordPress\Plugin_Check\Checker\Abstract_Check_runner
 *
 * @package plugin-check
 */

namespace WordPress\Plugin_Check\Checker;

use Exception;
use WordPress\Plugin_Check\Checker\Exception\Invalid_Check_Slug_Exception;
use WordPress\Plugin_Check\Checker\Preparations\Universal_Runtime_Preparation;
use WordPress\Plugin_Check\Utilities\Plugin_Request_Utility;

/**
 * Abstract Check Runner class.
 *
 * @since 1.0.0
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class Abstract_Check_Runner implements Check_Runner {

	/**
	 * True if the class was initialized early in the WordPress load process.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $initialized_early;

	/**
	 * The check slugs to run.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $check_slugs;

	/**
	 * The plugin slug.
	 *
	 * @since 1.2.0
	 * @var string
	 */
	protected $slug;

	/**
	 * The check slugs to exclude.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $check_exclude_slugs;

	/**
	 * The plugin parameter.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin;

	/**
	 * An instance of the Checks class.
	 *
	 * @since 1.0.0
	 * @var Checks
	 */
	protected $checks;

	/**
	 * The plugin basename to check.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_basename;

	/**
	 * Whether to delete the plugin folder during cleanup.
	 *
	 * Used when downloading a plugin from a URL.
	 *
	 * @since 1.1.0
	 * @var bool
	 */
	private $delete_plugin_folder = false;

	/**
	 * An instance of the Check_Repository.
	 *
	 * @since 1.0.0
	 * @var Check_Repository
	 */
	private $check_repository;

	/**
	 * Runtime environment.
	 *
	 * @since 1.0.0
	 * @var Runtime_Environment_Setup
	 */
	protected $runtime_environment;

	/**
	 * Whether to include experimental checks.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $include_experimental;

	/**
	 * Checks category for the filter.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $check_categories;

	/**
	 * Returns the plugin parameter based on the request.
	 *
	 * @since 1.0.0
	 *
	 * @return string The plugin parameter from the request.
	 */
	abstract protected function get_plugin_param();

	/**
	 * Returns an array of Check slugs to run based on the request.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of Check slugs.
	 */
	abstract protected function get_check_slugs_param();

	/**
	 * Returns an array of Check slugs to exclude based on the request.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of Check slugs.
	 */
	abstract protected function get_check_exclude_slugs_param();

	/**
	 * Returns the include experimental parameter based on the request.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Returns true to include experimental checks else false.
	 */
	abstract protected function get_include_experimental_param();

	/**
	 * Returns an array of categories for filtering the checks.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of categories.
	 */
	abstract protected function get_categories_param();

	/**
	 * Returns plugin slug parameter.
	 *
	 * @since 1.2.0
	 *
	 * @return string Plugin slug.
	 */
	abstract protected function get_slug_param();

	/**
	 * Sets whether the runner class was initialized early.
	 *
	 * @since 1.0.0
	 */
	final public function __construct() {
		$this->initialized_early   = ! did_action( 'muplugins_loaded' );
		$this->check_repository    = new Default_Check_Repository();
		$this->runtime_environment = new Runtime_Environment_Setup();
	}

	/**
	 * Sets the check slugs to be run.
	 *
	 * @since 1.0.0
	 *
	 * @param array $check_slugs An array of check slugs to be run.
	 *
	 * @throws Exception Thrown if the checks do not match those in the original request.
	 */
	final public function set_check_slugs( array $check_slugs ) {
		if ( $this->initialized_early ) {
			// Compare the check slugs to see if there was an error.
			if ( $check_slugs !== $this->get_check_slugs_param() ) {
				throw new Exception(
					__( 'Invalid checks: The checks to run do not match the original request.', 'plugin-check' )
				);
			}
		}

		$this->check_slugs = $check_slugs;
	}

	/**
	 * Sets the check slugs to be excluded.
	 *
	 * @since 1.0.0
	 *
	 * @param array $check_slugs An array of check slugs to be excluded.
	 *
	 * @throws Exception Thrown if the checks do not match those in the original request.
	 */
	final public function set_check_exclude_slugs( array $check_slugs ) {
		if ( $this->initialized_early ) {
			// Compare the check slugs to see if there was an error.
			if ( $check_slugs !== $this->get_check_exclude_slugs_param() ) {
				throw new Exception(
					__( 'Invalid checks: The checks to exclude do not match the original request.', 'plugin-check' )
				);
			}
		}

		$this->check_exclude_slugs = $check_slugs;
	}

	/**
	 * Sets the plugin slug or basename to be checked.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin The plugin slug or basename to be checked.
	 *
	 * @throws Exception Thrown if the plugin set does not match the original request parameter.
	 */
	final public function set_plugin( $plugin ) {
		if ( $this->initialized_early ) {
			// Compare the plugin parameter to see if there was an error.
			if ( $plugin !== $this->get_plugin_param() ) {
				throw new Exception(
					__( 'Invalid plugin: The plugin set does not match the original request parameter.', 'plugin-check' )
				);
			}
		}

		$this->plugin = $plugin;
	}

	/**
	 * Sets whether to include experimental checks in the process.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $include_experimental True to include experimental checks. False to exclude.
	 *
	 * @throws Exception Thrown if the flag set does not match the original request parameter.
	 */
	final public function set_experimental_flag( $include_experimental ) {
		if ( $this->initialized_early ) {
			if ( $include_experimental !== $this->get_include_experimental_param() ) {
				throw new Exception(
					sprintf(
						/* translators: %s: include-experimental */
						__( 'Invalid flag: The %s value does not match the original request parameter.', 'plugin-check' ),
						'include-experimental'
					)
				);
			}
		}

		$this->include_experimental = $include_experimental;
	}

	/**
	 * Sets categories for filtering the checks.
	 *
	 * @since 1.0.0
	 *
	 * @param array $categories An array of categories for filtering.
	 *
	 * @throws Exception Thrown if the categories does not match the original request parameter.
	 */
	final public function set_categories( $categories ) {
		if ( $this->initialized_early ) {
			if ( $categories !== $this->get_categories_param() ) {
				throw new Exception(
					sprintf(
						/* translators: %s: categories */
						__( 'Invalid categories: The %s value does not match the original request parameter.', 'plugin-check' ),
						'categories'
					)
				);
			}
		}
		$this->check_categories = $categories;
	}

	/**
	 * Prepares the environment for running the requested checks.
	 *
	 * @since 1.0.0
	 *
	 * @return callable Cleanup function to revert any changes made here.
	 *
	 * @throws Exception Thrown exception when preparation fails.
	 */
	final public function prepare() {
		if ( $this->initialized_early ) {
			/*
			 * When initialized early, plugins are not loaded yet when this method is called.
			 * Therefore it could be that check slugs provided refer to addon checks that are not loaded yet.
			 * In that case, the only reliable option is to assume that it refers to an addon check and that the addon
			 * check is a runtime check. We don't know, but better to have the runtime preparations initialize
			 * unnecessarily rather than not having them when needed.
			 *
			 * The actual checks to run are retrieved later (once plugins are loaded), so if one of the provided slugs
			 * is actually invalid, the exception will still be thrown at that point.
			 */
			try {
				$checks             = $this->get_checks_to_run();
				$initialize_runtime = $this->has_runtime_check( $checks );
			} catch ( Invalid_Check_Slug_Exception $e ) {
				$initialize_runtime = true;
			}
		} else {
			// When not initialized early, all checks are loaded, so we can simply see if there are runtime checks.
			$initialize_runtime = $this->has_runtime_check( $this->get_checks_to_run() );
		}

		$cleanup_functions = array();
		if ( $initialize_runtime ) {
			$cleanup_functions = $this->initialize_runtime();
		}

		if ( $this->delete_plugin_folder ) {
			$cleanup_functions = function () {
				// It must be a directory at this point, but double check just in case.
				if ( is_dir( $this->plugin_basename ) ) {
					rmdir( $this->plugin_basename );
				}
			};
		}

		return function () use ( $cleanup_functions ) {
			foreach ( $cleanup_functions as $cleanup_function ) {
				$cleanup_function();
			}
		};
	}

	/**
	 * Runs the checks against the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Check_Result An object containing all check results.
	 */
	final public function run() {
		$checks       = $this->get_checks_to_run();
		$preparations = $this->get_shared_preparations( $checks );
		$cleanups     = array();

		// Prepare all shared preparations.
		foreach ( $preparations as $preparation ) {
			$instance   = new $preparation['class']( ...$preparation['args'] );
			$cleanups[] = $instance->prepare();
		}

		$results = $this->get_checks_instance()->run_checks( $this->get_check_context(), $checks );

		if ( ! empty( $cleanups ) ) {
			foreach ( $cleanups as $cleanup ) {
				$cleanup();
			}
		}

		return $results;
	}

	/**
	 * Determines if any of the checks are a runtime check.
	 *
	 * @since 1.0.0
	 *
	 * @param array $checks An array of check instances to run.
	 * @return bool Returns true if one or more checks is a runtime check.
	 */
	private function has_runtime_check( array $checks ) {
		foreach ( $checks as $check ) {
			if ( $check instanceof Runtime_Check ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns all shared preparations used by the checks to run.
	 *
	 * @since 1.0.0
	 *
	 * @param array $checks An array of Check instances to run.
	 * @return array An array of Preparations to run where each item is an array with keys `class` and `args`.
	 */
	private function get_shared_preparations( array $checks ) {
		$shared_preparations = array();

		foreach ( $checks as $check ) {
			if ( ! $check instanceof With_Shared_Preparations ) {
				continue;
			}

			$preparations = $check->get_shared_preparations();

			foreach ( $preparations as $class => $args ) {
				$key = $class . '::' . md5( json_encode( $args ) );

				if ( ! isset( $shared_preparations[ $key ] ) ) {
					$shared_preparations[ $key ] = array(
						'class' => $class,
						'args'  => $args,
					);
				}
			}
		}

		return array_values( $shared_preparations );
	}

	/**
	 * Returns the Check instances to run.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array map of check slugs to Check instances.
	 *
	 * @throws Exception Thrown when invalid flag is passed, or Check slug does not exist.
	 */
	final public function get_checks_to_run() {
		$check_slugs = $this->get_check_slugs();
		$check_flags = Check_Repository::TYPE_STATIC;

		// Check if conditions are met in order to perform Runtime Checks.
		if ( $this->allow_runtime_checks() ) {
			$check_flags = Check_Repository::TYPE_ALL;
		}

		// Check whether to include experimental checks.
		if ( $this->get_include_experimental() ) {
			$check_flags = $check_flags | Check_Repository::INCLUDE_EXPERIMENTAL;
		}

		$excluded_checks = $this->get_check_exclude_slugs();

		$collection = $this->check_repository->get_checks( $check_flags )
			->require( $check_slugs ) // Ensures all of the given slugs are valid.
			->include( $check_slugs ) // Ensures only the checks with the given slugs are included.
			->exclude( $excluded_checks ); // Exclude provided checks from list.

		// Filters the checks by specific categories.
		$categories = $this->get_categories();
		if ( $categories ) {
			$collection = Check_Categories::filter_checks_by_categories( $collection, $categories );
		}

		return $collection->to_map();
	}

	/**
	 * Initializes the runtime environment so that runtime checks can be run against a separate set of database tables.
	 *
	 * @since 1.3.0
	 *
	 * @return callable[] Array of cleanup functions to run after the process has completed.
	 */
	protected function initialize_runtime(): array {
		$preparation = new Universal_Runtime_Preparation( $this->get_check_context() );
		return array( $preparation->prepare() );
	}

	/**
	 * Checks whether the current environment allows for runtime checks to be used.
	 *
	 * @since 1.2.0
	 *
	 * @return bool True if runtime checks are allowed, false otherwise.
	 */
	protected function allow_runtime_checks(): bool {
		// Ensure that is_plugin_active() is available.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return ( $this->initialized_early || $this->runtime_environment->can_set_up() )
			&& is_plugin_active( $this->get_plugin_basename() );
	}

	/**
	 * Creates and returns the Check instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Checks An instance of the Checks class.
	 *
	 * @throws Exception Thrown if the plugin slug is invalid.
	 */
	protected function get_checks_instance() {
		if ( null !== $this->checks ) {
			return $this->checks;
		}

		$this->checks = new Checks();

		return $this->checks;
	}

	/**
	 * Returns the check slugs to run.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of check slugs to run.
	 */
	private function get_check_slugs() {
		if ( null !== $this->check_slugs ) {
			return $this->check_slugs;
		}

		return $this->get_check_slugs_param();
	}

	/**
	 * Returns the check slugs to exclude.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of check slugs to exclude.
	 */
	private function get_check_exclude_slugs() {
		if ( null !== $this->check_exclude_slugs ) {
			return $this->check_exclude_slugs;
		}

		return $this->get_check_exclude_slugs_param();
	}

	/**
	 * Returns the plugin basename.
	 *
	 * @since 1.0.0
	 *
	 * @return string The plugin basename to check.
	 */
	final public function get_plugin_basename() {
		if ( null === $this->plugin_basename ) {
			$plugin = null !== $this->plugin ? $this->plugin : $this->get_plugin_param();

			if ( filter_var( $plugin, FILTER_VALIDATE_URL ) ) {
				$this->plugin_basename = Plugin_Request_Utility::download_plugin( $plugin );

				$this->delete_plugin_folder = true;
			} elseif ( Plugin_Request_Utility::is_directory_valid_plugin( $plugin ) ) {
				$this->plugin_basename = $plugin;
			} else {
				$this->plugin_basename = Plugin_Request_Utility::get_plugin_basename_from_input( $plugin );
			}
		}

		return $this->plugin_basename;
	}

	/**
	 * Returns the value for the include experimental flag.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if experimental checks are included. False if not.
	 */
	final protected function get_include_experimental() {
		if ( null !== $this->include_experimental ) {
			return $this->include_experimental;
		}

		return $this->get_include_experimental_param();
	}

	/**
	 * Returns an array of categories for filtering the checks.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of categories.
	 */
	final protected function get_categories() {
		if ( null !== $this->check_categories ) {
			return $this->check_categories;
		}

		return $this->get_categories_param();
	}

	/**
	 * Returns plugin slug.
	 *
	 * @since 1.2.0
	 *
	 * @return string Plugin slug.
	 */
	final protected function get_slug() {
		if ( null !== $this->slug ) {
			return $this->slug;
		}

		return $this->get_slug_param();
	}

	/** Gets the Check_Context for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Check_Context The check context for the plugin file.
	 */
	private function get_check_context() {
		$plugin_basename = $this->get_plugin_basename();
		$plugin_path     = is_dir( $plugin_basename ) ? $plugin_basename : WP_PLUGIN_DIR . '/' . $plugin_basename;
		return new Check_Context( $plugin_path, $this->get_slug() );
	}

	/**
	 * Sets the plugin slug.
	 *
	 * @since 1.2.0
	 *
	 * @param string $slug Plugin slug.
	 */
	final public function set_slug( $slug ) {
		if ( ! empty( $slug ) ) {
			$this->slug = $slug;
		} else {
			$basename = $this->get_plugin_basename();

			$this->slug = ( '.' === pathinfo( $basename, PATHINFO_DIRNAME ) ) ? $basename : dirname( $basename );
		}
	}

	/**
	 * Sets the runtime environment setup.
	 *
	 * @since 1.0.0
	 *
	 * @param Runtime_Environment_Setup $runtime_environment_setup Runtime environment instance.
	 */
	final public function set_runtime_environment_setup( $runtime_environment_setup ) {
		$this->runtime_environment = $runtime_environment_setup;
	}
}
