<!-- Resultat -->
     <h3>Les frais de l'année <?php echo $numAnnee ?> :
         </h3>
         <div class="">
       	   <table class="listeLegere">
             <tr>
                  <th>Noms</th>
                 <th>Forfait Etape</th>
                  <th>Frais Kilometrique</th>
                  <th>Nuitée Hôtel</th>
                  <th>Repas Restaurant</th>
     		     </tr>
             <tr>
               <?php
               $idBase='';
               foreach ($lesFraisForfait as $unFraisForfait )
   		         {
                 $nom = $unFraisForfait['nom'];
                 $id = $unFraisForfait['id'];
                 $montant = $unFraisForfait['montant'];
               ?>
                <?php
                if ($id != $idBase):
                ?>
              </tr>
               <tr>
                  <td class="qteForfait"><?php echo $nom?> </td>
                <?php
                  $idBase = $id;
                endif;
                ?>
                  <td class="qteForfait"><?php echo $montant?> </td>
               <?php
                 }
   		         ?>
   		        </tr>
         </table>

       </div>
    </div>
