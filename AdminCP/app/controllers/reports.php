<?php
/**
 * reports class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * Dashboard reports page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_core('Admin');
class Reports_Controller extends AdminController
{
    
    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();        
        $this->viewFile = "reports";
    }


    /**
     * Index Method 
     *
     * @return void
     */
    public function index()
    {
        if($_POST)
        {
            
        }
        else
        {        
            if(is_get('page'))
            {
                if(get('page') == 'show') {
                    $this->viewData['page'] = 'show';
                }
                elseif(get('page') == 'read') {
                    $this->viewData['page'] = 'read';
                }
                else {
                    return header("Location: index.php");
                }
            }
            else
            {
                return header("Location: index.php");
            }
        }
    }

}
//end file 
?>
