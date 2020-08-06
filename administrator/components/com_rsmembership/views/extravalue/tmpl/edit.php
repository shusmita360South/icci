<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.tabstate');
?>

<form action="<?php echo JRoute::_('index.php?option=com_rsmembership'); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
<?php
foreach ($this->fieldsets as $name => $fieldset) 
{
	// add the tab title
	$this->tabs->addTitle($fieldset->label, $fieldset->name);

	// prepare the content
	$this->fieldset = $fieldset;

	$this->fields 	= $this->form->getFieldset($fieldset->name);
	$content = $this->loadTemplate($fieldset->name);

	// add the tab content
	$this->tabs->addContent($content);
}

// render tabs
$this->tabs->render();
?>
<?php echo JHtml::_( 'form.token' ); ?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo (int) $this->item->id; ?>" />
<!-- need this to for getRedirectToListAppend -->
<input type="hidden" name="extra_id" value="<?php echo JFactory::getApplication()->input->get('extra_id', 0, 'int'); ?>" />

</form>

<?php
//keep session alive while editing
JHtml::_('behavior.keepalive');
?>

<script type="text/javascript">
	var sharedItems = <?php echo (!empty($this->item->id) ? '1' : '0'); ?>;

	jQuery(window).load(function(){
		jQuery('#adminForm > .tab-content > div').each(function(index, elem){
			if (jQuery(elem).hasClass('active')) {
				if (sharedItems && index == 1) {
					jQuery('#toolbar-delete').css('display', 'inline-block');
					jQuery('#toolbar-publish').css('display', 'inline-block');
					jQuery('#toolbar-unpublish').css('display', 'inline-block');
				} else {
					jQuery('#toolbar-delete').hide();
					jQuery('#toolbar-publish').hide();
					jQuery('#toolbar-unpublish').hide();
				}
			}
		});
	});


	jQuery('#adminForm > #com-rsmembership-extra-values > li').each(function(index, elem){
		jQuery(elem).find('a').on('click', function(){
			jQuery('#tabposition').val(index);
			if (sharedItems && index == 1) {
				jQuery('#toolbar-delete').css('display', 'inline-block');
				jQuery('#toolbar-publish').css('display', 'inline-block');
				jQuery('#toolbar-unpublish').css('display', 'inline-block');
			} else {
				jQuery('#toolbar-delete').hide();
				jQuery('#toolbar-publish').hide();
				jQuery('#toolbar-unpublish').hide();
			}
		})
	});

</script>
