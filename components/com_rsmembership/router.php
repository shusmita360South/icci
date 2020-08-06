<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2016 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

function RSMembershipBuildRoute(&$query)
{
	$segments = array();
	
	if (!empty($query['task']))
		switch ($query['task'])
		{
			case 'back':
				$segments[] = 'back';
				$segments[] = $query['cid'];
			break;
			
			case 'subscribe':
				$segments[] = 'subscribe-to';
				$segments[] = $query['cid'];
			break;
			
			case 'validatesubscribe':
				$segments[] = 'subscribe-finish';
			break;
			
			case 'paymentredirect':
				$segments[] = 'payment-redirect';
			break;
			
			case 'payment':
				$segments[] = 'payment';
			break;
			
			case 'download':
				$segments[] = 'download';
				if ($query['from'] == 'membership')
					$segments[] = 'from-membership';
				elseif ($query['from'] == 'extra')
					$segments[] = 'from-membership-extra';
				$segments[] = $query['cid'];
			break;
			
			case 'thankyou':
				$segments[] = 'show-thank-you';
			break;

			case 'mymembership.addextrapaymentredirect':
			case 'mymembership.renewpaymentredirect':
			case 'mymembership.upgradepaymentredirect':
			case 'mymembership.upgrade':
				list($controller, $task) = explode('.', $query['task'], 2);

				$nice_routes = array(
					'upgrade' 					=> 'upgrade-to',
					'addextrapaymentredirect' 	=> 'add-extra-payment-redirect',
					'renewpaymentredirect' 		=> 'renew-payment-redirect',
					'upgradepaymentredirect' 	=> 'upgrade-payment-redirect',
				);

				$segments[] = $nice_routes[$task];
				if ($task == 'upgrade') {
					$segments[] = $query['cid'];
				}
			break;

			case 'mytransaction.outputinvoice':
				$segments[] = 'download-invoice';
				$segments[] = $query['id'];
				unset($query['id']);
			break;
			
			case 'validateuser':
				$segments[] = 'save-my-account';
			break;
			
			case 'captcha':
				$segments[] = 'captcha';
			break;
			
			case 'cancel':
				$segments[] = 'cancel-subscription';
				$segments[] = $query['cid'];
			break;

            case 'removedata.request':
                $segments[] = 'remove-data-request';
                break;

            case 'removedata.process':
                $segments[] = 'remove-data-process';
                break;
		}
	
	if (!empty($query['view']))
		switch ($query['view'])
		{
			case 'membership':
				$segments[] = 'view-membership-details';

				if (isset($query['catid'])) {
					$segments[] = $query['catid'];
				}

				$segments[] = $query['cid'];
			break;

			case 'mymembership':
				$processed_cid = false;
				if (!empty($query['path']))
				{
					$segments[] = 'browse-folders';
					if ($query['from'] == 'membership')
						$segments[] = 'from-membership';
					elseif ($query['from'] == 'extra')
						$segments[] = 'from-membership-extra';
				}
				else
				{
					if (isset($query['layout'])) {
						switch($query['layout']) {
							case 'payment':
								$build_segment = 'payment-';
								if (isset($query['action_type'])) {
									$build_segment .= $query['action_type'];
								}
								$segments[] = $build_segment;
								$segments[] = $query['payment'];

								// unset unnecessary
								unset($query['payment']);
								unset($query['action_type']);
							break;

							case 'upgrade':
								$segments[] = 'upgrade';
								if (isset($query['cid'])) {
									$segments[] = $query['cid'];
									$processed_cid = true;
								}
								if (isset($query['to_id'])) {
									$segments[] = $query['to_id'];
									unset($query['to_id']);
								}
							break;

							case 'addextra':
								$segments[] = 'add-extra-to-membership';
								if (isset($query['cid'])) {
									$segments[] = $query['cid'];
									$processed_cid = true;
								}
								if (isset($query['extra_id'])) {
									$segments[] = $query['extra_id'];
								}
							break;

							default:
								$segments[] = $query['layout'];
							break;
						}
					} else {
						$segments[] = 'view-my-membership-details';
					}
				}
				if (!$processed_cid && isset($query['cid'])) {
					$segments[] = $query['cid'];
				}
			break;
			
			case 'mymemberships':
				$segments[] = 'view-my-memberships';
			break;

			case 'mytransactions':
				$segments[] = 'view-my-transactions';
			break;
			
			case 'rsmembership':
				$segments[] = 'view-available-memberships';
				
				if (isset($query['catid']))
					$segments[] = $query['catid'];

				if (isset($query['layout']))
					$segments[] = $query['layout'];
			break;
			
			case 'terms':
				$segments[] = 'view-terms';
				if (!empty($query['cid']))
                {
                    $segments[] = $query['cid'];
                }
			break;
			
			case 'user':
				$segments[] = 'view-my-account';
			break;
			
			case 'removedata':
				$segments[] = 'remove-data-success';
			break;
		}
	
	unset($query['task'], $query['cid'], $query['catid'], $query['view'], $query['from'], $query['extra_id'], $query['layout']);
	
	return $segments;
}

