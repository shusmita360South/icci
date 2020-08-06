<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__).'/adapters/input.php';

// Joomla! 3.0
if (!class_exists('JPane')) {
	class JPane {
		function getInstance() {
			return new JPane();
		}

		function startPane($id) {
			return JHtml::_('tabs.start', $id, array('useCookie' => 1));
		}

		function startPanel($text, $id) {
			return JHtml::_('tabs.panel', $text, $id);
		}

		function endPanel() {
			return '';
		}

		function endPane() {
			return JHtml::_('tabs.end');
		}
	}
}
