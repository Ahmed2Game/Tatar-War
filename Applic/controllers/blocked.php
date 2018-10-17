<?php
/**
 * Blocked class file.
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @copyright Copyright 2013
 * @license http://www.xtatar.com
 */

/**
 * user blocked page
 *
 * @author Abdulrahman Mohamed <abdokamal15@gmail.com>
 * @version $Id$
 * @package app.controllers
 * @since 1.0
 */
load_game_engine('Auth');
class Blocked_Controller extends AuthController
{

	/**
     * Constructor Method
     * This method defines view file && contentCssClass .
    */
	public function __construct()
	{
		parent::__construct();
		$this->viewFile = 'blocked';
		$this->viewData['contentCssClass'] = "plus";
	}


	/**
     * Index Method
     *
     * @return void
    */
	public function index()
	{
	    if ( $this->data['blocked_second'] <= 0 and $this->data['is_blocked'] == 0 )
        {
            $this->is_redirect = TRUE;
            redirect('village1');
        }
		$this->viewData['block_reason'] =  nl2br($this->data['blocked_reason']);
        $this->viewData['timeout'] =  secondsToString($this->data['blocked_second']);
	}
}
// end file
?>