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
define( 'DB_NAME', 'cmb_db' );

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
define( 'AUTH_KEY',         '@1&vyf@b_2mS-KKKni1~0~;@+wj,ujr25F>Db_<;slo:Txzo!7FiJDn|FcAF<e#B' );
define( 'SECURE_AUTH_KEY',  '/r3fWgbbXw5Tj}p){gkHCt=5aakX{<?}[#sUl35F/MQE@&`#2lhjTVHX_h9Up%0V' );
define( 'LOGGED_IN_KEY',    ',9#6m xul[b}[-anTVC8tqBGE*/L{+o}?zp)WLzo6Z7sq2J/oSTvj-0tP8w!!*c8' );
define( 'NONCE_KEY',        's`eoFsm/LWFG5U|aGW/S7T63eed#00HDOU=fO8jsM;*;l#5.jKA,5|W3 %r4}lP}' );
define( 'AUTH_SALT',        '^6o]%c//);FW)`+:u5ynl+}oq2=L((5iE;z8UE;Jw6&/;*dFYSC](F6m;3*,DY=U' );
define( 'SECURE_AUTH_SALT', 'b!b<kC#(T:%TsyJp ZyNRWr$FGDNmO?jrLx|+@q1hW{Zt2K)R%1ZxKQT3-&R-V>q' );
define( 'LOGGED_IN_SALT',   '}Xe-Q*8xr0k< $W=<-BsNba8]ag?$i;S8&Vtz$vo8Glz30yK@8!7D@M5v=4(7fZ{' );
define( 'NONCE_SALT',       'N.0q*#QBL@m#%cG-iH[KsK/>86q*!aZLuz;!/lhNvd?K`03Z.^h^iMiYeetl?PlU' );

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
