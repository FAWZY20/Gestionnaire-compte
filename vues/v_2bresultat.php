<h3>Fiche de type <?php echo $lstVisiteur?> : 
    </h3>
    <div class="encadre">
     <p>
     </p>
  	<table class="listeLegere">
  	   <caption>Eléments forfaitisés </caption>
        <tr>
		<th>mois </th>
		<th> montant </th>
		</tr>
        <tr>
        <?php
          foreach (  $lesResultats as $unResultat  ) 
		  {
				$mois = $unResultat['mois'];
				$montant = $unResultat['montant'];
		 ?>
				<td class="qteForfait"><?php echo $mois?> </td> 
				<td class="qteForfait"><?php echo $montant?> </td>
				</tr>
				<tr>
		 <?php
          }
		 ?>
		</tr>
    </table>
    </div>

 