function RSMembershipParseRoute($segments)
{
	$query = array();
	
	$segments[0] = str_replace(':', '-', $segments[0]);
	
	switch ($segments[0])
	{
		case 'back':
			$query['task'] = 'back';
			$query['view'] = 'subscribe';
			$query['cid'] = @$segments[1];
		break;
		
		case 'subscribe-to':
			$query['task'] = 'subscribe';
			$query['cid'] = @$segments[1];
		break;
		
		case 'subscribe-finish':
			$query['task'] = 'validatesubscribe';
		break;
		
		case 'payment-redirect':
			$query['task'] = 'paymentredirect';
		break;
		
		case 'payment':
			$query['task'] = 'payment';
		break;
		
		case 'download':
			$query['task'] = 'download';
			$segments[1] = str_replace(':', '-', $segments[1]);
			if ($segments[1] == 'from-membership')
				$query['from'] = 'membership';
			elseif ($segments[1] == 'from-membership-extra')
				$query['from'] = 'extra';
			
			$query['cid'] = end($segments);
		break;
		
		case 'show-thank-you':
			$query['task'] = 'thankyou';
		break;
		
		case 'upgrade-to':
			$query['task'] = 'mymembership.upgrade';
			$query['cid'] = $segments[1];
		break;

		case 'download-invoice':
			$query['task'] = 'mytransaction.outputinvoice';
			$query['id'] = $segments[1];
		break;
		
		case 'upgrade-payment-redirect':
			$query['task'] = 'mymembership.upgradepaymentredirect';
		break;
		
		case 'renew':
			$query['view'] = 'mymembership';
			$query['layout'] = 'renew';
			$query['cid'] = $segments[1];
		break;

		case 'upgrade':
			$query['view'] = 'mymembership';
			$query['layout'] = 'upgrade';
			$query['cid'] = $segments[1];
			$query['to_id'] = $segments[2];
		break;

		case 'renew-payment-redirect':
			$query['task'] = 'mymembership.renewpaymentredirect';
		break;
		
		case 'add-extra-to-membership':
			$query['view'] = 'mymembership';
			$query['layout'] = 'addextra';
			$query['cid'] = $segments[1];
			$query['extra_id'] = @$segments[2];
		break;
		
		case 'add-extra-payment-redirect':
			$query['task'] = 'mymembership.addextrapaymentredirect';
		break;

		case 'payment-addextra':
		case 'payment-renew':
		case 'payment-upgrade':
			list($layout, $action_type) = explode('-', $segments[0], 2);
			$query['view'] = 'mymembership';
			$query['layout'] = $layout;
			$query['payment'] = $segments[1];
			$query['action_type'] = $action_type;
		break;
		
		case 'view-membership-details':
			$query['view'] = 'membership';
			
			if (isset($segments[2]))
			{
				$query['catid'] = $segments[1];
				$query['cid'] = $segments[2];
			}
			else
			{
				$query['cid'] = $segments[1];
			}
		break;
		
		case 'view-my-membership-details':
			$query['view'] = 'mymembership';
			$query['cid'] = $segments[1];
		break;
		
		case 'browse-folders':
			$query['view'] = 'mymembership';
			$segments[1] = str_replace(':', '-', $segments[1]);
			if ($segments[1] == 'from-membership')
				$query['from'] = 'membership';
			elseif ($segments[1] == 'from-membership-extra')
				$query['from'] = 'extra';
			$query['cid'] = @$segments[2];
		break;
		
		case 'view-my-memberships':
			$query['view'] = 'mymemberships';
		break;

		case 'view-my-transactions':
			$query['view'] = 'mytransactions';
		break;
		
		case 'view-available-memberships':
			if (isset($segments[1]))
				$query['catid'] = $segments[1];
			if (isset($segments[2]))
				$query['layout'] = $segments[2];
				
			$query['view'] = 'rsmembership';
		break;
		
		case 'view-terms':
			$query['view'] = 'terms';
			$query['cid'] = @$segments[1];
		break;
		
		case 'view-my-account':
			$query['view'] = 'user';
		break;
		
		case 'save-my-account':
			$query['task'] = 'validateuser';
		break;
		
		case 'captcha':
			$query['task'] = 'captcha';
		break;
		
		case 'cancel-subscription':
			$query['task'] = 'cancel';
			$query['cid'] = @$segments[1];
		break;

        case 'remove-data-request':
            $query['task'] = 'removedata.request';
            break;

        case 'remove-data-process':
            $query['task'] = 'removedata.process';
            break;
			
		case 'remove-data-success':
            $query['view'] = 'removedata';
            $query['layout'] = 'default';
            break;
	}
	
	return $query;
}