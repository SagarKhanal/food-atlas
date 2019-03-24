<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'recipe');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'rm$Adp]eBUh>>8&FF.apa`lIg~V^%=+LonSPwQCt./3zQw;pd!a.J}TX(r)4hwub');
define('SECURE_AUTH_KEY',  'IQ{$K8dYA!x=stU)IH:h(%O{tRs|M#CQ1c0-_y4-orwR[JMaY0 X>Ng3m:*5;H~u');
define('LOGGED_IN_KEY',    'F@f6lm/s_oldXO*&:kp)mhyrsPB!4[RSnQC&=`5R/r$07LX+K<XQ-)w(n:if!W=l');
define('NONCE_KEY',        'LMFo|,f7JIiAlN6go<hai9}E+UA,j=~itZ:oQ9sb8ptK,bp`]v-JS$Hd#l`qVN#5');
define('AUTH_SALT',        'r#zur]$y@xFQvL%6QH+?(%S<@Nn9&fak}5ejI?vc&bJG7%6|N|^1{03-=Td49C-a');
define('SECURE_AUTH_SALT', 'CwP%(rHz6m::_55f-yT+wL@,y+*<pal&mx2Ua+(% :j((VJS$e*vNE Aal1L{{@;');
define('LOGGED_IN_SALT',   '*v8=G,[Q:6D*}TLNJc^L@X=4(VuwT*lj(=0tGbr74[.Nl[T$Vlh)+6[@HRWZZ)wN');
define('NONCE_SALT',       'sP</T7tka&ypkO1^%pEp(=H]H|l7?.;n9o1HVY,33A3F1QennI]R}e#ua[[th(6/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
