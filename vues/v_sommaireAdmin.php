    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
       </div>
        <ul id="menuList">
			<li >
				  Gestionnaire :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>
			</li>
           <li class="smenu">
              <a href="selectionnerAnnee" title="Consultation de fiches de frais par visiteur">Fiche de frais par Visiteur pour une année</a>
           </li>
           <li class="smenu">
              <a href="selectionnerVisiteur" title="Consultation de fiches de frais par mois">Fiches de frais par Mois</a>
           </li>
           <li class="smenu">
              <a href="selectionnerTypes" title="Consultation des montants par mois et visiteur">Montants par Mois et par Visiteur</a>
           </li>

 	         <li class="smenu">
              <a href="deconnecter" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>

    </div>
