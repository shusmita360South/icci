<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldThumb extends JFormField
{

	protected $type = 'thumb';

	protected function getInput()
	{
		# settings
		$doc       = JFactory::getDocument();
		$component = JRequest::getVar('option'); 
		$fieldsets = $this->form->getField($this->fieldname);
		$value	   = $this->form->getValue($this->fieldname);
			
		#default size
		if($this->element['width'] && $this->element['height']) {
			$width  = $this->element['width'];
			$height = $this->element['height'];
		} else {
			$width  = 500;
			$height = 250;
		}
		
		# add stylesheet
		$doc->addStyleSheet( 'components/'.$component.'/assets/css/thumbnail.css' );
		# add script
		$doc->addScript( 'components/'.$component.'/assets/js/thumbnail.js' );
		
		
		$html = '';
		$html .= '<div class="dropzone '.$this->fieldname.'" data-width="'.$width.'" data-height="'.$height.'" data-url="components/'.$component.'/assets/thumbnail.php"';
		
		if($value) :
			$html .= 'data-image="'.$value.'">';
		else :
			$html .= '>';
		endif;
		
		$html .= '<input type="file" name="thumb" /></div>';
		$html .= '<input type="text" name="'.$this->name.'" id="jform_'.$this->fieldname.'" class="inputbox input-xlarge drop-input" value="'.$value.'" style="display:none!important;" />';
		$html .= '<script>jQuery(".'.$this->fieldname.'").html5imageupload({ onAfterProcessImage: function() { jQuery("#jform_'.$this->fieldname.'").val(jQuery(this.element).data("name")); }, onAfterCancel: function() { jQuery("#jform_'.$this->fieldname.'").val(""); } });</script>';
		
		return $html;
  
	}
}
?>