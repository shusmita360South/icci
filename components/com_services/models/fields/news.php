<?php
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class Params {
   private $fields;
   public function __construct($fields) {
      $this->fields = $fields;
   }
   public function get($param) {
      foreach($this->fields as $field) {
         if ( $field->name == 'jform[params]['.$param.']' || $field->name == 'jform[params]['.$param.'][]' ) {
            return $field->value;
         }
      }
   }
   
}

class JFormFieldServices extends JFormField
{
	protected $type = 'services';

	protected function getInput()
	{
		$params = new Params($this->form->getFieldset());
		$strVal	= $params->get('pid');
		
		# Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->clear();
		$query->select('id,title');
		$query->from('#__services_items');
		$query->where('state=1');
		$query->order('title asc');
		$db->setQuery($query);
		
		$result = $db->loadObjectList();
		
		$options		= array();
		$options[0]		= JHtml::_('select.option', '0', '- Select Services -');

		foreach($result as $item) :
			# build items
			$options[]	= JHtml::_('select.option', $item->id, $item->title);
		endforeach;
		
		$selectlist  = '';
		$selectlist .= '<select name="'.$this->name.'" id="'.$this->name.'" class="inputbox" size="1">';
		$selectlist .= JHtml::_('select.options', $options, 'value', 'text', $strVal, true);
		$selectlist .= '</select>';

		return $selectlist;
	}
}