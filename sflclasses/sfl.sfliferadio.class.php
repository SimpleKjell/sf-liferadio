<?php
/* Master Class*/class SFLiferadio{
  var $options;  var $current_page;  var $classes_array;
  public function __construct()	{		$this->current_page = $_SERVER['REQUEST_URI'];		$this->options = get_option('sfliferadio_options');  }
  // Init Funktion  public function plugin_init()  {
    /*Load Main classes*/    $this->set_main_classes();    $this->load_classes();
    /*Load Amin Classes*/    if (is_admin())    {      $this->set_admin_classes();      $this->load_classes();    }
    //Initial Settings    $this->intial_settings();
  }  // Alle Klassen in ein Array schreiben  public function set_main_classes()	{
    $this->classes_array = array(      "commmonmethods" =>"sfl.sfliferadio.common" ,      "shortocde" =>"sfl.sfliferadio.shortcodes" ,      //"form" =>"sfg.sfgewinnspiel.form" ,      //"sub" =>"sfg.sfgewinnspiel.sub" ,      //"register" =>"sfm.sfmusiker.register",      //"search" =>"sfm.sfmusiker.search",			//"activate" =>"dsdf.dsdfmembers.activate",      //"userpanel" =>"dsdf.dsdfmembers.user",			//"team" =>"dsdf.dsdfmembers.team",    );	}  // Alle Klassen laden  function load_classes()	{		foreach ($this->classes_array as $key => $class)		{			if (file_exists(sfliferadio_path."sflclasses/$class.php"))			{				require_once(sfliferadio_path."sflclasses/$class.php");			}		}
	}
  // Alle Admin Klassen  public function set_admin_classes()	{
    $this->classes_array = array(      "sfladmin" =>"sfl.sfliferadio.admin",		);
	}
  // Nach dem Laden der Klassen werden hier die Initial Settings getroffen.  public function intial_settings()	{
		// Styles und Scripts		add_action('wp_enqueue_scripts', array(&$this, 'add_front_end_styles'), 9);	}
  public function add_front_end_styles()	{    /* Bootstrap */		//wp_register_style( 'sfgewinnspiel_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');		//wp_enqueue_style('sfgewinnspiel_bootstrap');
		/* Font Awesome */		wp_register_style( 'sfliferadio_font_awesome', sfliferadio_url.'libs/font-awesome/font-awesome.min.css');		wp_enqueue_style('sfliferadio_font_awesome');    /* Slick Slider CSS*/    wp_register_style( 'sfliferadio_slick', sfliferadio_url.'libs/slick/slick.css');		wp_enqueue_style('sfliferadio_slick');    wp_register_style( 'sfliferadio_slick_theme', sfliferadio_url.'libs/slick/slick-theme.css');		wp_enqueue_style('sfliferadio_slick');    // slick.js    wp_register_script('slick-js', sfliferadio_url.'libs/slick/slick.min.js',array('jquery'));		wp_enqueue_script('slick-js');
		/* Custom style */		wp_register_style( 'sfliferadio_style', sfliferadio_url.'templates/'.sfliferadio_template.'/css/default.css');		wp_enqueue_style('sfliferadio_style');    // parsley.js    wp_register_script('parsley-js', sfliferadio_url.'libs/parsley/parsley.min.js',array('jquery'));		wp_enqueue_script('parsley-js');    //wp_register_script('canvg-js', sfgewinnspiel_url.'js/canvg.js',array('jquery'));		//wp_enqueue_script('canvg-js');
		wp_register_script('sfl-custom-js', sfliferadio_url.'js/custom.js',array('jquery', 'parsley-js'));		// ajaxurl mitgeben		wp_localize_script( 'sfl-custom-js', 'Custom', array('ajaxurl'  => admin_url( 'admin-ajax.php' ),'homeurl' => home_url(), 'upload_url' => admin_url('async-upload.php')));		wp_enqueue_script('sfl-custom-js');
	}
}