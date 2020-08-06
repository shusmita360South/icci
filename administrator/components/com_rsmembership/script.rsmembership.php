<?php
/**
 * @package	RSMembership!
 * @copyright	(c) 2009 - 2020 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */
defined('_JEXEC') or die('Restricted access');

class com_rsmembershipInstallerScript
{
	private static $plugins = array(
		'plg_rsmembership' => array(
			'name'      => 'System - RSMembership!',
			'core'      => true,
			'group'     => 'system',
			'element'   => 'rsmembership',
			'result'    => false
		),
		'plg_installer' => array(
			'name'      => 'Installer - RSMembership!',
			'core'      => true,
			'group'     => 'installer',
			'element'   => 'rsmembership',
			'result'    => false
		),
		'plg_rsmembershipprivacy' => array(
			'name'      => 'Privacy - RSMembership!',
			'core'      => true,
			'group'     => 'privacy',
			'element'   => 'rsmembership',
			'result'    => false
		),
		'plg_rsmembershipwire' => array(
			'name'      => 'System - RSMembership! - Wire Transfer',
			'core'      => false,
			'group'     => 'system',
			'element'   => 'rsmembershipwire',
			'result'    => false
		)
	);

	public function preflight($type, $parent)
	{
		$app 		= JFactory::getApplication();
		$jversion 	= new JVersion();
		
		// Running Joomla! 2.5
		if (!$jversion->isCompatible('3.7.0'))
		{
			$app->enqueueMessage('RSMembership! Could not be installed on your Joomla! version. Please consider updating to minimum 3.7.0 version of Joomla! if you\'d like to still use RSMembership!.', 'error');
			return false;
		}

		return true;
	}
	
	public function postflight($type, $parent)
	{
		$source 	= $parent->getParent()->getPath('source');
		$db			= JFactory::getDBO();
		$query 		= $db->getQuery(true);

		if ($type == 'install') 
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_rsmembership/tables');
			
			// insert default data in Fields, RSMembershipTable
			$new_fields = array(
				array('name' => 'address', 	'label' => 'Address', 'type' => 'textbox', 'values' => ''),
				array('name' => 'city', 	'label' => 'City',    'type' => 'textbox', 'values' => ''),
				array('name' => 'state', 	'label' => 'State',   'type' => 'textbox', 'values' => ''),
				array('name' => 'zip', 		'label' => 'ZIP', 	  'type' => 'textbox', 'values' => ''),
				array('name' => 'country', 	'label' => 'Country', 'type' => 'select',  'values' => "//<code>\r\n\$db = JFactory::getDBO();\r\n\$db->setQuery(\"SELECT name FROM #__rsmembership_countries\");\r\nreturn implode(\"\\n\", \$db->loadColumn());\r\n//</code>")
			);

			foreach ( $new_fields as $new_field ) 
			{
				$field = JTable::getInstance('Field', 'RSMembershipTable');
				$field->bind( $new_field );

				$field->required 	= 1;
				$field->published 	= 1;
				$field->ordering 	= $field->getNextOrder();

				if ($field->store()) 
				{
					$db->setQuery("SHOW COLUMNS FROM #__rsmembership_subscribers WHERE `Field` = 'f".$field->id."'");
					if (!$db->loadResult()) 
					{
						$db->setQuery("ALTER TABLE `#__rsmembership_subscribers` ADD `f".$field->id."` VARCHAR( 255 ) NOT NULL");
						$db->query();
					}
				}
			}

			// insert default Wire Payment
			$values = array(
				$db->qn('name') 		=> $db->q('Wire Transfer'),
				$db->qn('details') 		=> $db->q('<p>Please enter your transfer details here.</p>'),
				$db->qn('tax_type') 	=> $db->q(0),
				$db->qn('tax_value') 	=> $db->q(0),
				$db->qn('published')	=> $db->q(1)
			);
			
			$query->clear();
			$query->insert($db->qn('#__rsmembership_payments'))
				  ->columns(array_keys($values))
				  ->values(implode(', ', $values));
			$db->setQuery($query);
			$db->execute();
		}

