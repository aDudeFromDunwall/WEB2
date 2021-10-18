<?php
/**
 * Fonction de rappel du hook after_setup_theme, exécutée après que le thème ait été initialisé
 *
 * Utilisation : add_action( 'after_setup_theme', 'thierry_apres_initialisation_theme' );
 *
 * @author Christiane Lagacé
 *
 */
function thierry_apres_initialisation_theme() {
    // Retirer la balise <meta name="generator">
    remove_action( 'wp_head', 'wp_generator' ); 
}
/**
 * Enregistre une information de débogage dans le fichier debug.log, seulement si WP_DEBUG est à true
 *
 * Utilisation : thierry_log_debug( 'test' );
 * Inspiré de http://wp.smashingmagazine.com/2011/03/08/ten-things-every-wordpress-plugin-developer-should-know/
 *
 * @author Christiane Lagacé <christianelagace.com>
 *
 */
function thierry_log_debug( $message ) {
   if ( WP_DEBUG === true ) {
       if ( is_array( $message ) || is_object( $message ) ) {
           error_log( 'Message de débogage: ' . print_r( $message, true ) );
       } else {
           error_log( 'Message de débogage: ' . $message );
       }
   }
}


/*Table Personnage:
id
Nom:
Date naissance:
Appartenance_id ->lien vers la table Appartenance
Surnom: (peut etre NULL)


Table Appartenance:
id:
nom:
NB de membre: (seulement pour organisation Yakuza Genre Tojo et la famille majima)
*/ 

/**
 * Crée les tables personnalisées pour mon thème enfant
 *
 * Utilisation : add_action( "after_switch_theme", "monprefixe_creer_tables" );
 */
add_action( "after_switch_theme", "thierry_creer_tables" );
function thierry_creer_tables() {
   global $wpdb;

   $charset_collate = $wpdb->get_charset_collate();

   $table_matable = $wpdb->prefix . 'bruh_personnage';
   $table_matable2 = $wpdb->prefix . 'bruh_appartenance';
   $table_matableexam=$wpdb->prefix . 'bruh_like';
   
   $sql = "CREATE TABLE $table_matable2 (
      id bigint(20) unsigned NOT NULL auto_increment,
      nom VARCHAR(30) NOT NULL,
      nbdemembre VARCHAR(50),
      PRIMARY KEY  (id)
  ) $charset_collate;";
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
   $sql = "CREATE TABLE $table_matable (
       id bigint(20) unsigned NOT NULL auto_increment,
       nom VARCHAR(30) NOT NULL,
       date_naissance DATE NOT NULL,
       appartenance_id bigint NOT NULL,
       surnom VARCHAR(50),
       PRIMARY KEY  (id)
   ) $charset_collate;";
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
   $sql="CREATE TABLE $table_matableexam (
       id bigint(20) unsigned NOT NULL auto_increment,
       usager_id bigint(20) NOT NULL,
       _date DATETIME NOT NULL,
       PRIMARY KEY(id)
    )$charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);




   $id = []; // pour stocker les identifiants au cas où ils devraient être utilisés plus loin
   $donnees = array(

    array( 1,'Clan Tojo', '25000 membres'),
    array( 2,'Civil', 'NF'),
    array( 3,'Famille Majima', 'plusieurs milliers d hommes'),
   );
   $donneesPerso=array(
      array(1,'Kazuma Kiryu','1968-6-17',2,'Le Dragon de Dojima'),
      array(2,'Makoto Date','1960-5-3',2,'NA'),
      array(3,'Goro Majima','1964-5-14',3,'Le Chien Fou de Shimano'),
      array(4,'Daigo Dojima','1976-8-2',1,'Le 6e Patriarche du Clan Tojo'),
   );
   $donneesExam=array(
       array(1,1,"2001-08-02 06:38:00"),
       array(2,1,"2004-08-24 00:12:36"),
       array(3,1,"1968-06-17 00:00:00"),
   );

   $requete = "SELECT COUNT(*) FROM $table_matable2";
   $presence_donnees = $wpdb->get_var( $requete );
 
   if ( is_null( $presence_donnees ) ) {
      $presence_donnees = 0;    // valeur si on ne réussissait pas à retrouver l'info dans la BD
   }
 
   if ( ! $presence_donnees ) {
      foreach( $donnees as $donnee ) {
         $reussite = $wpdb->insert(
             $table_matable2, /*Table Appartenance*/
             array(
                 'id' => $donnee[0],
                 'nom' => $donnee[1],
                 'nbdemembre' => $donnee[2],
               ),
             array(
                 '%d', /*Sa fait juste confirmer que tu insert un digit*/ 
                 '%s',/*Sa fait juste confirmer que tu insert un string*/
                 '%s'/*IDEM*/
               )
         );
         
         if ( ! $reussite ) {
             // réagir en cas de problème
             monprefixe_log_debug( $wpdb->last_error );
             // ...
         }
        }
   foreach ($donneesPerso as $donnee )
   {
      $reussite = $wpdb->insert(
         $table_matable,/*Table Perso*/
         array(
             'id' => $donnee[0],
             'nom' => $donnee[1],
             'date_naissance' => $donnee[2],
             'appartenance_id' => $donnee[3],
             'surnom' => $donnee[4], 
         ),
         array(
             '%d', /*Sa fait juste confirmer que tu insert un digit*/ 
             '%s',/*Sa fait juste confirmer que tu insert un string*/
             '%s',/*IDEM*/
             '%d',
             '%s'
         )
     );
     if ( ! $reussite ) {
      // réagir en cas de problème
      monprefixe_log_debug( $wpdb->last_error );
      // ...
    }
    foreach( $donneesExam as $donnee ) {
        $reussite = $wpdb->insert(
            $table_matableexam, /*Table examen*/
            array(
                'id' => $donnee[0],
                'usager_id' => $donnee[1],
                '_date' => $donnee[2],
              ),
            array(
                '%d', /*Sa fait juste confirmer que tu insert un digit*/ 
                '%d',/*Sa fait juste confirmer que tu insert un digit*/
                '%s',
              )
        );
        
        if ( ! $reussite ) {
            // réagir en cas de problème
            monprefixe_log_debug( $wpdb->last_error );
        }
   }
   }
   
}}

