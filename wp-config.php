<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'diablot');
//define('DB_NAME', 'u779076485_test');
/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');
//define('DB_USER', 'u779076485_test');
/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');
//define('DB_PASSWORD', '9GlZTIJ8YR');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');
//define('DB_HOST', 'mysql.hostinger.fr');
/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'ka0Bl(Fa,qug3^({+*C+Os+Aam{=,}F6 hV;,PV7RI&gp1{4t2PNo7]YmweFaQ@c');
define('SECURE_AUTH_KEY',  '|LG#S;iK=KNwdDwDsY4,|x3`*^|7XNu<g=@U~@r%1XvKci% S88`]QVdl!py,x|0');
define('LOGGED_IN_KEY',    '`$N9FcE_,=md^f/WY:GbIm3V3m2EeH^6RP<bj6{pfa{SpL$X9tYo^kUv4}V$U+is');
define('NONCE_KEY',        'Mxc,M/e-&A;qGe>}2[RkOJK4T1;ha-SK-.+uFm8[<{t{{!%b+5-U5#Ja>c>,:Ax8');
define('AUTH_SALT',        't*vN|hJR8(<ff~G]],e@<A^{Shn#m?ML#W&gLAQbD C_ooS~W@5}) lH|ziIDRu$');
define('SECURE_AUTH_SALT', 'olR)-OJs9(D|gM*-A-PvvnZUT|KAqbQ;,X^uxiC$FeGZEAF7fDTs0jgiy-z_f%NQ');
define('LOGGED_IN_SALT',   '} N{nCBr=zEQCBy^>QOBcrnVa_d+_a5~-lVb4Fh>|=6q$*ZzxfI%39E7_Ei-)EsR');
define('NONCE_SALT',       'm0BXB(KIy{]{jV=ZWz4T^u_6iTBON?}=U+>c|iDa{|Dz!Vw/.|?|2QM&i:;TA&#E');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');