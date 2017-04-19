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
define('DB_NAME', 'rpandya1_wrdp1');

/** MySQL database username */
define('DB_USER', 'rpandya1_wrdp1');

/** MySQL database password */
define('DB_PASSWORD', 'GRj63ksUCRRFwyX');

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
define('AUTH_KEY',         'PS?-/7/])So9L8yPUV*/2O0c)xBh1WDtV]3sj:<iE?hBD~2j^NF^BM/3f0f8RYo~');
define('SECURE_AUTH_KEY',  'ER,G,b=K}8y 0T[pO G&yh_yT?yh?j0c*v?IeaS}vQL8DH/yQB<2}~(I:PS*bGvl');
define('LOGGED_IN_KEY',    '$s&x0f7;zEcfipk6OMXd!T[Q@nVd7DAdl;SI<96!gu5%{V+^]X2&XpnWie^5~TYE');
define('NONCE_KEY',        '4RudI]bKXem;tav#p ,fl#`HHFj7x3_&%rm#0cR-eI}j$pc=ah1@o!5=w&2Ry04M');
define('AUTH_SALT',        '9HB9;!jxbXr}z-[@pbw[}.-SP>moqyK^TNtJD/@V#I03LrZ(-yM4^toT(0dBYbrl');
define('SECURE_AUTH_SALT', 'DCP V2%; O,0ru)_^T6)_W&$D^fa]h9v#17b%1fnp`, &^Rs .3$65!Wu&/^;(c2');
define('LOGGED_IN_SALT',   '`]u|c[m!3sX4zgsj=6huAY?0qLhbE@db]+dImp`Lh3>8A{XJo$t:`D/}b5dcthwD');
define('NONCE_SALT',       '@dYmjLMcBg92YdC*u9J.wul2[|}1pJ^z 58uARSadk]x}EF(Da|`wcK);=QZgcyK');

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


