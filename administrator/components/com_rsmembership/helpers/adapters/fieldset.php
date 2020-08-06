<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

class RSFieldset {
	public function startFieldset( $legend='', $class='adminform form-horizontal', $display = true ) {
		$return = '<fieldset class="' . $class . '">';
		if ($legend) {
			$return .= '<p class="rule-desc">' . $legend . '</p>';
		}

		if ($display)
			echo $return;
		else
			return $return;
	}

	public function showField( $label, $input, $display = true ) {
		$return = '<div class="control-group">';
		if ($label) {
			$return .= '<div class="control-label">'. $label .'</div>';
		}
		$return .= '<div' . ( $label ? ' class="controls"' : '' ) .'>'. $input .'</div>';
		$return .= '</div>';

		if ($display)
			echo $return;
		else
			return $return;
	}

	public function endFieldset( $display = true ) {
		$return = '</fieldset>';

		if ($display)
			echo $return;
		else
			return $return;
	}
}