<div id="contenu">
     <h2>Fiche de frais par visiteur pour une année</h2>
     <h3>Année à sélectionner : </h3>
     <form action="voirFrais2a" method="post">
       <div class="corpsForm">
         <p>
         <label for="lstAnnee" accesskey="n">Annee : </label>
         <select id="lstAnnee" name="lstAnnee">
         <?php
        //liste deroulante
        $numAnneeBase='';
         foreach ($lesAnneesMois as $uneAnnee)
         {
           $mois = $uneAnnee['mois'];
           $numAnnee =  $uneAnnee['numAnnee'];
           if ($numAnneeBase!= $numAnnee):

               if($numAnnee == $AnneeASelectionner){
                 ?>
                <option selected value="<?php echo $numAnnee ?>"><?php echo $numAnnee ?> </option>
                <?php
               }
               else{ ?>
                 <option value="<?php echo $numAnnee ?>"><?php echo $numAnnee ?> </option>
                 <?php
                 $numAnneeBase = $numAnnee;
               }
          endif;
         }
         ?>
           </select>
         </p>
       </div>
       <div class="piedForm">
         <p>
           <input id="ok" type="submit" value="Valider" size="20" />
           <input id="annuler" type="reset" value="Effacer" size="20" />
         </p>
       </div>
     </form>
