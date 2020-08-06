<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RSMembershipViewMembership extends JViewLegacy
{
	public function display($tpl = null)
	{
		$this->item 			= $this->get('Item');
		$this->ordering 		= $this->get('SharedOrdering');
		$this->sharedPagination = $this->get('sharedPagination');

		$this->item->attachments = $this->get('attachments');
		$this->item->attachmentsPagination = $this->get('attachmentsPagination');
		$email_type = JFactory::getApplication()->input->get('email_type', '', 'string');
		if ($email_type)
		{
			$this->email_type 				   = $this->escape($email_type);
			$this->item->attachments 		   = isset($this->item->attachments[$email_type]) 		    ? $this->item->attachments[$email_type] 		  : array();
			$this->item->attachmentsPagination = isset($this->item->attachmentsPagination[$email_type]) ? $this->item->attachmentsPagination[$email_type] : null;
		}

		parent::display($tpl);
	}
}