		if ($type == 'update') 
		{
			$tables = $db->getTableList();

			if ( in_array($db->getPrefix().'rsmembership_users', $tables) ) 
			{
				$db->setQuery('RENAME TABLE '.$db->qn('#__rsmembership_users').' TO '.$db->qn('#__rsmembership_subscribers'));
				$db->execute();
			}
			if ( in_array($db->getPrefix().'rsmembership_membership_users', $tables) ) {
				$db->setQuery('RENAME TABLE '.$db->qn('#__rsmembership_membership_users').' TO '.$db->qn('#__rsmembership_membership_subscribers'));
				$db->execute();
			}			

			// parsing sql 
			$sqlfile = JPATH_ADMINISTRATOR.'/components/com_rsmembership/sql/mysql/install.mysql.sql';
			$buffer = file_get_contents($sqlfile);
			if ($buffer !== false)
			{
				if ($queries = $db->splitSql($buffer))
				{
					// Process each query in the $queries array (split out of sql file).
					foreach ($queries as $sqlquery)
					{
						$db->setQuery($sqlquery);
						if (!$db->execute())
						{
							JFactory::getApplication()->enqueueMessage(JText::_('JLIB_INSTALLER_ERROR_SQL_ERROR'), 'warning');
							return false;
						}
					}
				}
			}

			// converting date from int(11) to datetime

			// transaction
			$transactions_columns = $db->getTableColumns('#__rsmembership_transactions');
			if ( $transactions_columns['date'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_transactions')." CHANGE ".$db->qn('date')." ".$db->qn('date')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_transactions')
					  ->set($db->qn('date')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('date')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('date')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_transactions'))->set($db->qn('date').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_transactions'))->set($db->qn('date').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_transactions')." CHANGE ".$db->qn('date')." ".$db->qn('date')." DATETIME NOT NULL");
				$db->execute();
			}
			
			// set the tax_type in the transactions table
			if (!isset($transactions_columns['tax_type']))
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_transactions')." ADD ".$db->qn('tax_type')." TINYINT(1) NOT NULL AFTER `price`");
				$db->execute();
			}
			
