<?php
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file viene utilizzato, durante l’installazione, dallo script
 * di creazione di wp-config.php. Non è necessario utilizzarlo solo via web
 * puoi copiare questo file in «wp-config.php» e riempire i valori corretti.
 *
 * Questo file definisce le seguenti configurazioni:
 *
 * * Impostazioni MySQL
 * * Chiavi Segrete
 * * Prefisso Tabella
 * * ABSPATH
 *
 * * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Impostazioni MySQL - È possibile ottenere queste informazioni dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define( 'DB_NAME', 'xlthlxcotbsmarz' );

/** Nome utente del database MySQL */
define( 'DB_USER', 'xlthlxcotbsmarz' );

/** Password del database MySQL */
define( 'DB_PASSWORD', '2dyrSqGVVtUXmXAT' );

/** Hostname MySQL  */
define( 'DB_HOST', 'xlthlxcotbsmarz.mysql.db' );

/** Charset del Database da utilizzare nella creazione delle tabelle. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Il tipo di Collazione del Database. Da non modificare se non si ha idea di cosa sia. */
define('DB_COLLATE', '');

/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * È possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 * È possibile cambiare queste chiavi in qualsiasi momento, per invalidare tuttii cookie esistenti. Ciò forzerà tutti gli utenti ad effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '}ho?aH7/QN)kkg<gUr4YPbo4@zA>XhRz90h<d-_%A&&:qD!Xi(^fR7ZB/jI3=cbY' );
define( 'SECURE_AUTH_KEY',  'HqF{|oV.r]Q^>R+E1-YId83b&$G(xGC;/mZ3CtqF*=X/7y1m.8pvj<BWT(tWgeq%' );
define( 'LOGGED_IN_KEY',    'KM_P7j!;E; sPVv@O2()&(`$rcx_*$%2]S%v[<)m[tm%m0zV`RL(u5_F~B*.;:~m' );
define( 'NONCE_KEY',        'v^NR=5;Ky7B[2|!{&y7.IX}(XJiRCc*;&G6YElpS:Hn^@uDcli/<0%hh(Xj7IS_7' );
define( 'AUTH_SALT',        'pg1KxsO}Ib#w%!QA$^kHS?Yt?2qp)LeTi9Eb0_<=Q?.Vx2-/V$?q/5/<717n8`^0' );
define( 'SECURE_AUTH_SALT', 'y=rR7dI1Msr<j=}[fV!^ril8?Do]MYyy}}O~j|%BHllFehyoqK(>XU{OPE$)p K!' );
define( 'LOGGED_IN_SALT',   '5-}UhDk~w1Q9l}vFH/L0f+TM11Pw=<DTk(RnQ2Y_r<UvH2XrR|BqdTilx?]E)l9t' );
define( 'NONCE_SALT',       '?UFov3k$6&:un&=O)xM dezZ=Ok[axY6]Y;el8A8a%f^i,1gj6l.q^=U|`HA>_K,' );

/**#@-*/

/**
 * Prefisso Tabella del Database WordPress.
 *
 * È possibile avere installazioni multiple su di un unico database
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix = 'sl_';

/**
 * Per gli sviluppatori: modalità di debug di WordPress.
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi durante lo sviluppo
 * È fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all’interno dei loro ambienti di sviluppo.
 *
 * Per informazioni sulle altre costanti che possono essere utilizzate per il debug,
 * leggi la documentazione
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', false );
@ini_set( 'display_errors', 0 );

/* Finito, interrompere le modifiche! Buon blogging. */

/** Path assoluto alla directory di WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');

/** Imposta le variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');
