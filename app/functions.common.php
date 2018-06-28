<?php

if (get_magic_quotes_gpc()) {
	
	function stripslashes_deep($value) {
    	if (is_array($value)) {
        	$value = array_map('stripslashes_deep', $value);
        } elseif (!empty ($value) && is_string($value)) {
        	$value = stripslashes($value);
		}
                return $value;
	}

    $_POST = stripslashes_deep($_POST);
    $_GET = stripslashes_deep($_GET);
    $_REQUEST = stripslashes_deep($_REQUEST);
    $_COOKIE = stripslashes_deep($_COOKIE);
}

// ---------------------------------------------------------------------------------------
// fonction de REDIMENSIONNEMENT physique "PROPORTIONNEL" et Enregistrement
// ---------------------------------------------------------------------------------------
// retourne : true si le redimensionnement et l enregistrement ont bien eu lieu, sinon false
// ---------------------------------------------------------------------------------------
// La FONCTION : fctredimimage ($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src)
// Les parametres :
// - $W_max : LARGEUR maxi finale --> ou 0
// - $H_max : HAUTEUR maxi finale --> ou 0
// - $rep_Dst : repertoire de l image de Destination (deprotégé) --> ou '' (meme repertoire)
// - $img_Dst : NOM de l image de Destination --> ou '' (meme nom que l image Source)
// - $rep_Src : repertoire de l image Source (deprotégé)
// - $img_Src : NOM de l image Source
// ---------------------------------------------------------------------------------------
// 3 options :
// A- si $W_max != 0 et $H_max != 0 : a LARGEUR maxi ET HAUTEUR maxi fixes
// B- si $H_max != 0 et $W_max == 0 : image finale a HAUTEUR maxi fixe (largeur auto)
// C- si $W_max == 0 et $H_max != 0 : image finale a LARGEUR maxi fixe (hauteur auto)
// Si l'image Source est plus petite que les dimensions indiquees : PAS de redimensionnement.
// ---------------------------------------------------------------------------------------
// $rep_Dst : il faut s'assurer que les droits en écriture ont été donnés au dossier (chmod)
// - si $rep_Dst = ''   : $rep_Dst = $rep_Src (meme repertoire que l image Source)
// - si $img_Dst = '' : $img_Dst = $img_Src (meme nom que l image Source)
// - si $rep_Dst='' ET $img_Dst='' : on ecrase (remplace) l image source !
// ---------------------------------------------------------------------------------------
// NB : $img_Dst et $img_Src doivent avoir la meme extension (meme type mime) !
// Extensions acceptees (traitees ici) : .jpg , .jpeg , .png
// Pour ajouter d autres extensions : voir la bibliotheque GD ou ImageMagick
// (GD) NE fonctionne PAS avec les GIF ANIMES ou a fond transparent !
// ---------------------------------------------------------------------------------------
// UTILISATION (exemple) :
// $redimOK = fctredimimage(120,80,'reppicto/','monpicto.jpg','repimage/','monimage.jpg');
// if ($redimOK == true) { echo 'Redimensionnement OK !';  }
// ---------------------------------------------------------------------------------------
function fctredimimage($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src) {
 // ----------------------------------------------------
 $condition = 0;
 // Si certains parametres ont pour valeur '' :
 if ($rep_Dst == '') { $rep_Dst = $rep_Src; } // (meme repertoire)
 if ($img_Dst == '') { $img_Dst = $img_Src; } // (meme nom)
 // ----------------------------------------------------
 // si le fichier existe dans le répertoire, on continue...
 if (file_exists($rep_Src.$img_Src) && ($W_max!=0 || $H_max!=0)) { 
   // --------------------------------------------------
   // extensions acceptees : 
   $ExtfichierOK = '" jpg jpeg png"'; // (l espace avant jpg est important)
   // extension fichier Source
   $tabimage = explode('.',$img_Src);
   $extension = $tabimage[sizeof($tabimage)-1]; // dernier element
   $extension = strtolower($extension); // on met en minuscule
   // --------------------------------------------------
   // extension OK ? on continue ...
   if (strpos($ExtfichierOK,$extension) != '') {
      // -----------------------------------------------
      // recuperation des dimensions de l image Src
      $img_size = getimagesize($rep_Src.$img_Src);
      $W_Src = $img_size[0]; // largeur
      $H_Src = $img_size[1]; // hauteur
      // -----------------------------------------------
      // condition de redimensionnement et dimensions de l image finale
      // -----------------------------------------------
      // A- LARGEUR ET HAUTEUR maxi fixes
      if ($W_max != 0 && $H_max != 0) {
         $ratiox = $W_Src / $W_max; // ratio en largeur
         $ratioy = $H_Src / $H_max; // ratio en hauteur
         $ratio = max($ratiox,$ratioy); // le plus grand
         $W = $W_Src/$ratio;
         $H = $H_Src/$ratio;   
         $condition = ($W_Src>$W) || ($W_Src>$H); // 1 si vrai (true)
      }
      // -----------------------------------------------
      // B- HAUTEUR maxi fixe
      if ($W_max == 0 && $H_max != 0) {
         $H = $H_max;
         $W = $H * ($W_Src / $H_Src);
         $condition = ($H_Src > $H_max); // 1 si vrai (true)
      }
      // -----------------------------------------------
      // C- LARGEUR maxi fixe
      if ($W_max != 0 && $H_max == 0) {
         $W = $W_max;
         $H = $W * ($H_Src / $W_Src);         
         $condition = ($W_Src > $W_max); // 1 si vrai (true)
      }
      // -----------------------------------------------
      // REDIMENSIONNEMENT si la condition est vraie
      // -----------------------------------------------
     // Si l'image Source est plus petite que les dimensions indiquees :
      // Par defaut : PAS de redimensionnement.
     // Mais on peut "forcer" le redimensionnement en ajoutant ici :
     // $condition = 1; (risque de perte de qualite)
      // -----------------------------------------------
      if (1 == 1) {
         // --------------------------------------------
         // creation de la ressource-image "Src" en fonction de l extension
         switch($extension) {
         case 'jpg':
         case 'jpeg':
           $Ress_Src = imagecreatefromjpeg($rep_Src.$img_Src);
           break;
         case 'png':
           $Ress_Src = imagecreatefrompng($rep_Src.$img_Src);
           break;
         }
         // --------------------------------------------
         // creation d une ressource-image "Dst" aux dimensions finales
         // fond noir (par defaut)
         switch($extension) {
         case 'jpg':
         case 'jpeg':
           $Ress_Dst = imagecreatetruecolor($W,$H);
           break;
         case 'png':
           $Ress_Dst = imagecreatetruecolor($W,$H);
           // fond transparent (pour les png avec transparence)
           imagesavealpha($Ress_Dst, true);
           $trans_color = imagecolorallocatealpha($Ress_Dst, 0, 0, 0, 127);
           imagefill($Ress_Dst, 0, 0, $trans_color);
           break;
         }
         // --------------------------------------------
         // REDIMENSIONNEMENT (copie, redimensionne, re-echantillonne)
         imagecopyresampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src); 
         // --------------------------------------------
         // ENREGISTREMENT dans le repertoire (avec la fonction appropriee)
         switch ($extension) { 
         case 'jpg':
         case 'jpeg':
           imagejpeg ($Ress_Dst, $rep_Dst.$img_Dst);
           break;
         case 'png':
           imagepng ($Ress_Dst, $rep_Dst.$img_Dst);
           break;
         }
         // --------------------------------------------
         // liberation des ressources-image
         imagedestroy ($Ress_Src);
         imagedestroy ($Ress_Dst);
      }
      // -----------------------------------------------
   }
 }
