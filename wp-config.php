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
define('DB_NAME', 'akyodo_dev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1234');

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
define('AUTH_KEY',         'H_FZ(aO:(=C+#7mFjz?4H#7@<.uSNEo53@zv O=5ClgtO2Bh8k>K@E?-tIRmoW S');
define('SECURE_AUTH_KEY',  'OKBaJxOzu/+Dg=QCdj<}njW?1#s5y`&~P#z0q6 `LTib0f}.WLCERnu3a}E=gMMD');
define('LOGGED_IN_KEY',    'u#8.4+ nod>Al&!@^p#zaUHRUYIT;?!3:l3qh&9-Fszl9`?xSd:k>g,#A92 :g[w');
define('NONCE_KEY',        'tlbzr *l[euUAI4K`R)dG.=Y~>{mS;yk*Q@@Q}AavLuyT(!0% ymn=K(QeTP6{)b');
define('AUTH_SALT',        'p *d%}p=EpL4)}?UhCmGbuttLOSqu_dh5<@;VlMk-%jCSmfQFT3VJL2oNG9CYDHF');
define('SECURE_AUTH_SALT', 'W4XCI49 K^RGG>m*BL<,[J/Z[,B*Be0{oY)G,:`4Z,WI<>aQ-s<;iX&{i0jYZ})m');
define('LOGGED_IN_SALT',   'xzc 6!<?mo7#jLe<+ZGdGe-+HYM6<op>uS,=|~b9SWH#/l*CC[p?n[EB+TkH(mW&');
define('NONCE_SALT',       'i q:?g*Ee>YKz|iF=ss~!F@<|ay:rwl:HG,f&u>y,MErqnM?G*tw*?q%FsFIfgJo');

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

define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
// define('DOMAIN_CURRENT_SITE', 'akw.angelkyodowilliams.com');
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/akyodo');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
