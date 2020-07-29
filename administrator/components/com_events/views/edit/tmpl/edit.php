<?php
/**
 * @version     1.0.0
 * @package     com_events
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      360South Pty Ltd <tech@360south.com.au> - http://www.360south.com.au/
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_events/assets/css/events.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'edit.cancel'){
            Joomla.submitform(task, document.getElementById('edit-form'));
        }
        else{
            
            if (task != 'edit.cancel' && document.formvalidator.isValid(document.id('edit-form'))) {
                
                Joomla.submitform(task, document.getElementById('edit-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_events&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="edit-form" class="form-validate">
  <div class="row-fluid">
    <div class="span10 form-horizontal">
      <fieldset class="adminform">
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('subtitle'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('subtitle'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('intro'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('intro'); ?></div>
        </div>
      
        
        <!--  
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
        </div>
        -->
       
       
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('stime'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('stime'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('etime'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('etime'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('locationtitle'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('locationtitle'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('location'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('location'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('link'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('link'); ?></div>
        </div> 
        
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('memberfee'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('memberfee'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('nonmemberfee'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('nonmemberfee'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('host'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('host'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('partners'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('partners'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('faq'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('faq'); ?></div>
        </div> 

        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('relatedevents'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('relatedevents'); ?></div>
        </div> 
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('body'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('body'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('thumb'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('thumb'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('image'); ?></div>
        </div>
        
       
       
      </fieldset>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?> </div>
</form>
