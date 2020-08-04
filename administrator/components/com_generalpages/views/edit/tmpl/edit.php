<?php
/**
 * @version     1.0.0
 * @package     com_generalpages
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
$document->addStyleSheet('components/com_generalpages/assets/css/generalpages.css');
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

<form action="<?php echo JRoute::_('index.php?option=com_generalpages&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="edit-form" class="form-validate">
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
          <div class="control-label"><?php echo $this->form->getLabel('template'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('template'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('intro'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('intro'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('image1'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('image1'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('videolink'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('videolink'); ?></div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('relatedevents'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('relatedevents'); ?></div>
        </div>
        <!--
        <div class="control-group">
          <div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
          <div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
        </div>
        -->

        <ul class="nav nav-tabs" id="myTabTabs">
          <li class="active"><a href="#layout1" data-toggle="tab">Layout 1</a></li>
          <li class=""><a href="#layout2" data-toggle="tab">Layout 2</a></li>
          <li class=""><a href="#layout3" data-toggle="tab">Layout 3</a></li>
          <li class=""><a href="#layout4" data-toggle="tab">Layout 4</a></li>
          <li class=""><a href="#layout5" data-toggle="tab">Layout 5</a></li>
        </ul>
        <div class="tab-content" id="myTabContent">

          <div id="layout1" class="tab-pane active">
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('subtitle1'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('subtitle1'); ?></div>
            </div>

            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('body1'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('body1'); ?></div>
            </div>
          </div>

          <div id="layout2" class="tab-pane">
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('subtitle2'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('subtitle2'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('image2'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('image2'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('body2'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('body2'); ?></div>
            </div>
            
          </div>
          <div id="layout3" class="tab-pane">
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('twocolsbody'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('twocolsbody'); ?></div>
            </div>
          </div>

          <div id="layout4" class="tab-pane">
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('cardstitle'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('cardstitle'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('cardsintro'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('cardsintro'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('cards'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('cards'); ?></div>
            </div>
          </div>

          <div id="layout5" class="tab-pane">
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('cardstitle2'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('cardstitle2'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('cardsintro2'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('cardsintro2'); ?></div>
            </div>
            <div class="control-group">
              <div class="control-label"><?php echo $this->form->getLabel('cards2'); ?></div>
              <div class="controls"><?php echo $this->form->getInput('cards2'); ?></div>
            </div>
          </div>


        </div>


      </fieldset>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?> </div>
</form>
