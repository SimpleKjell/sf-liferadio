<?php
class SFLAdmin extends SFLCommon
  var $options;
  function __construct() {
		/* Priority actions */
    //add_action('wp_ajax_delete_donation', array( $this, 'delete_donation' ));
	}
  /*  if ( !wp_verify_nonce( $_REQUEST['nonce'], "delete_donation_nonce")) {
  function add_admin_styles()
    //wp_register_style( 'prbreakfast_font_awesome', sfprbreakfast_url.'libs/font-awesome/font-awesome.min.css');
  }
  // Menüs hinzufügen
  /*
    ?>
  }
  /*
    {
      if ($key != 'submit')
      {
        $this->sfliferadio_set_option($key, $value);
      }
    }

     $this->options = get_option('sfliferadio_options');
      if ( isset ( $_GET['tab'] ) ) {
      foreach( $tabs as $tab => $name ) :
          $links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->slug."&tab=$tab'>$name </a>";
        else :
          $links[] = "<a class='nav-tab' href='?page=".$this->slug."&tab=$tab'>$name </a>";
        endif;
      foreach ( $links as $link )
  }
   $screen = get_current_screen();
   if( strstr($screen->id, $this->slug ) )
     if(isset($this->tabs[$tab]))
 }
 /*
}

$key = "sfladmin";
$this->{$key} = new SFLAdmin();