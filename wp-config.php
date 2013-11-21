<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'havee');

/** MySQL database username */
define('DB_USER', 'wiet');

/** MySQL database password */
define('DB_PASSWORD', '1234');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'mt1$cwX!Pi^Q$tAlzyQwU9=(DL<7(8lGcS<RMZ(<i_=SQRiI.:(2]OFtj36WMy@W');
define('SECURE_AUTH_KEY',  '.xQE-4)+Cx61gaZ@zWJjbVh`?0|2,i7].sj20s[FEd`}7!v0V4;>$N_p&#,pu2xC');
define('LOGGED_IN_KEY',    'JYEE~<CA$h.%ji?z%jVcnI1!7mK<:[2|TaTZhGP%s0V@~n7dU=cnCkn SLjU)*Ui');
define('NONCE_KEY',        'GJlO)HM&xHMtTBPP}i7oL-G`n?T}}GU4{&GKr|S_|BN{)h0;]2: Y4izNu 49Etg');
define('AUTH_SALT',        '7fz?? 9UkhIxlc()9HLk:kK$y}>T+q]_+v~af^fn,ZDfjXH7@]b4}D5}.F_e)]Ly');
define('SECURE_AUTH_SALT', 'e2Tz1Rt,q=?filR:B;4(L,Q2eI/|:p(=,5oUGoQWfEx.Ph3;I;55K/-}0Lxtj6,2');
define('LOGGED_IN_SALT',   '-KvMyuNJ[H@f/(F=)C`UaBhT9EN>EpiO8 &%nncX:3_Gl`)FUA;rt<|yncN$q>l/');
define('NONCE_SALT',       '<]x)V_]Z|q0G}3C.n[4-q{m,cK+?X>-8I02HlEC5ZiJv25B+c]>NS! O<PE<7o43');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'nl_NL');

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
