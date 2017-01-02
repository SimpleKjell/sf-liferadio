<?php
class SFLshortCode {

  var $options;
  var $session;

  function __construct()
	{
		add_action( 'init',   array(&$this,'sfliferadio_shortcodes'));

    // Our custom post type function
    //add_action( 'init', array(&$this, 'create_custom_post_type' ));

    // Custom Meta Box
    //add_action( 'add_meta_boxes', array(&$this, 'create_custom_meta_box' ));

    /*
    * Global Options
    */
    $this->options = get_option('sfliferadio_options');

	}

  /*
  * Custom Meta Box
  */
  function create_custom_meta_box()
  {
    add_meta_box(
      'custom-sub-box',      // Unique ID
      'Teilnehmer Informationen',    // Title
      array(&$this, 'sub_meta_box' ),   // Callback function
      'anfragen',         // Admin page (or post type)
      'normal',         // Context
      'default'         // Priority
    );
  }

  /*
  * Meta Box
  * Backend Anzeigen der Teilnehmer
  */
  function sub_meta_box( $object, $box ) {

    $subMeta = get_post_meta($object->ID);

    ?>
    <div class="postbox" id="boxid">
      <div class="marginTopMedium marginBottomMedium subInfo">
        <?php
        foreach($subMeta as $key => $value) {
          $this->showSubInformation($key, $value, $object->ID);
        }
        ?>
      </div>
    </div>

  <?php
 }

  /*
  * Custom Post Type
  * Gewinnspielteilnehmer
  */
  function create_custom_post_type() {
    register_post_type( 'anfragen',
    // CPT Options
        array(
            'labels' => array(
                'name' => 'Anfragen',
            ),
            'public'    => false,
            'show_ui'            => true,
		        'show_in_menu'       => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'anfragen'),
            'supports' => array('title','thumbnail')
        )
    );
  }

  /*
  * show Subscriber Info
  */
  function showSubInformation($field, $value, $subID) {

    switch($field) {
      case 'Vorname':
        $label = $field;
        $value = $value[0];
        break;
      case 'Nachname':
        $label = $field;
        $value = $value[0];
        break;
      case 'E-Mail':
        $label = $field;
        $value = $value[0];
        break;
      case 'Telefon':
        $label = $field;
        $value = $value[0];
      case 'Bundesland':
        $label = $field;
        $value = $value[0];
        break;
      default:
        $label = '';
        $value = '';
    }

    if(!empty($label) && !empty($value) ) {

     ?>

     <div class="field">
       <div class="label">
         <?php echo $label; ?>
       </div>
       <div class="value">
         <?php echo $value;?>
       </div>
     </div>

     <?php
   }
 }

  /**
	* Add the shortcodes
	*/
	function sfliferadio_shortcodes()
	{
    // Frontend Form
    add_shortcode( 'sfliferadio_form', array(&$this,'sfliferadio_form_function') );

	}

  public function sfliferadio_form_function($atts)
  {
    ob_start();
    ?>
    <div class="sfl_container">
      <form>
        <div class="sfl_container_data sfl_box">
          <div class="sfl_input_field">
            <label>Vorname</label>
            <input type="text" name="vorname" id="vorname" />
          </div>
          <div class="clear"></div>
          <div class="sfl_input_field">
            <label>Nachname</label>
            <input type="text" name="nachname" id="nachname" />
          </div>
          <div class="clear"></div>
          <div class="sfl_input_field">
            <label>Telefon</label>
            <input type="text" name="telefon" id="telefon" />
          </div>
          <div class="clear"></div>
          <div class="sfl_input_field">
            <label>E-Mail</label>
            <input type="email" name="email" id="email" />
          </div>
          <div class="clear"></div>
        </div>
        <div class="sfl_container_wunsch sfl_box">
          <div class="sfl_heading center">
            2 x Gehalt
          </div>
          <div class="sfl_wunsch_input center">
            = 1 x
            <input type="text" name="wunsch" id="wunsch" />
          </div>
        </div>
      </form>
    </div>
    <?php
    //assign the file output to $content variable and clean buffer
		$content = ob_get_clean();
		return  $content;
  }

}

$key = "shortcode";
$this->{$key} = new SFLshortCode();