// ---------------------------------------------------------------------------------------
 // si le fichier a bien ete cree
 if ($condition == 1 && file_exists($rep_Dst.$img_Dst)) { return true; }
 else { return false; }
}
// retourne : true si le redimensionnement et l enregistrement ont bien eu lieu, sinon false
// ---------------------------------------------------------------------------------------


/**
 * Sécurise une chaine de caractère avant de la placer en value d'un input.
 * @param string une chaine de caractère à entrer en value d'un champs input
 * @return une chaine nettoyée
 */
function input_value($input) {
	return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Transforme une date du format jj/mm/AAAA en une timestamp unix valide
 * @param $date string Date au format dd/mm/AAAA
 * @return string Timestamp unix 
 */
function ToTimestamp($date){
	$_a = explode('/', $date);
    return (sizeof($_a) == 3) ? mktime(0, 0, 0, $_a[1], $_a[0], $_a[2]) : null;
}

/**
 * Transforme une date du format jj/mm/AAAA hh:ii:ss en format DATETIME SQL
 * @param $date une date au format jj/mm/AAAA hh:ii:ss
 * @return une date au format SQL DATETIME
 */
function DateToDateTime ($date){
	$_a = explode('/', $date);
	
	$date = $_a[2][0].$_a[2][1].$_a[2][2].$_a[2][3].'-'.$_a[1].'-'.$_a[0].' 00:00:00';	
	return (sizeof($_a) == 3) ? $date : null;
}

function DateTimeToDate($date) {
	$_a = explode('/', $date);
	return strtotime($date);
}

/**
 * Formatage d'une chaine pour l'URL rewriting
 * @param $chaine 
 * @return string une chaine utilisable avec l'URL rexriting
 */ 
function txt_rewrite($chaine){
    
//	$chaine = utf8_decode($chaine);
//  $chaine = str_replace("[.\'\"(){}<>+&$%µ§£:²\\\/*?!]", "", $chaine);
//	$chaine = strtr($chaine,utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"), utf8_decode("aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn"));
//  $chaine = str_replace(" ", "-", $chaine);
//  $chaine = trim($chaine);
//	$chaine = strtolower($chaine);
//	return utf8_decode($chaine);
        
    //Convert accented characters, and remove parentheses and apostrophes
    $from = explode (',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,(,),[,],'");
    $to = explode (',', 'c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,,,,,,');
    //Do the replacements, and convert all other non-alphanumeric characters to spaces
    $chaine = preg_replace ('~[^\w\d]+~', '-', str_replace ($from, $to, trim ($chaine)));
    //Remove a - at the beginning or end and make lowercase
    $chaine =  strtolower (preg_replace ('/^-/', '', preg_replace ('/-$/', '', $chaine)));        
    
    return utf8_decode($chaine);
        
        
}

// Formatage d'une chaine pour l'upload de fichiers
function txt_img_rewrite($chaine){
	$chaine = utf8_decode($chaine);
  $chaine = str_replace("[\'\"(){}<>+&$%µ§£:²\\\/*?!]", "", $chaine);
	$chaine = strtr($chaine,utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ"), utf8_decode("aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn"));
  $chaine = str_replace(" ", "_", $chaine);
	$chaine = strtolower($chaine);
	return utf8_decode($chaine);
}

// Vérifier un email
function is_a_mail($chaine){
	if(preg_match("/^[-a-z0-9\._]+@[-a-z0-9\.]+\.[a-z]{2,4}$/i", $chaine)){
		return true;
	} else {
		return false;
	}
}

// Adapte un texte en vue d'une requête MySQL
function secure_txt_mysql($chaine){
	$chaine = eregi_replace("<", "&lt;", $chaine);
	$chaine = eregi_replace(">", "&gt;", $chaine);
	if(get_magic_quotes_gpc() == 0){
		$chaine = eregi_replace("\"", "&quot;", $chaine);
		$chaine = eregi_replace("'", "\'", $chaine);
	}
	elseif(get_magic_quotes_gpc() == 1) {
		$chaine = stripslashes($chaine);
	}
	return $chaine;
}

// Couper une chaine et supprimer ses caractères HTML
// $longueur : Nombre de caractères à afficher
// $keepHtml : balises HTML à garder à l'affichage (ex '<p><a>')
function format_txt($chaine,$longueur,$keepHtml = ''){
	preg_match('!.{0,'.$longueur.'}\s!si', $chaine, $match);
	$chaine = strip_tags($match[0],$keepHtml) . ' ...';
	return $chaine;
}

function convert_fr_date($date){
	$annee = substr($date, 0, 4);
	$mois = substr($date, 5, 2);
	$jour = substr($date, 8, 2);
	switch($mois){
		case "01" : $mois_lettre="Janvier"; break;
		case "02" : $mois_lettre="Février"; break;
		case "03" : $mois_lettre="Mars"; break;
		case "04" : $mois_lettre="Avril"; break;
		case "05" : $mois_lettre="Mai"; break;
		case "06" : $mois_lettre="Juin"; break;
		case "07" : $mois_lettre="Juillet"; break;
		case "08" : $mois_lettre="Août"; break;
		case "09" : $mois_lettre="Septembre"; break;
		case "10" : $mois_lettre="Octobre"; break;
		case "11" : $mois_lettre="Novembre"; break;
		case "12" : $mois_lettre="Décembre"; break;
		default : $mois_lettre="Inconnu";
	}
	if($jour[0] == "0"){
	 $jour = $jour[1];
	}
	$date = $jour . " " . $mois_lettre . " " . $annee;
	
	return $date;
}

function convert_fr_mois($mois){
	switch($mois){
		case "01" : $mois_lettre="Janvier"; break;
		case "02" : $mois_lettre="Février"; break;
		case "03" : $mois_lettre="Mars"; break;
		case "04" : $mois_lettre="Avril"; break;
		case "05" : $mois_lettre="Mai"; break;
		case "06" : $mois_lettre="Juin"; break;
		case "07" : $mois_lettre="Juillet"; break;
		case "08" : $mois_lettre="Août"; break;
		case "09" : $mois_lettre="Septembre"; break;
		case "10" : $mois_lettre="Octobre"; break;
		case "11" : $mois_lettre="Novembre"; break;
		case "12" : $mois_lettre="Décembre"; break;
		default : $mois_lettre="Inconnu";
	}
	
	return $mois_lettre;
}

/**
 * Fonction appellée lors de la génération des arborescence, renvoie un nombre de tirets correspondant au niveau de la page
 * @param uint $level
 * @return string $tirets
 */
function getArboTirets($level)
{
    $tirets = "" ;
    for($i = 0 ; $i < $level ; $i++)
    {
        $tirets .= "--" ;
    }
    return $tirets ;
}

/*
 * Fonction qui permet de faire un explode de plusieurs délimiteurs
 */
function explodeX($delimiters,$string)
{
    $return_array = Array($string); // The array to return
    $d_count = 0;
    while (isset($delimiters[$d_count])) // Loop to loop through all delimiters
    {
        $new_return_array = Array();
        foreach($return_array as $el_to_split) // Explode all returned elements by the next delimiter
        {
            $put_in_new_return_array = explode($delimiters[$d_count],$el_to_split);
            foreach($put_in_new_return_array as $substr) // Put all the exploded elements in array to return
            {
                $new_return_array[] = $substr;
            }
        }
        $return_array = $new_return_array; // Replace the previous return array by the next version
        $d_count++;
    }
    return $return_array; // Return the exploded elements
}

/**
 * Renvoi le statut d'une requete HTTP selon la configuration du serveur
 * @param string $msg message HTTP à envoyer
 * @param int $code code de la réponse HTTP
 */
function http_statut($msg, $code) {
 if (preg_match('/cgi/', php_sapi_name())) {
  header('Status: ' . $code . ' ' . $msg, true, $code);
 }
 else {
  header($msg, true, $code);
 }
}

/**
 * 
 * Renvoi le libelle correspondant à la langue appelée pour uen chaine de la forme @fr_FR:Libelle@en_UK:libelle@
 * @param string La chaine d'origine
 * @param string La langue de sortie 
 */
function getLibelleLang($str, $locale = '') {
	if (empty ($locale)) {
		$locale = SIT_LANGUE;
	}
	$debut = strpos($str, '@' . $locale . ':');	
	if ($debut === false) {
		return $str;
	}
	$debut += strlen('@' . $locale . ':');
	$fin = strpos($str, '@', $debut);
	return substr($str, $debut, $fin - $debut);
}


/**
 * Stocke dans une variable de session les eventuels
 * 
 * @param string Le message à renvoyer
 * @param string le Type (success, error, notice)
 */
function setMessage($msg='', $type='error'){
    $_SESSION['gretour'][$type][] = $msg;
}


/**
 * Renvoi tous les messages (success, error, notice) stockés dans
 * la variable de session.
 * 
 * @return string Le Message
 */
function getMessage(){
    $return = '';
//    echo '<pre>'; print_r($_SESSION); echo '</pre>';
    if(isset($_SESSION['gretour']) && $_SESSION['gretour'] != ''){
        foreach ($_SESSION['gretour'] as $type => $a_msg){
            if(is_array($a_msg)){
                $return .= '<div class="p-10 mb-20 font-aldrich alert-'.$type.'"><ul>';
                foreach($a_msg as $value){
                    $return .= '<li>' . $value . '</li>';
                }
                $return .= '</ul></div>';
            }
        }
    }
    unset($_SESSION['gretour']);
    return $return;
}

/**
 * 
 * Génère uen chaine de caractères alétoire du nombre de caractères apssés en argument (8 par défaut)
 * @param int Le nombre de caractères voulu.
 */
function generpass($chrs = 8) {
	
	$chaine = ""; 
	$list = "123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ";

	mt_srand((double)microtime()*1000000);

	$newstring="";

	while( strlen( $newstring )< $chrs ) {
			$newstring .= $list[mt_rand(0, strlen($list)-1)];
		}

	return $newstring;
 }

 function genererRegexp($str) {
        $str = trim(strtr($str, '[]|:^/$()*+?{}-\\', '               '));
        $tabLettres[] = 'aàáâãäåÀÁÂÄÅ';
        $tabLettres[] = 'eéêèëÉÊËÈ';
        $tabLettres[] = 'iìíîïÌÍÎÏ';
        $tabLettres[] = 'oòóôõöÒÓÔÕÖ';
        $tabLettres[] = 'uùúûüÙÚÛÜ';
        $tabLettres[] = 'cçÇ';
        $tabLettres[] = 'dÐ';
        $tabLettres[] = 'sšŠ';
        $tabLettres[] = 'nñÑ';
        $tabLettres[] = 'yýÿÝŸ';
        $tabLettres[] = 'zžŽ';
        foreach ($tabLettres as $val) {
            $str = preg_replace('/([' . $val . '])/ui', '(' . implode('|', mb_str_split($val)) . ')', $str);
        }
        return $str;
    }

    function mb_str_split($str) {
        $result = array ();
        for ($i = 0; $i < mb_strlen($str); $i++) {
            $result[] = mb_substr($str, $i, 1);
        }
        return $result;
    }

    function getVisitorIP() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else{
            return $_SERVER['REMOTE_ADDR'];
        }
    }

?>