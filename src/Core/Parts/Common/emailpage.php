<?php
//--------------------------------------------------------------------------------
// Copyright 2005 - 2009 Arketec Limited. (http://www.arketec.com)
// Released under the GPL license (http://www.gnu.org/licenses/gpl.html)
//--------------------------------------------------------------------------------
/**
 * @package		ARK
 * @subpackage	Parts
 * @version    $Id: emailpage.php,v 1.1.1.1 2006/04/13 01:11:01 cambell Exp $
 * @author		  Cambell Prince <cambell@arketec.com>
 * @link				http://www.arketec.com
 * @see
 */

/**
 */
require_once(SGF_CORE . 'Controller/Part.php');

/**
 * A EmailPage is a controller that produces a View.
 *
 * Note that EmailPage is a controller; it accesses the model for update based on inputs in the event,
 * and selects data for display by the template (the view).
 *
 * Pages only render if they match the action path.
 * @package		ARK
 * @subpackage	Parts
 */
class EmailPage extends Part {
	/**
	 * @var float
	 */
	var $startTime_;

	/**
	 * @var string
	 */
	var $email_;

	/**
	 * @var EmailOutput
	 */
	var $emailOutput_;

	/**
	 * @var ArraySpac
	 */
	var $space_;

	/**
	 * Constructor
	 * Pages always render only if they follow the action path.
	 * @param string
	 * @param View
	 * @param EmailOutput
	 * @param string
	 * @access public
	 */
	function EmailPage($name, $view, $emailOutput, $context = null) {
		$this->Part($name, $view, null, DECK_FollowAction, $context);
		$this->startTime_ = $this->getTime();
		$this->email_ = new Email();
		$this->emailOutput_ = $emailOutput;
		$this->space_ = new ArraySpace();
	}

	/**
	 * Posts and handles an EVT_Render event to generate the email.
	 * @param string Optional action path to follow during render
	 */
	function render($action = '/') {
		$t = Traversal::singleton('email');
		$state = new ArraySpace();
		$t->stateSetRoot($state);
		$e = new Event(EVT_Render, new ActionPath($action));
		$t->begin($e->getActionPath());
		$this->handle($e, $t);
	}

	/**
	 * Sends the email
	 * Called by onRenderLeave in the default implementation.
	 * @see onRenderLeave
	 */
	function send() {
		$ret = $this->email_->send($this->emailOutput_);
		if (!$ret) {
			Error::log(__FILE__, __LINE__, $this->email_->story_);
		}
	}

	/**
	 * Called by Traversal::enter prior to rendering the parts of this page.
	 * @access protected
	 */
	function onRenderEnter($t) {
		// Set the view in the traversal
		$t->viewSet($this->_view);

		// To set our own view data in the traversal
		parent::onRenderEnter($t);
	}

	/**
	 * Called by Traversal::leave after rendering the parts of this page.
	 * This delivers the output to the output stream by calling patTemplate::displayParsedTemplate.
	 * @access protected
	 */
	function onRenderLeave($t) {
		$totalTime = $this->getTime() - $this->startTime_;
		$this->_view->pushText('TIME', round($totalTime, 3));
		// Render the template
		$plain = $this->_view->renderToString('plain'); // TODO: this is wrong. Should have two views, can have a view that has two views and manages that if we want though.
		$html = $this->_view->renderToString('html');
		$this->email_->setPlain($plain);
		$this->email_->setHTML($html);
		$this->send();
	}

	/**
	 * Gets processor time.
	 * @access private
	 */
	function getTime() {
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

}

?>
