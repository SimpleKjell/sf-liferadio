<?php
class SFLshortCode {

  var $options;
  var $session;

  function __construct()
	{
		add_action( 'init',   array(&$this,'sfliferadio_shortcodes'));

    // Our custom post type function
    add_action( 'init', array(&$this, 'create_custom_post_type' ));

    // Custom Meta Box
    add_action( 'add_meta_boxes', array(&$this, 'create_custom_meta_box' ));

    add_action('wp_ajax_add_new_suberino', array( $this, 'add_new_suberino' ));
    add_action('wp_ajax_nopriv_add_new_suberino', array( $this, 'add_new_suberino' ));

    add_action('wp_ajax_add_design_to_sub', array( $this, 'add_design_to_sub' ));
    add_action('wp_ajax_nopriv_add_design_to_sub', array( $this, 'add_design_to_sub' ));


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
      'teilnehmer',         // Admin page (or post type)
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
    register_post_type( 'teilnehmer',
    // CPT Options
        array(
            'labels' => array(
                'name' => 'Teilnehmer',
            ),
            'public'    => false,
            'show_ui'            => true,
		        'show_in_menu'       => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'teilnehmer'),
            'supports' => array('title','thumbnail')
        )
    );
  }

  /*
  * show Subscriber Info
  */
  function showSubInformation($field, $value, $subID) {


    switch($field) {
      case 'vorname':
        $label = "Vorname";
        $value = $value[0];
        break;
      case 'nachname':
        $label = "Nachname";
        $value = $value[0];
        break;
      case 'email':
        $label = "E-Mail";
        $value = $value[0];
        break;
      case 'phone':
        $label = "Telefon";
        $value = $value[0];
        break;
      case 'sfl_wall_picture':
        $label = "Wall Picture";
        $value = '<img style="margin-top:25px;" src="'.$value[0].'" />';
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

    // Frontend Wall
    add_shortcode( 'sfliferadio_wall', array(&$this,'sfliferadio_wall_function') );

	}

  public function sfliferadio_wall_function($atts)
  {

    // Alle Teilnehmer
    $args = array(
    	'posts_per_page'   => -1,
    	'offset'           => 0,
    	'category'         => '',
    	'category_name'    => '',
    	'orderby'          => 'date',
    	'order'            => 'DESC',
    	'include'          => '',
    	'exclude'          => '',
    	'meta_key'         => '',
    	'meta_value'       => '',
    	'post_type'        => 'teilnehmer',
    	'post_mime_type'   => '',
    	'post_parent'      => '',
    	'author'	   => '',
    	'author_name'	   => '',
    	'post_status'      => 'publish',
    	'suppress_filters' => true
    );
    $posts_array = get_posts( $args );

    ob_start();
    ?>
    <div class="sfl_wall_sub_container">
      <center>
        <div class="sfl_nav_left">
          <img width="50px" src="<?php echo sfliferadio_url . "templates/".sfliferadio_template. "/img/pfeillinks.png"; ?>" />
        </div>
        <div class="sfl_wall_sub_container_inner">
          <div class="sfl_wall_sub_container_inner_block">
            <?php
            foreach ($posts_array as $teilnehmer) {

              ?>
              <div class="sfl_wall_sub_single">
                <img width="150" src="<?php echo $teilnehmer->sfl_wall_picture;?>" />
              </div>
              <?php
            }
            ?>
          </div>
        </div>
        <div class="sfl_nav_right">
          <img width="50px" src="<?php echo sfliferadio_url . "templates/".sfliferadio_template. "/img/pfeilrechts.png"; ?>" />
        </div>
      </center>
    </div>
    <?php
    //assign the file output to $content variable and clean buffer
		$content = ob_get_clean();
		return  $content;
  }

  public function sfliferadio_form_function($atts)
  {
    ob_start();
    ?>
    <script type="text/javascript" src="http://canvg.github.io/canvg/canvg.js"></script>
    <div class="sfl_container">
      <form id="sfl_form_container" method="POST">
        <div class="sfl_container_data sfl_box">
          <div class="sfl_input_field">
            <label>Vorname</label>
            <input type="text" name="vorname" id="vorname" maxlength="15" required="" />
          </div>
          <div class="clear"></div>
          <div class="sfl_input_field">
            <label>Nachname</label>
            <input type="text" name="nachname" id="nachname" required=""/>
          </div>
          <div class="clear"></div>
          <div class="sfl_input_field">
            <label>Telefon</label>
            <input type="text" name="telefon" id="telefon" required=""/>
          </div>
          <div class="clear"></div>
          <div class="sfl_input_field">
            <label>E-Mail</label>
            <input type="email" name="email" id="email" required=""/>
          </div>
          <div class="clear"></div>
        </div>
        <div class="sfl_container_wunsch sfl_box">
          <div class="sfl_heading center">
            2 x Gehalt
          </div>
          <div class="sfl_wunsch_input center">
            = 1 x
            <input type="text" name="wunsch" id="wunsch" maxlength="14" required=""/>
          </div>
          <?php $nonce = wp_create_nonce( 'user-submit-form' ); ?>
          <input type="hidden" id="form_nonce" value="<?php echo $nonce;?>">
          <input type="submit" value="weiter" />
        </div>
      </form>
      <div class="svg_edit_container">
        <svg id="mySVG" version="1.1"  xmlns="http://www.w3.org/2000/svg" class="designer_svg" width="1200" height="627" viewBox="0 0 1200 627" preserveAspectRatio="xMidYMid meet">
            <g class="image-layer">
              <image id="default_fb_image" xlink:href="<?php echo sfliferadio_url . "templates/".sfliferadio_template. "/img/userpost_default.jpg"; ?>" width="1200" height="627" x="0" y="0"/>
            </g>
            <g class="text-layer">
              <text x="650" y="378" font-family="XXMarker" id="fb_image_text" font-size="75">

              </text>
            </g>
        </svg>
        <svg id="mySVGWall" version="1.1"  xmlns="http://www.w3.org/2000/svg" class="designer_svg" width="250" height="250" viewBox="0 0 250 250" preserveAspectRatio="xMidYMid meet">
            <g class="image-layer">
              <image id="default_fb_image" xlink:href="<?php echo sfliferadio_url . "templates/".sfliferadio_template. "/img/wall_default_ohneborder.jpg"; ?>" width="250" height="250" x="0" y="0"/>
            </g>
            <g class="text-layer">
              <text text-anchor="middle" x="125" y="50" font-family="Rams" id="wall_image_name" font-size="30">
              </text>

              <text x="95" y="165" font-family="XXMarker" id="fb_image_text_wall" font-size="20">
              </text>
            </g>
        </svg>
        <canvas id="svg-canvas" width=1200 height=627></canvas>
        <canvas id="svg-canvas-wall" width=1200 height=627></canvas>
        <img id="svg-img" />
        <img id="svg-img-wall" />
      </div>

      <div class="finish_process_sub_teilnahme sfl_box">
        <div class="sfl_heading center marginBottomMedium">
          Du hast es fast geschafft!
        </div>
        <div class="sfl_flow_text center marginBottomMedium">
          Du musst nur noch deinen Wunsch auf Facebook teilen,
          und du nimmst am Gewinnspiel teil.
        </div>
        <center>
          <button id="sfl_fb_share" >Teilen & Mitmachen </button>
        </center>
      </div>
      <div class="finished_sub_teilnahme sfl_box">
        <div class="sfl_heading center marginBottomMedium">
          Viel Glück!
        </div>
        <div class="sfl_flow_text center marginBottomMedium">
          Dein Wunsch ist eingetragen. <br/>
          Du wirst benachrichtigt, sobald du als Gewinner gezogen wurdest. <br />
          Die Gewinner werden nur unter denjenigen gezogen, die ihren Wunsch auf Facebook geteilt haben.
        </div>
      </div>
    </div>
    <?php
    //assign the file output to $content variable and clean buffer
		$content = ob_get_clean();
		return  $content;
  }


  /*
  * Add New Subscriper
  * Neuen Teilnehmer hinzufügen
  * Jeweils neuen Eintrag Custom Post Type Teilnehmer
  */
  function add_new_suberino()
  {

    if ( !wp_verify_nonce( $_REQUEST['nonce'], "user-submit-form")) {
      // TODO ERRORE zurücksenden
      exit("No naughty business please");
    }

    $vorname = $_REQUEST['vorname'];
    $nachname = $_REQUEST['nachname'];
    $telefon = $_REQUEST['telefon'];
    $email = $_REQUEST['mail'];





    // Neuen Teilnehmer hinzufügen
    $new_sub = array(
      'post_title'    => wp_strip_all_tags( $vorname ),
      'post_status'   => 'publish',
      'post_type'     => 'teilnehmer',
      'post_author'   => 1,
    );

    // Teilnehmer hinzufügen
    $teilnehmer_id = wp_insert_post( $new_sub );

    // Alle Daten dem Teilnehmer zuordnen
    if($teilnehmer_id !== 0) {
      update_post_meta($teilnehmer_id, "vorname", $vorname);
      update_post_meta($teilnehmer_id, "nachname", $nachname);
      update_post_meta($teilnehmer_id, "email", $email);
      update_post_meta($teilnehmer_id, "phone", $telefon);

      echo $teilnehmer_id;
    }
    die();
  }

  public function add_design_to_sub()
  {
    $teilnehmer = $_REQUEST['teilnehmer_id'];
    $imageSrc = $_REQUEST['img_src'];
    $imageSrcWall = $_REQUEST['img_src_wall'];

    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageSrc));

    $wp_upload_dir = wp_upload_dir();
    $upDir = wp_upload_dir();



    file_put_contents($upDir['path'].'/'.$teilnehmer.'.png', $data);
    $picUrl = $upDir['url'].'/'.$teilnehmer.'.png';


    // $filename should be the path to a file in the upload directory.
    $filename = $upDir['path'].'/'.$teilnehmer.'.png';

    // The ID of the post this attachment is for.
    $parent_post_id = $teilnehmer;

    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $filename ), null );

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    // Prepare an array of post data for the attachment.
    $attachment = array(
    	'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
    	'post_mime_type' => $filetype['type'],
    	'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
    	'post_content'   => '',
    	'post_status'    => 'inherit'
    );

    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    set_post_thumbnail( $parent_post_id, $attach_id );

    echo $wp_upload_dir['url'] . '/' . basename( $filename );


    $this->add_wall_picture_to_sub($imageSrcWall, $teilnehmer);

    die();
  }

  public function add_wall_picture_to_sub($imageSrc, $teilnehmer)
  {

    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageSrc));

    $wp_upload_dir = wp_upload_dir();
    $upDir = wp_upload_dir();

    file_put_contents($upDir['path'].'/'.$teilnehmer.'Wall.png', $data);
    $picUrl = $upDir['url'].'/'.$teilnehmer.'Wall.png';


    // $filename should be the path to a file in the upload directory.
    $fileUrl = $upDir['url'].'/'.$teilnehmer.'Wall.png';

    update_post_meta($teilnehmer, 'sfl_wall_picture', $fileUrl);


  }

}

$key = "shortcode";
$this->{$key} = new SFLshortCode();