function thierry_sauce( ) {
   global $wpdb;
   $code_html = '';

   
   $requete = "SELECT bruh_bruh_personnage.nom, date_naissance,bruh_bruh_appartenance.nom AS nomAppartenance,surnom FROM bruh_bruh_personnage INNER JOIN
   bruh_bruh_appartenance ON bruh_bruh_personnage.appartenance_id = bruh_bruh_appartenance.id";
   $message_aucune_donnee = __( 'Aucune donnée ne correspond aux critères demandés.', 'mon-domaine-de-localisation' );
   $message_erreur_sql = __( 'Oups ! Un problème a été rencontré.', 'mon-domaine-de-localisation' );
   $resultat = $wpdb->get_results( $requete);
   $erreur_sql = $wpdb->last_error;

if ( $erreur_sql == "" ) {
    if ( $wpdb->num_rows > 0 ) {
        $code_html .= '<table class="...">';
            $code_html.="<tr>";
            $code_html.="<th>Nom</th>";
            $code_html.="<th>Date de naissance</th>";
            $code_html.="<th>Appartenance</th>";
            $code_html.="<th>Surnom</th>";
         foreach( $resultat as $enreg ) {
            $code_html .= "<tr>";
            $code_html .= "<td>$enreg->nom</td>";
            $code_html .= "<td>$enreg->date_naissance</td>";
            $code_html .= "<td>$enreg->nomAppartenance</td>";
            $code_html .= "<td>$enreg->surnom</td>";
            $code_html .= "</tr>";
        }
        $code_html .= '</table>';
    } else {
        $code_html .= '<div class="messageavertissement">';
        $code_html .= $message_aucune_donnee;
        $code_html .= '</div>';
    }
} else {
   $code_html .= '<div class="messageerreur">';
   $code_html .= $message_erreur_sql;
   $code_html .= '</div>';
 // écrit l'erreur dans le journal seulement si on est en mode débogage
    monprefixe_log_debug( $erreur_sql );
}
return $code_html;
}

