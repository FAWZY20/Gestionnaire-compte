<?php
require_once __DIR__.'/../modele/class.pdogsb.php';
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

//********************************************Contrôleur connexion*****************//
class ConnexionControleur{

    public function __construct(){
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
    }
    public function accueil(){
        require_once __DIR__.'/../vues/v_connexion.php';
        require_once __DIR__.'/../vues/v_pied.php';
        $view = ob_get_clean(); // récupère le contenu du flux et le vide
        return $view;     // retourne le flux 
    }
  
  public function verifierUser(Request $request, Application $app){
        session_start();
        $login = $request->get('login');
        $mdp = $request->get('mdp');
        $pdo = PdoGsb::getPdoGsb();
        $admin = $pdo->getInfosAdmin($login,$mdp);
        $visiteur = $pdo->getInfosVisiteur($login,$mdp);
     if(!is_array( $visiteur) && !is_array( $admin)){
            $app['couteauSuisse']->ajouterErreur("Login ou mot de passe incorrect");
            require_once __DIR__.'/../vues/v_erreurs.php';
            require_once __DIR__.'/../vues/v_connexion.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
        }
     elseif(is_array( $visiteur)){
            $id = $visiteur['id'];
            $nom =  $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            $app['couteauSuisse']->connecter($id,$nom,$prenom);
            require_once __DIR__.'/../vues/v_sommaire.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
        }
      else {
        $id = $admin['id'];
        $nom =  $admin['nom'];
        $prenom = $admin['prenom'];
        $app['couteauSuisse']->connecter($id,$nom,$prenom);
        require_once __DIR__.'/../vues/v_sommaireAdmin.php';
        require_once __DIR__.'/../vues/v_pied.php';
        $view = ob_get_clean();
      }
        return $view;
    }
    public function deconnecter(Application $app){
        $app['couteauSuisse']->deconnecter();
       return $app->redirect('../public/');
    }
    public function MDPoublier(Application $app)
    {
        session_start();
        require_once __DIR__.'/../vues/v_mdpOublier.php';
        $view = ob_get_clean();
        return $view;
    }
    public function verifierEmail(Request $request, Application $app){
        session_start();
        $email = $request->get('email');
        $pdo = PdoGsb::getPdoGsb();
        $admin = $pdo->getInfosmdp($email);
        if(!is_array( $admin)){
            $app['couteauSuisse']->ajouterErreur("email inconue");
            require_once __DIR__.'/../vues/v_erreurs.php';
            require_once __DIR__.'/../vues/v_mdpOublier.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
        }
        elseif(is_array( $admin)){
            $mdp = $admin['mdp'];
            $app['couteauSuisse']->ajouterErreur("votre mot de passe et $mdp");
            require_once __DIR__.'/../vues/v_erreurs.php';
            require_once __DIR__.'/../vues/v_mdpOublier.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
        }
        return $view;

    }
}
//**************************************Contrôleur EtatFrais**********************

class EtatFraisControleur {
     private $idVisiteur;
     private $pdo;
     public function init(){
        $this->idVisiteur = $_SESSION['idVisiteur'];
        $this->pdo = PdoGsb::getPdoGsb();
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
        require_once __DIR__.'/../vues/v_sommaire.php';
        
    }
    public function selectionnerMois(Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init();
            $lesMois = $this->pdo->getLesMoisDisponibles($this->idVisiteur);
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            $lesCles = array_keys( $lesMois );
            $moisASelectionner = $lesCles[0];
            require_once __DIR__.'/../vues/v_listeMois.php';
             require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view;
        }
        else{
            return Response::HTTP_NOT_FOUND;
        }
    }
    public function voirFrais(Request $request,Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init();
            $leMois = $request->get('lstMois');
            $this->pdo = PdoGsb::getPdoGsb();
            $lesMois = $this->pdo->getLesMoisDisponibles($this->idVisiteur);
            $moisASelectionner = $leMois;
            $lesFraisForfait= $this->pdo->getLesFraisForfait($this->idVisiteur,$leMois);
            $lesInfosFicheFrais = $this->pdo->getLesInfosFicheFrais($this->idVisiteur,$leMois);
            $numeroAnnee = substr( $leMois,0,4);
            $numeroMois = substr( $leMois,4,2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  $app['couteauSuisse']->dateAnglaisVersFrancais($dateModif);
            require_once __DIR__.'/../vues/v_listeMois.php';
            require_once __DIR__.'/../vues/v_etatFrais.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view;
         }
        else {
            $response = new Response();
            $response->setContent('Connexion nécessaire');
            return $response;
        }
    } 
}
//************************************Controleur GererFicheFrais********************

