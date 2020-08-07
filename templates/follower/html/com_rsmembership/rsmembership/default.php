<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
$app        = JFactory::getApplication();
$menu       = $app->getMenu();
$active     = $menu->getActive();
$params   = new JRegistry;
$params->loadString($active->params);
$pageTitle = $params->get('page_heading');

require_once JPATH_BASE . '/templates/home/assets/helper.php';
$helper = new TempHelper();
$extras = $helper->getMemberExtras();


?>
<div class="page-header-2 light-bg section-padding-top section-padding-bottom-half">
  <div class="grid-container-medium">
    
    <h1><?php echo $pageTitle;?></h1>
    <h5>View the benefits below to see which membership is the right one</h5>
  </div>
</div>
<div class="member-levels-list section-padding-bottom">
	
	<div class="grid-container">
		<div uk-grid class="uk-grid-collapse card-member-outer">
			<div class="uk-width-1-1 uk-width-1-4@s member-benefit-col"><h3>Member Benefits</h3></div>
			<?php 
			$i = 1; 
			foreach ($this->items as $item) 
			{
				$link  		= JRoute::_( RSMembershipRoute::Membership($item->id, $this->Itemid) );
				$apply_link = JRoute::_( RSMembershipRoute::Subscribe( $item->category_id, $item->category_name, $item->id, $item->name, $this->Itemid ) );

				$price 		= RSMembershipHelper::getPriceFormat($item->price);
				$image 		= !empty($item->thumb) ? JHtml::image('components/com_rsmembership/assets/thumbs/'.$item->thumb, $item->name, 'class="rsm_thumb"') : '';

				$placeholders = array(
					'{price}' 	=> $price,
					'{buy}'		=> '',
					'{extras}'  => '',
					'{stock}'	=> ($item->stock > -1 ? ( $item->stock == 0 ? JText::_('COM_RSMEMBERSHIP_UNLIMITED') : $item->stock) : JText::_('COM_RSMEMBERSHIP_OUT_OF_STOCK_PLACEHOLDER')) ,
					'<hr id="system-readmore" />' => ''
				);
				
				// Trigger content plugins if enabled
				if (RSMembershipHelper::getConfig('trigger_content_plugins')) {
					$item->description = JHtml::_('content.prepare', $item->description);
				}
				
				$item->description = str_replace(array_keys($placeholders), array_values($placeholders), $item->description);
				?>
					<div class="uk-width-1-1 uk-width-1-4@s rsm_container<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
						<div class="thumbnail card-member center">
							<?php echo $image; ?>
							<div class="caption">
								<h3 class="rsm_title"><a href="<?php echo $link; ?>"><?php echo $item->name; ?></a></h3>
								
								<?php echo $item->description; ?>
								<h2 class="rsm_title"><?php echo $price; ?></h2>
								<p class="subtitle">Annual Membership</p>
								<div class="clearfix"></div>

										

										<?php if (($this->params->get('show_buttons', 2) == 2 || $this->params->get('show_buttons', 2) == 3) && $item->stock != -1) { ?>
											<a href="<?php echo $apply_link; ?>" class="button uk-margin-medium-top">Join Now</a>
										<?php } ?>
									
								<div class="clearfix"></div>
							</div>
						</div>
					</div>

				<?php $i++; ?>
			<?php } ?>
		</div>
	</div>
	<div class="grid-container">
		<div uk-grid class="uk-grid-collapse card-member-benefit-outer ">
			<?php foreach ($extras as $extra) :?>
				<div class="uk-width-1-1 uk-width-1-4@s">
					
					<p class="light-bg"><?php echo $extra->name;?></p>
				</div>
				<?php 
				$i = 1; 
				foreach ($this->items as $item) :
					$itemExtra = $helper->getMemberItemExtra($item->id,$extra->id );

				?>
						<div class="uk-width-1-1 uk-width-1-4@s">
							<div class="thumbnail card-member-benefit center">
								<?php print_r($itemExtra);?>
							</div>
						</div>

					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>