function shortcutexam(){            //////////////////////////SHORTCODE EXAM
    global $wpdb;
   $code_html = '';


   $requete = "SELECT bruh_users.user_nicename,_date FROM bruh_bruh_like INNER JOIN bruh_users
   ON bruh_bruh_like.usager_id = bruh_users.id";
   $message_aucune_donnee = __( 'Aucune donnée ne correspond aux critères demandés.', 'mon-domaine-de-localisation' );
   $message_erreur_sql = __( 'Oups ! Un problème a été rencontré.', 'mon-domaine-de-localisation' );
   $resultat = $wpdb->get_results( $requete);
   $erreur_sql = $wpdb->last_error;

if ( $erreur_sql == "" ) {
    if ( $wpdb->num_rows > 0 ) {
        $code_html .= '<table class="...">';
            $code_html.="<tr>";
            $code_html.="<th>Nicename</th>";
            $code_html.="<th>Date de mention</th>";
            $code_html.="</tr>";
         foreach( $resultat as $enreg ) {
            $code_html .= "<tr>";
            $code_html .= "<td>$enreg->user_nicename</td>";
            $code_html .= "<td>$enreg->date</td>";
            $code_html .= "</tr>";
        }
        $code_html .= '</table>';
    } else {
        $code_html .= '<div class="messageavertissement">';
        $code_html .= $message_aucune_donnee;
        $code_html .= '</div>';
    }
} else {
   $code_html .= '<div class="messageerreur">';
   $code_html .= $message_erreur_sql;
   $code_html .= '</div>';
 // écrit l'erreur dans le journal seulement si on est en mode débogage
    monprefixe_log_debug( $erreur_sql );}
return $code_html;
}
add_shortcode( 'thierrysauce', 'thierry_sauce' ); 

add_shortcode('shortcutexam','blahblah');//le premier c'est le nom de la fonction que tu appelle.
//le 2e c'est les caractères que tu dois mettre en [] pour faire le shortcut
function thierry_ajuster_head() {
    echo '<link rel="icon" href="' . get_site_url() . '/favicon.ico" />';
    echo '<link rel="icon" type="image/png" sizes="32x32" href="' . get_site_url() . '/favicon-32x32.png">';
    echo '<link rel="icon" type="image/png" sizes="16x16" href="' . get_site_url() . '/favicon-16x16.png">';
    echo '<link rel="apple-touch-icon" sizes="180x180" href="' .get_site_url() .'/apple-touch-icon.png">';
}

add_action( 'wp_head','thierry_ajuster_head' );

add_action( 'admin_head','thierry_ajuster_head' );
/**
 * Affiche une information de débogage à l'écran, seulement si WP_DEBUG est à true
 *
 * Utilisation : thierry_echo_debug( 'test' );
 * Suppositions critiques : le style .debug doit définir l'apparence du texte
 *
 * @author Christiane Lagacé <christianelagace.com>
 *
 */
function thierry_echo_debug( $message ) {
   if ( WP_DEBUG === true ) {
       if ( ! empty( $message ) ) {
           echo '<span class="debug">';
           if ( is_array( $message ) || is_object( $message ) ) {
               echo '<pre>';
               print_r( $message ) ;
               echo '</pre>';
           } else {
               echo $message ;
           }
           echo '</span>';
       }
   }
}
add_action( 'after_setup_theme', 'thierry_apres_initialisation_theme' );
 
/**
 * Change l'attribut ?ver des .css et des .js pour utiliser celui de la version de style.css
 *
 * Utilisation : add_filter( 'style_loader_src', 'thierry_attribut_version_style', 9999 );
 *               add_filter( 'script_loader_src', 'thierry_attribut_version_style', 9999 );
 * Suppositions critiques : dans l'entête du fichier style.css du thème enfant, le numéro de version
 *                          à utiliser est inscrit à la ligne Version (ex : Version: ...)
 *
 * @author Christiane Lagacé
 * @return String Url de la ressource, se terminant par ?ver= suivi du numéro de version lu dans style.css
 *
 */
function thierry_attribut_version_style( $src ) {
   $version = thierry_version_style();
   if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
      $src = remove_query_arg( 'ver', $src );
      $src = add_query_arg( 'ver', $version, $src );
   }
   return $src;
}
 
add_filter( 'style_loader_src', 'thierry_attribut_version_style', 9999 );
add_filter( 'script_loader_src', 'thierry_attribut_version_style', 9999 );
 
/**
 * Retrouve le numéro de version de la feuille de style
 *
 * Utilisation : $version = thierry_version_style();
 * Suppositions critiques : dans l'entête du fichier style.css du thème enfant, le numéro de version
 *                          à utiliser est inscrit à la ligne Version (ex : Version: ...)
 *
 * @author Christiane Lagacé
 * @return String Le numéro de version lu dans style.css ou, s'il est absent, le numéro 1.0
 *
 */
function thierry_version_style() {
   $default_headers =  array( 'Version' => 'Version' );
   $fichier = get_stylesheet_directory() . '/style.css';
   $data = get_file_data( $fichier, $default_headers );
   if ( empty( $data['Version'] ) ) {
      return "1.0";
   } else {
      return $data['Version'];
   }
}