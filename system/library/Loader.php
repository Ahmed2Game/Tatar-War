<?php

require_once LIBRARY_DIR . "functions.php";
require_once LIBRARY_DIR . "error.functions.php";
require_once CONSTANTS_DIR . "constants.php";
require_once LIBRARY_DIR . "Controller.php";

/**
 * Load and init all the classes of the framework
 */
class Loader
{
    protected static $instance;   // class instance for singleton calls

    // controller settings
    protected static $controllers_dir = CONTROLLERS_DIR,
        $controller_extension = CONTROLLER_EXTENSION,
        $controller_class_name = CONTROLLER_CLASS_NAME,
        $models_dir = MODELS_DIR;

    // ajax variables
    protected $ajax_mode = false,
        $load_javascript = false,
        $load_style = false;

    protected $var,                   // variables assigned to the page layout
        $load_area_array = array();   // variables assigned to the page layout

    // selected controller
    public $selected_controller = null,
        $selected_action = null,
        $selected_params = null,
        $loaded_controller = array();


    /**
     * Return the object Loader
     * @return Loader
     */
    static function get_instance()
    {
        if (!self::$instance)
            self::$instance = new self;

        return self::$instance;
    }


    public function auto_load_controller()
    {
        // load the Router library and get the URI
        require_once LIBRARY_DIR . "Router.php";
        $router = new Router;
        $this->selected_controller_dir = $controller_dir = $router->get_controller_dir();
        $this->selected_controller = $controller = $router->get_controller();
        $this->selected_action = $action = $router->get_action();
        $this->selected_params = $params = $router->get_params();

        $this->load_controller($controller, $action, $params);
    }

    /**
     * Load the content selected by the URI and save the output in load_area.
     * Leave the parameters null if you want to load automatically the controllers
     *
     * @param string $controller selected controller
     * @param string $action selected action
     * @param string $params array of the selected actions
     * @param string $load_area selected load area where the controller is rendered
     */
    public function load_controller($controller = null, $action = null, $params = array(), $load_area = "center")
    {
        // transform the controller string to capitalized. e.g. user => user, news_list => news_list
        $controller = implode("_", array_map("strtolower", explode("_", $controller)));


        // include the file
        if (file_exists($controller_file = self::$controllers_dir . "/$controller." . self::$controller_extension))
            require_once $controller_file;
        else
            return header("Location: " . URL . "index.php");


        // define the class name of the controller
        $class = $controller . self::$controller_class_name;

        // check if the controller class exists
        if (class_exists($class))
            $controller_obj = new $class($this);
        else
            return header("Location: " . URL . "index.php");


        if ($action) {

            // start the output buffer
            ob_start();

            // call the method filter_before
            call_user_func_array(array($controller_obj, "filter_before"), $params);

            // call the selected action
            call_user_func_array(array($controller_obj, 'index'), $params);

            //call the method filter_after
            call_user_func_array(array($controller_obj, "filter_after"), $params);

            $html = ob_get_contents();

            // close the output buffer
            if ($controller != 'xsollaback') {
                ob_end_clean();
            }

            $this->loaded_controller[] = array("controller" => $controller);

            echo $html;
        }
        if ($action != 'index') {
            return header("Location: " . URL . "login");
        }
    }


    /**
     * Load the model
     *
     * @param string $model selected model
     * @param string $action selected action
     * @param array $params parameters
     * @param string $assign_to variable where you assign the result of the model
     */
    public function load_model($model)
    {

        // load the model class
        require_once LIBRARY_DIR . "Model.php";

        // transform the model string to capitalized. e.g. user => User, news_list => News_List
        $model = implode("_", array_map("ucfirst", explode("_", $model)));

        // include the file
        if (file_exists($file = self::$models_dir . $model . ".php"))
            require_once $file;
        else {
            trigger_error("MODEL: FILE <b>{$file}</b> NOT FOUND ", E_USER_WARNING);
            return false;
        }

        // class name
        $class = $model . "_Model";

        // test if the class exists
        if (class_exists($class))
            return new $class();
        else {
            trigger_error("MODEL: CLASS <b>{$model}</b> NOT FOUND", E_USER_WARNING);
            return false;
        }

    }


    /**
     *
     * @param <type> $helper
     */
    public function load_helper($helper)
    {
        if (is_array($helper))
            array_map(array($this, "load_helper"), $helper);
        else
            require_once LIBRARY_DIR . $helper . ".php";
    }


    /**
     * Load the settings file
     */
    public function init_settings($config_dir = CONFIG_DIR, $settings_file = "settings.php")
    {
        require_once $config_dir . $settings_file;
    }


    /**
     * Init the database class
     */
    public function init_db($name)
    {
        require_once LIBRARY_DIR . "DB.php";
        db::init($name);
    }

    public function init_db2()
    {
        require_once LIBRARY_DIR . "DB2.php";
        db2::init();
    }


    /**
     * Init the session class
     */
    public function init_session()
    {
        require_once LIBRARY_DIR . "Session.php";
        session::get_instance();
    }


    /**
     * Init the language
     *
     * @param string $lang_id selected language
     */
    public function init_language($lang_id = "ar")
    {
        if (file_exists(LANGUAGE_DIR . $lang_id . '/lang.php')) {
            require_once LANGUAGE_DIR . $lang_id . '/lang.php';

            if (!defined('LANG_ID')) {
                define("LANG_ID", $lang_id);
            }
        } else {
            $this->page_not_found = true;
        }
    }


    /**
     * Init the theme
     *
     * @param string $theme selected theme
     */
    public function init_theme($theme = null)
    {
        // Init the view class
        require_once LIBRARY_DIR . "View.php";

        // if theme dir is a directory
        if (is_dir($theme_dir = VIEWS_DIR . $theme . ((!$theme or substr($theme, -1, 1)) == "/" ? null : "/"))) {

            if (!defined('THEME_DIR')) {
                define("THEME_DIR", $theme_dir);
            }
            View::configure("tpl_dir", THEME_DIR);
            View::configure("cache_dir", CACHE_DIR);
            View::configure("base_url", URL);
        } else {
            $this->_draw_page_not_found("theme_not_found");
        }
    }


    /**
     * Init eventual Javascript useful for the application.
     * Extends the class Loader if you have to load more javascript
     */
    public function init_js()
    {
        add_javascript("var url = '" . URL . "';");
    }


    /**
     * Assign value to the page layout
     *
     * @param mixed $variable
     * @param string $value
     */
    public function assign($variable, $value = null)
    {
        if (is_array($variable))
            $this->var += $variable;
        else
            $this->var[$variable] = $value;
    }


    /**
     * Get the selected controller dir
     * @return string
     */
    public function get_selected_controller_dir()
    {
        return $this->selected_controller_dir;
    }


    /**
     * Get the selected controller
     * @return string
     */
    public function get_selected_controller()
    {
        return $this->selected_controller;
    }


    /**
     * Get the selected controller
     * @return string
     */
    public function get_selected_action()
    {
        return $this->selected_action;
    }


    /**
     * Configure the settings,
     * settings are static variable to setup this class
     *
     * @param string $setting setting name
     * @param string $value value of the setting
     *
     */
    static function configure($setting, $value)
    {
        if (is_array($setting))
            foreach ($setting as $key => $value)
                self::configure($key, $value);
        else if (property_exists(__CLASS__, $setting))
            self::$$setting = $value;
    }


    protected function __construct()
    {
    }
}