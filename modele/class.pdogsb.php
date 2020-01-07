<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiquesgetInfosAdmin
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsb2016';   		
      	private static $user='root' ;    		
      	private static $mdp='root' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
	$req = PdoGsb::$monPdo->prepare("SELECT visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom from visiteur
where visiteur.login=? and visiteur.mdp=?");
   	 $req->execute(array($login,$mdp));
$ligne = $req->fetch();
	return $ligne;
	}

/**
Permet de voir la visu* de la feuille de co 
*/
        public function getInfosAdmin($login, $mdp){
	$req = PdoGsb::$monPdo->prepare( "SELECT gestionnaire.id as id, gestionnaire.nom as nom, gestionnaire.prenom as prenom from gestionnaire
where gestionnaire.login=? and gestionnaire.mdp=?");
    	$req->execute(array($login,$mdp));
	$ligne = $req->fetch();
	return $ligne;
	}

    public function getInfosmdp($email){
        $req = PdoGsb::$monPdo->prepare( "SELECT gestionnaire.mdp as mdp from gestionnaire
where gestionnaire.Email='?' ");
        $req->execute(array($email));
        $ligne = $req->fetch();
        return $ligne;
    }




/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}

/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}


/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}
	// Retourne la liste des "id" de Visiteur
public function getLesVisiteurs(){
		$req = PdoGsb::$monPdo->prepare ("select visiteur.id as idVisiteur from visiteur");
        $req ->execute ();
        $lesLignes = $req->fetchAll();
        return $lesLignes;
	}
 // Retourne tout les types 
	public function getLesTypes(){
		$req = PdoGsb::$monPdo->prepare ("select id AS idType from fraisforfait");
        $req ->execute ();
        $lesLignes = $req->fetchAll();
        return $lesLignes;
	}
	 // Retourne les montant celon le tyoe selectionner
        	public function getLesMontants($lstTypes){
		$req = PdoGsb::$monPdo->prepare ("SELECT idVisiteur, mois , (SUM(quantite)*montant) AS total
FROM lignefraisforfait
inner join fraisforfait on lignefraisforfait.idFraisForfait=fraisforfait.id
WHERE idFraisForfait = '$lstTypes'
GROUP BY idVisiteur , mois");
        $req ->execute ();
        $lesLignes = $req->fetchAll();
        return $lesLignes;
	}
        
        /** Vue 2a "pour une année tous les frais par visiteur" **/

   /* Retourne les Annees */
  public function getLesAnnees(){
		$req = "SELECT fichefrais.mois as mois from  fichefrais
		        order by fichefrais.mois desc";
		$res = PdoGsb::$monPdo->query($req);
		$lesAnneesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr($mois,0,4);
			$lesAnneesMois["$mois"]=array(
		     "mois"=>"$mois",
	       "numAnnee"  => "$numAnnee",
             );
			$laLigne = $res->fetch();
		}
		return $lesAnneesMois;
	}

  /* Retourne les informations des frais des visiteurs pour une annee donné */
  public function getLesFraisParVisiteur($numAnnee){
		$req = "SELECT visiteur.id as id, visiteur.nom as nom, SUBSTR(mois, 1, 4), fraisforfait.libelle as libelle, lignefraisforfait.idFraisForfait as etat, sum(lignefraisforfait.`quantite`*fraisforfait.montant) as montant
            FROM lignefraisforfait
            inner join fraisforfait on lignefraisforfait.idFraisForfait = fraisforfait.id
            inner join visiteur on lignefraisforfait.idVisiteur = visiteur.id
            where SUBSTR(mois, 1, 4)='$numAnnee'
            group by visiteur.id,lignefraisforfait.idFraisForfait, SUBSTR(mois, 1, 4)";
		$res = PdoGsb::$monPdo->query($req);
		$lesLigne = $res->fetchAll();
		return $lesLigne;
	}
	// retourne les Frais et le mois pour un visiteur
public function getLesFrais($lstVisiteur)
	{
		$req = PdoGsb::$monPdo->prepare ("select mois , SUM(montantValide) AS montant from ficheFrais  where idVisiteur= '$lstVisiteur' group by mois");
		$req ->execute ();
		$lesLignes = $req->fetchAll();
		return $lesLignes;
		
	}
	
	//...
    public function getLeIDVisiteur($login,$mdp)
{
	    $ok = false;
		$req = "update mdp from visiteur where login= '$login' and mdp= '$mdp'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne == 0)
		{
			$ok = true;
		}
		  
		return $ok;
	
}
	 
           
 

		
		
           
    
}
?>