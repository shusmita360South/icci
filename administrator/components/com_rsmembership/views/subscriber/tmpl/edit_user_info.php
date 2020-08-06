<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=subscriber.edit&id='.(int) $this->item->user_id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
<?php
$this->fields 	= $this->form->getFieldset('main');

$this->field->startFieldset(JText::_($this->fieldsets['main']->label), 'adminform form');

$username_label = '<label id="u_username-lbl" for="username" class="hasTip" title="'.JText::_('Username').'">'.JText::_('Username').'</label>';
$username_field = (!$this->temp ? '<input type="text" name="u[username]" value="'.$this->escape($this->item->username).'" id="username" size="40" />' : $this->escape($this->item->username));
$this->field->showField($username_label, $username_field);

$name_label = '<label id="u_name-lbl" for="name" class="hasTip" title="'.JText::_('Name').'">'.JText::_('Name').'</label>';
$name_field = (!$this->temp ? '<input type="text" name="u[name]" value="'.$this->escape($this->item->name).'" id="name" size="40" />' : $this->escape($this->item->name));
$this->field->showField($name_label, $name_field);

$email_label = '<label id="u_email-lbl" for="email" class="hasTip" title="'.JText::_('Email').'">'.JText::_('Email').'</label>';
$email_field = (!$this->temp ? '<input type="text" name="u[email]" value="'.$this->escape($this->item->email).'" id="email" size="40" />' : $this->escape($this->item->email));
$this->field->showField($email_label, $email_field);
?>

<?php
foreach ($this->custom_fields as $cfield) {
    $this->field->showField($cfield[0], $cfield[1]);
}
$this->field->endFieldset();
?>

<?php echo JHtml::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="temp_id" value="<?php echo JFactory::getApplication()->input->get('temp_id', 0, 'int');?>" />

</form>

