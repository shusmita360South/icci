<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2020 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');
?>
<div class="item-page">
    <?php if ($this->params->get('show_page_heading', 1)) { ?>
        <div class="page-header">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php } ?>

    <form action="<?php echo $this->action; ?>" method="post" name="adminForm" id="rsm_mymemberships_form">
        <?php if ( !empty($this->items) ) {
            $colspan = 6;
            ?>
            <table class="rsmembershiptable <?php echo $this->escape($this->params->get('pageclass_sfx')); ?> table table-stripped table-hovered">
                <?php if ($this->params->get('show_headings', 1)) { ?>
                    <tr>
                        <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" align="right" width="5%"><?php echo JText::_('#'); ?></th>
                        <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION'); ?></th>
                        <?php if ($this->params->get('show_details', 1)) { ?>
                            <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_DETAILS'); ?></th>
                        <?php $colspan++; } ?>
                        <?php if ($this->params->get('show_invoice', 1)) { ?>
                            <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_INVOICE'); ?></th>
                        <?php $colspan++; } ?>
                        <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_DATE'); ?></th>
                        <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_PRICE'); ?></th>
                        <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_GATEWAY'); ?></th>
                        <th class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('COM_RSMEMBERSHIP_STATUS'); ?></th>
                    </tr>
                <?php } ?>

                <?php $k = 1; ?>
                <?php $i = 0; ?>
                <?php foreach ($this->items as $item) {
                    $css_status = ( $item->status == 'completed' ? 'success' : ( $item->status == 'pending' ? 'warning' : 'error' ) );
                    ?>
                    <tr class="rsmesectiontableentry<?php echo $k . $this->escape($this->params->get('pageclass_sfx')); ?> <?php echo $css_status;?>" >
                        <td align="right"><?php echo $this->pagination->getRowOffset($i); ?></td>
                        <td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_'.strtoupper($item->type)); ?></td>
                        <?php if ($this->params->get('show_details', 1)) { ?>
                        <td>
                            <?php
                            $params = RSMembershipHelper::parseParams($item->params);
                            switch ($item->type)
                            {
                                case 'new':
                                    if (!empty($params['membership_id']))
                                        echo isset($this->cache->memberships[$params['membership_id']]) ? $this->cache->memberships[$params['membership_id']] : JText::_('COM_RSMEMBERSHIP_COULD_NOT_FIND_MEMBERSHIP');
                                    if (!empty($params['extras']))
                                        foreach ($params['extras'] as $extra)
                                            if (!empty($extra))
                                                echo '<br />- '.$this->cache->extra_values[$extra];
                                    break;

                                case 'upgrade':
                                    if (!empty($params['from_id']) && !empty($params['to_id']))
                                        echo $this->cache->memberships[$params['from_id']].' -&gt; '.$this->cache->memberships[$params['to_id']];
                                    break;

                                case 'addextra':
                                    if (!empty($params['extras']))
                                        foreach ($params['extras'] as $extra)
                                            echo $this->cache->extra_values[$extra].'<br />';
                                    break;

                                case 'renew':
                                    if (!empty($params['membership_id']))
                                        echo $this->cache->memberships[$params['membership_id']];
                                    break;
                            }
                            ?>
                        </td>
                        <?php } ?>
                        <?php if ($this->params->get('show_invoice', 1)) { ?>
                        <td>
                            <?php if ($item->status == 'completed' && $item->membership_data && $item->membership_data->use_membership_invoice) { ?>
                                <a class="rsm_pdf" href="<?php echo JRoute::_("index.php?option=com_rsmembership&task=mytransaction.outputinvoice&id=$item->id"); ?>"></a>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td><?php echo RSMembershipHelper::showDate($item->date); ?></td>
                        <td><?php echo RSMembershipHelper::getPriceFormat($item->price); ?></td>
                        <td><?php echo $item->gateway; ?></td>
                        <td><?php echo JText::_('COM_RSMEMBERSHIP_TRANSACTION_STATUS_'.strtoupper($item->status)); ?></td>
                    </tr>
                    <?php $k = $k == 1 ? 2 : 1; ?>
                    <?php $i++; ?>
                <?php } ?>

                <?php if ($this->params->get('show_pagination', 1) && $this->pagination->get('pages.total') > 1) { ?>
                    <tr>
                        <td align="center" colspan="<?php echo $colspan; ?>" class="center pagination sectiontablefooter<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
                            <?php echo $this->pagination->getPagesLinks(); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>" align="right"><?php echo $this->pagination->getPagesCounter(); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />

        <?php } ?>
    </form>
</div>