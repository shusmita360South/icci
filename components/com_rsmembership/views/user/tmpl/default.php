<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

JText::script('COM_RSMEMBERSHIP_THERE_WAS_AN_ERROR');
?> 

<div class="item-page">
	<?php if ($this->params->get('show_page_heading', 1)) { ?>
	<div class="page-header">
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	</div>
	<?php } ?>

	<form method="post" class="rsmembership_form form form-horizontal" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=validateuser'); ?>" name="membershipForm" onsubmit="return RSMembership.subscribe.validate_subscribe(this);" id="rsm_user_form">
		<?php $this->field->startFieldset('', 'rsmembership_form_table input'); ?>
		<?php if ($this->fields) { ?>
			<?php foreach ($this->fields as $field) { ?>
				<?php echo  $this->field->showField($field[0], $field[1]); ?>
			<?php } ?>
		<div class="form-actions">
			<button type="submit" class="button btn btn-success pull-right"><?php echo JText::_('COM_RSMEMBERSHIP_SAVE'); ?></button>
		</div>
		<?php } ?>
        <?php if ($this->allow_self_anonymisation) { ?>
            <button type="button" class="btn btn-danger pull-left" onclick="RSMembership.removeData(this);"><?php echo JText::_('COM_RSMEMBERSHIP_REMOVE_DATA_AND_CLOSE_ACCOUNT'); ?></button>
        <?php } ?>
		<?php echo $this->field->endFieldset(); ?>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value="validateuser" />
	</form><!-- rsm_user_form -->
</div>

<?php if ($this->allow_self_anonymisation) { ?>
<div id="rsmembership_remove_data_and_close_account">
    <div class="alert alert-warning">
        <p><?php echo JText::_('COM_RSMEMBERSHIP_REMOVE_DATA_AND_CLOSE_ACCOUNT_SURE'); ?></p>
        <p><strong><?php echo JText::_('COM_RSMEMBERSHIP_REMOVE_DATA_AND_CLOSE_ACCOUNT_SURE_CONT'); ?></strong></p>
        <p><button type="button" onclick="RSMembership.requestRemoveData(this);" class="btn btn-danger"><?php echo JText::sprintf('COM_RSMEMBERSHIP_YES_SEND_ME_A_LINK', $this->email); ?></button></p>
    </div>
</div>
<?php
}

echo RSMembershipHelper::renderMagnificPopup('rsmembershipModal', array(
	'url' 	   => false,
	'height'   => 400
));