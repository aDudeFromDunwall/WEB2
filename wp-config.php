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
define( 'DB_NAME', 'wordpress2021_nadeauthierry' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'mysql' );

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
define( 'AUTH_KEY',         'NCBH<),h|T?Yun3*ogmosY, P2+YMDNo?`,D_Do?y#URReL Tcw8R!UAgidg=rW}' );
define( 'SECURE_AUTH_KEY',  '9+w-yX=m)%;(;(F?L?#a*<aRX RO2U /pqdWqbwU[l$6@ TAu5AqX[V+myy[ZEqT' );
define( 'LOGGED_IN_KEY',    'r^8*f _g,ev)?faT>IekyFv9a3H2m{j;e,W</u:0A,lQTHT!efl77(%0|70wA[kH' );
define( 'NONCE_KEY',        '&Hx/v#NA0jxQ@/P6Pq7mg*wMU{g4RIyi:xQ0NIF>lKB,FO*2z*^sZkBudh.wyBK?' );
define( 'AUTH_SALT',        'L:GOp:.qHAfUneJ?|aH8/gC$~c>A.T1q1jRh.r3.|ciZr2AzP%8Tb>hz0O[n(y?2' );
define( 'SECURE_AUTH_SALT', 'ZJJ9Who)zISHbPsL0C=IrIWh=%o{4?D(]7S];KmpI_EY})NW){[XfuKm{jX`E)RK' );
define( 'LOGGED_IN_SALT',   '8U|/:.}4~C$I@rWM&rOD-n#:{n2d6}=e-m[OB]IH;1|1A@SBCxWo@m(G6^&k6|yn' );
define( 'NONCE_SALT',       'XT]eMFC IhL%sCjii(aM}`2^r*&9cq9TygFK];x:A7<N9zavO}fr<;iG[pl8KNEb' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bruh_';

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
// Active (true) ou désactive (false) le mode débogage
define( 'WP_DEBUG', true );
 
// Pendant le débogage, lorsqu'une erreur est rencontrée, n'affiche pas de message d'erreur.
// Plutôt, WordPress enverra un codes d'état HTTP 500 au navigateur.
define( 'WP_DEBUG_DISPLAY', false );
 
// Pendant le débogage, WordPress enregistrera les messages d'erreurs dans le fichier wp-content\debug.log.
define( 'WP_DEBUG_LOG', true );
 
// En production (lorsque WP_DEBUG est à false), n'affiche pas les erreurs à l'écran.
@ini_set( 'display_errors', '0' );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
