<?php
                    /* Définition des routes*/
$app->match('/', "ConnexionControleur::accueil"); 
$app->match('/verifierUser', "ConnexionControleur::verifierUser");
$app->match('/deconnecter', "ConnexionControleur::deconnecter");
$app->match('/MDPoublier', "ConnexionControleur::MDPoublier");
$app->match('/verifierEmail', "ConnexionControleur::verifierEmail");

$app->match('/selectionnerMois', "EtatFraisControleur::selectionnerMois");
$app->match('/voirFrais', "EtatFraisControleur::voirFrais");

$app->match('/saisirFrais', "GestionFicheFraisControleur::saisirFrais");
$app->match('/validerFrais', "GestionFicheFraisControleur::validerFrais");

$app->match('/selectionnerAnnee', "Voir2a::selectionnerAnnee");
$app->match('/voirFrais2a', "Voir2a::voirFrais2a");

$app->match('/selectionnerVisiteur', "Voir2b::selectionnerVisiteur");
$app->match('/resultat2b', "Voir2b::resultat2b");

$app->match('/selectionnerTypes', "Voir2c::selectionnerTypes"); //href qui vient du sommaire // Classe du controleur// Fonction de la Classe du Controleur
$app->match('/voir2cresultat', "Voir2c::voir2cresultat");   //action qui vient du form de la vue // Classe du controleur// Fonction de la Classe du Controleur


?>
