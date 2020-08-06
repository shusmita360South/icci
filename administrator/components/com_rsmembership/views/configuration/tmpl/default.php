<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

//keep session alive while editing
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tabstate');
?>
<script type="text/javascript">
	function rsm_idev_check_connection()
	{
		Joomla.submitbutton("configuration.idevCheckConnection");
	}

    Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		
		if (pressbutton == "cancel")
		{
			Joomla.submitform(pressbutton);
			return;
		}

        if (!document.formvalidator.isValid(form))
        {
            return false;
        }
        
		Joomla.submitform(pressbutton);
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&view=configuration'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form form-horizontal" enctype="multipart/form-data" autocomplete="off">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php

	foreach ($this->fieldsets as $name => $fieldset) 
	{
		// add the tab title
		$this->tabs->addTitle($fieldset->label, $fieldset->name);

		if ($fieldset->name == 'permissions')
        {
            $content = $this->loadTemplate('permissions');
        }
        else
        {
            $content = $this->field->startFieldset(JText::_($this->fieldsets[$fieldset->name]->label), 'adminform form', false);

            $this->fields = $this->form->getFieldset($fieldset->name);
            foreach ($this->fields as $field) {
                $content .= $this->form->renderField($field->fieldname);
            }
			
			if ($fieldset->name == 'general_invoice')
			{
				$placeholders = array(
					'{membership}', '{category}', '{extras}', '{email}', '{name}', '{username}', '{total_price}', '{coupon}', '{discount_type}', '{discount_value}', '{payment}', '{transaction_id}', '{transaction_hash}', '{membership_from}', '{invoice_id}', '{tax_type}', '{tax_value}', '{invoice_transaction_table}', '{date_purchased}', '{site_name}', '{site_url}'
				);
				$content .= JText::sprintf('COM_RSMEMBERSHIP_PLACEHOLDERS_CAN_BE_USED', implode(', ', $placeholders)).JText::_('COM_RSMEMBERSHIP_PLACEHOLDERS_INVOICE_IF_CONDITIONS');
			}
			
            $content .= $this->field->endFieldset(false);
        }

		// add the tab content
		$this->tabs->addContent($content);
	}

	// render tabs
	$this->tabs->render();
	?>
		<div>
		<?php echo JHtml::_('form.token'); ?>
		<input type="hidden" name="option" value="com_rsmembership" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="configuration" />
		</div>
	</div>
</form>