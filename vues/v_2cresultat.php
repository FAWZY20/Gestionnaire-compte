<h3>Fiche de type <?php echo $lstTypes?> :
    </h3>
    <div class="encadre">
    <p>
     </p>
  	<table class="listeLegere">
  	   <caption>Eléments forfaitisés </caption>
        <tr>
		<th> id </th>
		<th>mois </th>
		<th>montant </th>
		</tr>
        <tr>
        <?php
          foreach (  $lesResultats as $unResultat  ) 
		  {
				$id = $unResultat['idVisiteur'];
				$mois = $unResultat['mois'];
				$montant = $unResultat['total'];
		?>
				<td class="qteForfait"><?php echo $id?> </td> 
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
 













