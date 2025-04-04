<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wplearn_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'o +jlUuVU?P47E`WZseB*5`tcF4zx.ZR*l: &iH %]LlQCoMo;`N?z[fK3fj4HJv' );
define( 'SECURE_AUTH_KEY',  ':<KzXXrCQ^oR4d7a7o{TTjMO ]H=d&zqSvs}Z7<H8sy/*ca_adSI53,X# 5i>,${' );
define( 'LOGGED_IN_KEY',    ';eHX >>pDv`Kja9?ZW5JeYl%u:]HkOrf=TO@=PnSrhky4#DY2Wat!ts53-_t.k0=' );
define( 'NONCE_KEY',        ' ;ZkFv2X(WUEv# `|TQ{U1#~L:kYBEp`jfo[B3=geUx#=+2(T@5e&$^u6_~F/V~)' );
define( 'AUTH_SALT',        'LncQ%SF;0C{c?XX5/oX85,5&*G=d8-2$a|BGdhu7Y=e w+#.GG<cTm](M7q[6onT' );
define( 'SECURE_AUTH_SALT', 'Bcf1aplZb7ib|~IJ]N ZkS>O_V6msC:~eW@8}=&Dv!i&u,E_z{dm|K&uxG7S>wq9' );
define( 'LOGGED_IN_SALT',   '!DD=~_f,]4*oAtGl[HJ0_GX+&92(E,q4*Q>w1@r>CEET)N~fV<)`GhEt|+)Zz-<k' );
define( 'NONCE_SALT',       '*3;[14-#67=9<lJB4)O3knuzDNWQz#j@S41jBgXpWcV2+i!v<<8ne*fyxBBv(`-1' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
