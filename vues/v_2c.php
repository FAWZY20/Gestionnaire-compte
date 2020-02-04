<div id="contenu">
    <form action="voir2cresultat" method="post">
    <div class="corpsForm">
      <h2> Type de Frais : </h2>
	  <label for="lstMois" accesskey="n">Type : </label>
        <select name="lstTypes">
            <?php
			// BOUCLE FOREACH POUR PARCOURIR LA LISTE AFIN D'AFFICHER TOUT LES TYPES ::
			foreach($lesTypes AS $Type)
      {
    ?>
            <option  value="<?php echo $Type[0]?>"> <?php echo $Type[0]?> </option> 
    <?php
      }
	  // FIN LISTE DEROULANTE ::
    ?>
        </select>
      </p>
	</div>
	<div class="piedForm">
	  <p>
		<input id="ok" type="submit" value="Valider" size="20" />

	  </p> 
	</div>  
        
      </form>

