<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

// set description if required
if (isset($this->fieldset->description) && !empty($this->fieldset->description)) { ?>
    <div class="com-rsmembership-tooltip"><?php echo JText::_($this->fieldset->description); ?></div>
<?php } ?>
<?php

$content = $this->field->startFieldset(JText::_($this->fieldset->label), 'rs_fieldset adminform', false);

$this->fields = $this->form->getFieldset($this->fieldset->name);
foreach ($this->fields as $field) {
    $content .= $this->form->renderField($field->fieldname);
}

$html_placeholders = $this->field->showField(' ', JText::sprintf('COM_RSMEMBERSHIP_PLACEHOLDERS_CAN_BE_USED', $this->getPlaceholders('invoice')).JText::_('COM_RSMEMBERSHIP_PLACEHOLDERS_INVOICE_IF_CONDITIONS'), false);

$html_placeholders = str_replace('<div class="control-group">', "<div class='control-group' data-showon='[{\"field\":\"jform[use_membership_invoice]\",\"values\":[\"1\"],\"sign\":\"=\",\"op\":\"\"},{\"field\":\"jform[membership_invoice_type]\",\"values\":[\"custom\"],\"sign\":\"=\",\"op\":\"AND\"}]'>", $html_placeholders);

$content .= $html_placeholders;
$content .= $this->field->endFieldset(false);

echo $content;
?>