<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', '487ea9f423f6');

/** MySQL database password */
define('DB_PASSWORD', 'c97269291ba06d19');

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
define('AUTH_KEY',         '1B$^JZ#:|D^k1:Sa:|?q+5Dc.bYoB?8/8,48l=]lzGehUCnJ>8n-PoK3!f!Nq[Ro');
define('SECURE_AUTH_KEY',  '1waYj21@y[d|6Q)<:-M)7vxqaOB1/vc}I+CSO-n-=N^:jSpYMS6Stx! SkmqB6{=');
define('LOGGED_IN_KEY',    'G%{H-TJ6%Nx]NL;AtG-Xw-w`dllNcQ~o6+DHVa[-vh95@BGnFA=c,mUN<#G3At7U');
define('NONCE_KEY',        '|!B{SXxkAn>o+{{5mO36!jJTlIq~5B `6=(t*$c$96G&e09ErX=(tkxcbIQB4)|f');
define('AUTH_SALT',        'Fwp3[fvu-+vjFqM o4FhTC!mGr2%ACGc2F(6n4ZtAnhW$|qL??V{w]lS.,D}EN$_');
define('SECURE_AUTH_SALT', 'mhkE#w;Ax1_jp@i_<}34TO$A3HU@J:;uV5(O*;Ap`_y=+t*G6HrMam$WO]s)BH[c');
define('LOGGED_IN_SALT',   'f ZM9m`8er8:S;fWBQ:<g+-wAu i@bA+n~XW#8uL>.=ssx_@Sl&|M@G[RmHsI:sr');
define('NONCE_SALT',       '5B#hzo2N%}3c(9(e[:(F=roDhk?|-Zcj!9pIot`F[LZDsqa>CtfB.iQ@xs16at-8');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