			// set the tax_value in the transactions table
			if (!isset($transactions_columns['tax_value']))
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_transactions')." ADD ".$db->qn('tax_value')." DECIMAL(10,2) NOT NULL AFTER `price`");
				$db->execute();
			}
			
			// set the transaction_data in the transactions table
			if (!isset($transactions_columns['transaction_data']))
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_transactions')." ADD ".$db->qn('transaction_data')." TEXT NOT NULL");
				$db->execute();
			}
			
			// index on coupon name
			$transactions_columns = $db->getTableColumns('#__rsmembership_transactions', false);
			if ($transactions_columns['coupon']->Key != 'MUL') {
				$db->setQuery('ALTER TABLE '.$db->qn('#__rsmembership_transactions').' ADD INDEX ( '.$db->qn('coupon').' )');
				$db->execute();
			}
			
			// subscribers
			$subscribers_columns = $db->getTableColumns('#__rsmembership_membership_subscribers');
			if ( $subscribers_columns['notified'] == 'tinyint' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_membership_subscribers')." CHANGE ".$db->qn('notified')." ".$db->qn('notified')." DATETIME NOT NULL");
				$db->execute();
			}
			
			// fields
			$fields_columns = $db->getTableColumns('#__rsmembership_fields');
			if ( !isset($fields_columns['showinsubscribers'])) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_fields')." ADD ".$db->qn('showinsubscribers')." TINYINT(1) NOT NULL");
				$db->execute();
			}
			
			if ( !isset($fields_columns['datetimeformat'])) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_fields')." ADD ".$db->qn('datetimeformat')." varchar(255) NOT NULL");
				$db->execute();
			}
			
			// memberships
			$memberships_columns = $db->getTableColumns('#__rsmembership_memberships', false);
			foreach ($memberships_columns as $membership_column => $struct)
			{
				if ($struct->Type === 'varchar(255)')
				{
					$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_memberships')." CHANGE ".$db->qn($membership_column)." ".$db->qn($membership_column)." text NOT NULL");
					$db->execute();
				}
			}
			if ( !isset($memberships_columns['admin_email_from_addr'])) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_memberships')." ADD ".$db->qn('admin_email_from_addr')." text NOT NULL");
				$db->execute();
			}
			if (!isset($memberships_columns['recurring_times'])) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_memberships')." ADD ".$db->qn('recurring_times')." INT(11) NOT NULL");
				$db->execute();
			}
			if (isset($memberships_columns['membershipl_invoice_layout']))
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_memberships')." CHANGE ".$db->qn('membershipl_invoice_layout') . ' ' . $db->qn('membership_invoice_layout') . ' text NOT NULL');
				$db->execute();
				
				$memberships_columns['membership_invoice_layout'] = $memberships_columns['membershipl_invoice_layout'];
			}
			
			// check for the invoice columns
			$invoice_columns = array(
				'use_membership_invoice' 	 		 => " tinyint(1) NOT NULL",
				'add_membership_invoice_to_approval' => " tinyint(1) NOT NULL default '1'",
				'membership_invoice_type' 	 		 => " varchar(6) NOT NULL",
				'membership_invoice_padding' 		 => " int(10) NOT NULL",
				'membership_invoice_title' 	 		 => " text NOT NULL",
				'membership_invoice_layout' 		 => " text NOT NULL",
				'membership_invoice_pdf_font' 		 => " varchar(15) NOT NULL",
				'membership_invoice_pdf_font_size' 	 => " int(3) NOT NULL"
			);
			
			foreach ($invoice_columns as $invoice_column => $invoice_col_type)
			{
				if ( !isset($memberships_columns[$invoice_column])) 
				{
					$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_memberships')." ADD ".$db->qn($invoice_column).$invoice_col_type);
					$db->execute();
				}
			}
			
			// membership_fields
			$memberships_fields_columns = $db->getTableColumns('#__rsmembership_membership_fields');
			if ( !isset($memberships_fields_columns['datetimeformat'])) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_membership_fields')." ADD ".$db->qn('datetimeformat')." varchar(255) NOT NULL");
				$db->execute();
			}

			// coupons
			$coupons_columns = $db->getTableColumns('#__rsmembership_coupons');
			if ( $coupons_columns['date_added'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_coupons')." CHANGE ".$db->qn('date_added')." ".$db->qn('date_added')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_coupons')
					  ->set($db->qn('date_added')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('date_added')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('date_added')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_coupons'))->set($db->qn('date_added').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date_added').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->update($db->qn('#__rsmembership_coupons'))->set($db->qn('date_added').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date_added').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_coupons')." CHANGE ".$db->qn('date_added')." ".$db->qn('date_added')." DATETIME NOT NULL");
				$db->execute();
			}
			
			if ( $coupons_columns['date_start'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_coupons')." CHANGE ".$db->qn('date_start')." ".$db->qn('date_start')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_coupons')
					  ->set($db->qn('date_start')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('date_start')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('date_start')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_coupons'))->set($db->qn('date_start').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date_start').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->update($db->qn('#__rsmembership_coupons'))->set($db->qn('date_start').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date_start').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_coupons')." CHANGE ".$db->qn('date_start')." ".$db->qn('date_start')." DATETIME NOT NULL");
				$db->execute();
			}

			if ( $coupons_columns['date_end'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_coupons')." CHANGE ".$db->qn('date_end')." ".$db->qn('date_end')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_coupons')
					  ->set($db->qn('date_end')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('date_end')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('date_end')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_coupons'))->set($db->qn('date_end').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date_end').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->update($db->qn('#__rsmembership_coupons'))->set($db->qn('date_end').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date_end').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_coupons')." CHANGE ".$db->qn('date_end')." ".$db->qn('date_end')." DATETIME NOT NULL");
				$db->execute();
			}
			
			// index on coupon name
			$coupons_columns = $db->getTableColumns('#__rsmembership_coupons', false);
			if ($coupons_columns['name']->Key != 'MUL') {
				$db->setQuery('ALTER TABLE '.$db->qn('#__rsmembership_coupons').' ADD INDEX ( '.$db->qn('name').' )');
				$db->execute();
			}

			// logs
			$logs_columns = $db->getTableColumns('#__rsmembership_logs');
			if ( $logs_columns['date'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_logs')." CHANGE ".$db->qn('date')." ".$db->qn('date')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_logs')
					  ->set($db->qn('date')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('date')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('date')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_logs'))->set($db->qn('date').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->update($db->qn('#__rsmembership_logs'))->set($db->qn('date').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('date').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_logs')." CHANGE ".$db->qn('date')." ".$db->qn('date')." DATETIME NOT NULL");
				$db->execute();
			}
			
			// membership_subscribers
			$membership_subscribers_columns = $db->getTableColumns('#__rsmembership_membership_subscribers');
			if ( $membership_subscribers_columns['membership_start'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_membership_subscribers')." CHANGE ".$db->qn('membership_start')." ".$db->qn('membership_start')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_membership_subscribers')
					  ->set($db->qn('membership_start')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('membership_start')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('membership_start')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_membership_subscribers'))->set($db->qn('membership_start').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('membership_start').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->update($db->qn('#__rsmembership_membership_subscribers'))->set($db->qn('membership_start').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('membership_start').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_membership_subscribers')." CHANGE ".$db->qn('membership_start')." ".$db->qn('membership_start')." DATETIME NOT NULL");
				$db->execute();
			}

			if ( $membership_subscribers_columns['membership_end'] == 'int' ) 
			{
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_membership_subscribers')." CHANGE ".$db->qn('membership_end')." ".$db->qn('membership_end')." VARCHAR(255) NOT NULL");
				$db->execute();

				// convert the date
				$query->clear();
				$query->update('#__rsmembership_membership_subscribers')
					  ->set($db->qn('membership_end')." = IFNULL(CONVERT_TZ(FROM_UNIXTIME(".$db->qn('membership_end')."), @@session.time_zone, 'UTC'), FROM_UNIXTIME(".$db->qn('membership_end')."))");
				$db->setQuery($query);
				$db->execute();

				$query->clear();
				$query->update($db->qn('#__rsmembership_membership_subscribers'))->set($db->qn('membership_end').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('membership_end').' LIKE '.$db->q('1970-01-01%'));
				$db->setQuery($query);
				$db->execute();
				
				$query->clear();
				$query->update($db->qn('#__rsmembership_membership_subscribers'))->set($db->qn('membership_end').' = '.$db->q('0000-00-00 00:00:00'))->where($db->qn('membership_end').' LIKE '.$db->q('1969-12-31%'));
				$db->setQuery($query);
				$db->execute();

				// change the column type
				$db->setQuery("ALTER TABLE ".$db->qn('#__rsmembership_membership_subscribers')." CHANGE ".$db->qn('membership_end')." ".$db->qn('membership_end')." DATETIME NOT NULL");
				$db->execute();
			}
			// end converting date from int(11) to datetime
			
			// Logs
			$query = $db->getQuery(true);
			$query->update($db->qn('#__rsmembership_logs'))
				  ->set($db->qn('path').' = CONCAT('.$db->q('[DWN] ').', '.$db->qn('path').')')
				  ->where($db->qn('path').' NOT LIKE '.$db->q('[DWN] %'))
				  ->where($db->qn('path').' NOT LIKE '.$db->q('[URL] %'));
			$db->setQuery($query);
			$db->execute();
		}

		foreach (static::$plugins as $plugin => $data)
        {
            $installer = new JInstaller();

            if ($installer->install($source.'/other/' . $plugin))
            {
                if ($data['core'])
                {
                    $query->clear();
                    $query->update('#__extensions')
                        ->set($db->qn('enabled').'='.$db->q(1))
                        ->where($db->qn('element').'='.$db->q($data['element']))
                        ->where($db->qn('type').'='.$db->q('plugin'))
                        ->where($db->qn('folder').'='.$db->q($data['group']));
                    $db->setQuery($query);
                    $db->execute();
                }

                static::$plugins[$plugin]['result'] = true;
            }
        }

		$this->showInstallMessage();
	}

	public function uninstall($parent)
	{
		// Get the database object.
		$db = JFactory::getDbo();

		foreach (static::$plugins as $plugin => $data)
		{
			$group = isset($data['group']) ? $data['group'] : 'system';
			$element = isset($data['element']) ? $data['element'] : str_replace('plg_', '', $plugin);

			$query = $db->getQuery(true)
				->select( $db->qn('extension_id') )
				->from( $db->qn('#__extensions') )
				->where( $db->qn('element') . '=' . $db->q($element) )
				->where( $db->qn('folder') . '=' . $db->q($group) )
				->where( $db->qn('type') . '=' . $db->q('plugin') );

			$db->setQuery($query);

			if ( $extension_id = $db->loadResult() )
			{
				// Initialize JInstaller.
				$plg_installer = new JInstaller();

				$plg_installer->uninstall('plugin', $extension_id);
			}
		}
	}
	
	protected function showInstallMessage()
	{
?>
		<style type="text/css">
			.version-history {
				margin: 0 0 2em 0;
				padding: 0;
				list-style-type: none;
			}
			.version-history > li {
				margin: 0 0 0.5em 0;
				padding: 0 0 0 4em;
			}
			.version,
			.version-new,
			.version-fixed,
			.version-upgraded {
				float: left;
				font-size: 0.8em;
				margin-left: -4.9em;
				width: 4.5em;
				color: white;
				text-align: center;
				font-weight: bold;
				text-transform: uppercase;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
			}

			.version {
				background: #000;
			}
			.version-new {
				background: #7dc35b;
			}
			.version-fixed {
				background: #e9a130;
			}
			.version-upgraded {
				background: #61b3de;
			}

			.install-ok {
				background: #7dc35b;
				color: #fff;
				padding: 3px;
			}

			.install-not-ok {
				background: #E9452F;
				color: #fff;
				padding: 3px;
			}
		</style>
		<div class="row-fluid">
		<div class="span2">
			<?php echo JHtml::image('com_rsmembership/admin/rsmembership-box.jpg', 'RSMembership! Box', null, true); ?>
		</div>
		<div class="span10">
            <?php foreach (static::$plugins as $plugin => $data) { ?>
                <p><?php echo $data['name']; ?> ...
                    <?php if ($data['result']) { ?>
                        <b class="install-ok">Installed</b>
                        <?php if ($data['core']) { ?>
                        <b class="install-ok">Published</b>
                        <?php } ?>
                    <?php } else { ?>
                        <b class="install-not-ok">Error installing!</b>
                    <?php } ?>
                </p>
            <?php } ?>

			<h2>Changelog v1.22.21</h2>
			<ul class="version-history">
				<li><span class="version-upgraded">Upg</span> 'Font' and 'Font Size' can be configured in 'Invoice Settings' and 'Membership - Invoice'.</li>
				<li><span class="version-fixed">Fix</span> Unicode characters were not displayed correctly in the generated PDF.</li>
			</ul>
			<a class="btn btn-large btn-primary" href="index.php?option=com_rsmembership">Start using RSMembership!</a>
			<a class="btn" href="https://www.rsjoomla.com/support/documentation/rsmembership-user-guide.html" target="_blank">Read the RSMembership! User Guide</a>
			<a class="btn" href="https://www.rsjoomla.com/support.html" target="_blank">Get Support!</a>
		</div>
		</div>
	<?php
	}
}