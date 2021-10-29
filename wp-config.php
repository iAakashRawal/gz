<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gamezone' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'd;nfqen>p.Z8]*i$M)]c`!uQOc;1P=),] @M9M%]?I1o$XTZdu,c-RipR?)pJlzv' );
define( 'SECURE_AUTH_KEY',  'jQ(N?X$-|s>[Zn`bI3q~+xW&;C[vU[W.02i[_>%]PP&DNnNCE*j:4lvs@-sL<=`2' );
define( 'LOGGED_IN_KEY',    'vcN1fB|cw3q6d#-AwD85+Tu?RqaTRP,PoepVES8P/vS8j (Y4nZSiJuO~`(^X!-G' );
define( 'NONCE_KEY',        'M;6~f|)?nz.(4i0:C5J?#d8e./o(RLh#CMW2Y0OZ3 yENhs@Fq|$BsrHS|gHJ|Cp' );
define( 'AUTH_SALT',        'gk-E9,_7@L!5W=M=k4`mv:;Mg{MwtBl>]r@rWdX>`9`gl lx,?Wa?L$!suE=v9.|' );
define( 'SECURE_AUTH_SALT', ']Qy k[2}DmuZtU]m5?_jO-JA_]<nXnH(n);Js;Q$HMt5,3*enp&5f_HOi*xB`wOm' );
define( 'LOGGED_IN_SALT',   '!a2ITT`^/V)y^t7/fHiap{{Q-A Kd,_L,l3tEW&i.D:Jg/V*C2pA>4x*m7y/AgyV' );
define( 'NONCE_SALT',       '%4-AO#)n)#+;{WWAOLLE<Hk20dE]Mf(djN58Q7DWc!x1*}kW$Lll(fd sVR?_rhA' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