Class GestionFicheFraisControleur{
    private $pdo;
    private $mois;
    private $idVisiteur;
    private $numAnnee;
    private $numMois;
    
    public function init(Application $app){
            $this->idVisiteur = $_SESSION['idVisiteur'];
            ob_start();
            require_once __DIR__.'/../vues/v_entete.php';
            require_once __DIR__.'/../vues/v_sommaire.php';
            $this->mois = $app['couteauSuisse']->getMois(date("d/m/Y"));
            $this->numAnnee =substr($this->mois,0,4);
            $this->numMois =substr( $this->mois,4,2);
            $this->pdo = PdoGsb::getPdoGsb();
        
     }
     
    public function saisirFrais(Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init($app);
            if($this->pdo->estPremierFraisMois($this->idVisiteur,$this->mois)){
                $this->pdo->creeNouvellesLignesFrais($this->idVisiteur,$this->mois);
            }
            $lesFraisForfait = $this->pdo->getLesFraisForfait($this->idVisiteur,$this->mois);
            $numMois = $this->numMois;
            $numAnnee = $this->numAnnee; 
            require_once __DIR__.'/../vues/v_listeFraisForfait.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view; 
        }
         else {
            $response = new Response();
            $response->setContent('Connexion nécessaire');
            return $response;
        }
    }
    public function validerFrais(Request $request,Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init($app);
            $lesFrais = $request->get('lesFrais');
            if($app['couteauSuisse']->lesQteFraisValides($lesFrais)){
                $this->pdo->majFraisForfait($this->idVisiteur,$this->mois,$lesFrais);
            }
            else{
                $app['couteauSuisse']->ajouterErreur("Les valeurs des frais doivent être numériques");
                require_once __DIR__.'/../vues/v_erreurs.php';
                require_once __DIR__.'/../vues/v_pied.php';
            }
            $lesFraisForfait= $this->pdo->getLesFraisForfait($this->idVisiteur,$this->mois);
            $numMois = $this->numMois;
            $numAnnee = $this->numAnnee; 
            require_once __DIR__.'/../vues/v_listeFraisForfait.php';
            require_once __DIR__.'/../vues/v_pied.php';
            $view = ob_get_clean();
            return $view; 
        }
         else {
            $response = new Response();
            $response->setContent('Connexion nécessaire');
            return $response;
        }
        
    }
    
    
    
    
}

 Class Voir2a{
   private $pdo;
   private $lAnnee;

  public function init(){
         $this->pdo = PdoGsb::getPdoGsb();
         ob_start();             // démarre le flux de sortie
         require_once __DIR__.'/../vues/v_entete.php';
         require_once __DIR__.'/../vues/v_sommaireAdmin.php';
  }

//
  public function selectionnerAnnee(Application $app){
       session_start();
       if($app['couteauSuisse']->estConnecte()){
           $this->init();
           $lesAnneesMois = $this->pdo->getLesAnnees();
           $lesCles = array_keys( $lesAnneesMois );
           $anneeASelectionner = $lesCles[0];
           require_once __DIR__.'/../vues/v_2a.php';
           $view = ob_get_clean();
           return $view;
       }
  }

//Retourne les frais
  public function voirFrais2a(Request $request,Application $app){
      session_start();
      if($app['couteauSuisse']->estConnecte()){
          $this->init();
          $lAnnee = htmlentities($request->get('lstAnnee'));
          $this->pdo = PdoGsb::getPdoGsb();
          $lesAnneesMois = $this->pdo->getLesAnnees();
          $anneeASelectionner = $lAnnee;
          $lesFraisForfait= $this->pdo->getLesFraisParVisiteur($lAnnee); 
          $numeroAnnee = substr( $lAnnee,0,4);
          $numeroMois = substr( $lAnnee,4,2);
          require_once __DIR__.'/../vues/v_2a.php';
          require_once __DIR__.'/../vues/v_2aResultat.php';
          require_once __DIR__.'/../vues/v_pied.php';
          $view = ob_get_clean();
          return $view;
       }
      else {
          $response = new Response();
          $response->setContent('Connexion nécessaire');
          return $response;
      }
  }
}

 
 
 
Class Voir2b{
     private $pdo;
    private $mois;
    private $idtype;
    private $idVisiteur;
    
    public function init(){
        $this->idVisiteur = $_SESSION['idVisiteur'];
        $this->pdo = PdoGsb::getPdoGsb();
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
        require_once __DIR__.'/../vues/v_sommaireAdmin.php';
        
    }
    
   public function selectionnerVisiteur(Application $app)
   {
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init();   
            $lesVisiteurs = $this->pdo->getLesVisiteurs(); // Pour la Liste deroulantes
            require_once __DIR__.'/../vues/v_2b.php';
            $view = ob_get_clean();
            return $view;
        }
 
   }
   
     public function resultat2b(Request $request,Application $app){
        session_start();
        if($app['couteauSuisse']->estConnecte()){
            $this->init(); 
            $lstVisiteur=htmlentities($request->get('lstVisiteur')); 
			 $lesResultats = $this->pdo->getLesFrais($lstVisiteur); // Remplir le tableau
            $lesVisiteurs = $this->pdo->getLesVisiteurs(); // Pour la Liste deroulantes
            require_once __DIR__.'/../vues/v_2b.php';
			require_once __DIR__.'/../vues/v_2bresultat.php';

            $view = ob_get_clean();
            return $view;
        }
 
   }


 }

 
 
 
 
 Class Voir2c{
     private $pdo;
    private $mois;
    private $idtype;
    private $idVisiteur;
    
 public function init()
        {
        $this->idVisiteur = $_SESSION['idVisiteur'];
        $this->pdo = PdoGsb::getPdoGsb();
        ob_start();             // démarre le flux de sortie
        require_once __DIR__.'/../vues/v_entete.php';
        require_once __DIR__.'/../vues/v_sommaireAdmin.php';
        
         }
   
		
		public function selectionnerTypes(Application $app)
        {
        session_start();
       
            $this->init();
            $lesTypes = $this->pdo->getLesTypes(); // Pour la Liste deroulantes
            require_once __DIR__.'/../vues/v_2c.php';
            $view = ob_get_clean();
            return $view;
   	
        }
   
   public function voir2cresultat(Request $request,Application $app){
        session_start();
       
            $this->init();
			$lstTypes=htmlentities($request->get('lstTypes')); // Donne la valeur de la liste que le gestionnaire a choisi 
            $lesResultats = $this->pdo->getLesMontants($lstTypes); // Retourne les valeurs selon la valeur de la liste
			$lesTypes = $this->pdo->getLesTypes(); // Pour la Liste deroulantes

            require_once __DIR__.'/../vues/v_2c.php';
			require_once __DIR__.'/../vues/v_2cresultat.php';
            $view = ob_get_clean(); // Retourne le flux
            return $view;
   	
   }
   
 }

?>

