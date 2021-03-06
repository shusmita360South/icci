<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class JFormFieldCouponcode extends JFormField {
	protected $type = 'Couponcode';

	public function getInput() 
	{
		return '<script type="text/javascript">
			function rsm_random_code()
			{
				var outputString = "";
				i = 0;
				while(i<5)
				{
					outputString += String.fromCharCode(65 + Math.round(Math.random() * 25));
					outputString += Math.floor(Math.random()*11);
					i++;
				}

				var form = document.adminForm;
				form.'.$this->id.'.value = outputString;
			}
		</script>
		<input type="text" name="'.$this->name.'" value="'.$this->value.'" class="'.$this->element['class'].'" id="'.$this->id.'"/>
		<button type="button" class="btn btn-info btn-small" onclick="rsm_random_code();">'.JText::_('COM_RSMEMBERSHIP_COUPON_GENERATE').'</button>';
	}
}		