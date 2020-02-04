<div id="contenu">
    <div class="corpsForm">
  <form  action= "resultat2b" method="POST">
        <h2> SELECTIONNER  UN VISITEUR</h2>
	     <label for="lstVisiteur" accesskey="n">Visiteur : </label>
              <select name="lstVisiteur">
                <?php
             foreach ($lesVisiteurs AS $Visiteur)
                { 
                ?>
                <option value="<?php echo $Visiteur[0];?>"><?php echo $Visiteur[0];?> </OPTION>
                <?php
                 }
                ?>
              </select>

	</div>
	<div class="piedForm">
	  <p>
		<input id="ok" type="submit" value="Valider" size="20" />
	  </p> 
	</div>
      </form>

		   
