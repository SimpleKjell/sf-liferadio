


<form method="post" action="">
  <input type="hidden" name="update_settings" />
  <?php wp_nonce_field( 'update_settings', 'prb_nonce_check' ); ?>



  <?php
  $resp = $this->getResponsiblePerson();

  /*
  * Für die Spenden wird kein custom post type benutzt, einfach nur die wp options
  */
  $donations = $this->options['prb_donations'];
  if(!is_array($donations)) {
    $donations = array();
  }

  // Nach dem Speichern nimm die Post Variablen
  //if(!empty($_POST)) {
  //  $donations = $_POST['prb_donations'];
  //  $donations = array_reverse($donations);
  //}


  if(!empty($donations)) {
    end($donations);
    $lastKey = key($donations);
    //$nextKey = $lastKey + 1;
    $nextKey = 0;
  } else {
    //$nextKey = 1;
    $nextKey = 0;
  }

  ?>

  <div class="hidden">
    <?php
    if(!empty($donations)) {

      /*foreach($donations as $key => $donation) {

        ?>
        <input type="text" name="prb_donations[<?php echo $key;?>][orga]" value="<?php echo $donation['orga'];?>" />
        <input type="text" name="prb_donations[<?php echo $key;?>][value]" value="<?php echo $donation['value'];?>" />
        <input type="text" name="prb_donations[<?php echo $key;?>][city]" value="<?php echo $donation['city'];?>" />
        <input type="text" name="prb_donations[<?php echo $key;?>][mail]" value="<?php echo $donation['mail'];?>" />
        <input type="text" name="prb_donations[<?php echo $key;?>][month]" value="<?php echo $donation['month'];?>" />
        <input type="text" name="prb_donations[<?php echo $key;?>][resp]" value="<?php echo $donation['resp'];?>" />
        <?php
      }*/
    }
    ?>

  </div>
  <input type="hidden" name="add_new_donation" value="ye"/>
  <div class="marginTopMedium spendenAddBox postbox wrap">

    <h3><?php _e('Neue Spende hinzufügen','prbreakfast'); ?> </h3>


    <div class="marginTopMedium field">
      <label for="orga" >Organisator</label>
      <input value="" name="prb_donations[<?php echo $nextKey;?>][orga]" id="orga" type="text" />
    </div>
    <div class="field">
      <label for="donation" >Spende</label>
      <input name="prb_donations[<?php echo $nextKey;?>][value]" id="donation" type="number" />
    </div>
    <div class="field">
      <label for="city" >Ort</label>
      <input name="prb_donations[<?php echo $nextKey;?>][city]" id="city" type="text" />
    </div>
    <div class="field">
      <label for="mail" >E-Mail</label>
      <input name="prb_donations[<?php echo $nextKey;?>][mail]" id="mail" type="email" />
    </div>
    <div class="field">
      <label for="mail" >Monat/Jahr</label>
      <select name="prb_donations[<?php echo $nextKey;?>][month]">
        <option value="<?php echo date('M Y');?>"><?php echo date('M Y');?></option>
        <!--option value="Feb 2015">Feb 2015</option-->
        <!--option value="Jan 2015">Jan 2015</option-->
        <option value="<?php echo date("M Y", strtotime("first day of previous month"));?>"><?php echo date("M Y", strtotime("first day of previous month"));?></option>
      </select>
    </div>
    <?php if($resp == 'Admin') {
      ?>
      <div class="field">
        <label for="bundesland">Bundesland</label>
        <select id="bundesland" name="prb_donations[<?php echo $nextKey;?>][resp]">
          <option value="Burgenland">Burgenland</option>
          <option value="Oberösterreich">Oberösterreich</option>
          <option value="Tirol">Tirol</option>
          <option value="Kärnten">Kärnten</option>
          <option value="Salzburg">Salzburg</option>
          <option value="Vorarlberg">Vorarlberg</option>
          <option value="Niederösterreich">Niederösterreich</option>
          <option value="Steiermark">Steiermark</option>
          <option value="Wien">Wien</option>
        </select>
      </div>
      <?php
    } else {
      ?>
        <input type="hidden" name="prb_donations[<?php echo $nextKey;?>][resp]" value="<?php echo $resp;?>"
      <?php
    }
    ?>


  </div>


  <p class="submit">
  	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Spende hinzufügen','prbreakfast'); ?>"  />
  </p>
</form>
