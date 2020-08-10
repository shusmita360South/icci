<?php
/**
 * @version     1.0.0
 * @package     com_contactform
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      360South Pty Ltd <tech@360south.com.au> - http://www.360south.com.au/
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';
//require_once JPATH_COMPONENT.'/assets/MailChimp.php';

/**
 * Form list controller class.
 */
class ContactformControllerForm extends ContactformController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	

	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}


	
	public function contact_submit()
	{
		// Check for request forgeries.
        #		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));


		$config    = JFactory::getConfig();
		$sitename  = $config->get('sitename');
		$siteemail = $config->get('mailfrom');

		$db 		= JFactory::getDBO();

		$query 		= $db->getQuery(true);
		$query->select('*')
		      ->from('#__contactform_items')
		      ->where('id = 1');
		$db->setQuery($query);
		$contact_form = $db->loadObject();

		$captcha 	= JRequest::getVar('g-recaptcha-response');
		$fname    	= urldecode(strip_tags(JRequest::getVar('c_fname')));
		$lname    	= urldecode(strip_tags(JRequest::getVar('c_lname')));
		$jtitle    	= urldecode(strip_tags(JRequest::getVar('c_jtitle')));
		$email   	= urldecode(strip_tags(JRequest::getVar('c_email')));
		$phone   	= urldecode(strip_tags(JRequest::getVar('c_phone')));
		$company   	= urldecode(strip_tags(JRequest::getVar('c_company')));
		$member    	= urldecode(strip_tags(JRequest::getVar('c_member')));
		$reason   	= urldecode(strip_tags(JRequest::getVar('c_reason')));
		$message 	= nl2br(htmlentities(JRequest::getVar('c_message')));

		$_SESSION['c_fname']    = $fname;
		$_SESSION['c_lname']    = $lname;
		$_SESSION['c_jtitle']    = $jtitle;
		$_SESSION['c_email']   	= $email;
		$_SESSION['c_phone']   	= $phone;
		$_SESSION['c_company']  = $company;
		$_SESSION['c_member']    = $member;
		$_SESSION['c_reason'] 	= $reason;
		$_SESSION['c_message'] 	= $message;



		$secret = '6LccwS8UAAAAAEF1lq8lc7Y6Wsmnol1EWtQBmQHm';
		$gVerifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha);
		$captcha_response = json_decode($gVerifyResponse);


		if (!$captcha_response->success) {
			$_SESSION['response_code'] = 3; // spam
			$linky = JURI::base().substr(JRoute::_('index.php'),1);
			JFactory::getApplication()->redirect( $linky );
			die;
			

		} else {
			date_default_timezone_set('Australia/Melbourne');

			// backup to db
			$submission = new stdClass();
			$submission->datetime 	= date("Y-m-d H:i:s");
			$submission->fname     	= $fname;
			$submission->lname     	= $lname;
			$submission->jtitle     	= $jtitle;
			$submission->email    	= $email;
			$submission->phone    	= $phone;
			$submission->company    = $company;
			$submission->member     	= $member;
			$submission->reason  	= $reason;
			$submission->body  		= $message;

			$db->insertObject('#__contactform_host_submissions', $submission, 'id');


			if (!empty($mailinglist)) {
				$api_key = "ba2aa4a654466fd2552038cc7d05bfc6-us3";
				$list_id = "d7b362d5c4";

				 
				$data_center = substr($api_key,strpos($api_key,'-')+1);
				$email_id = md5($email);
				$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'.$email_id;
				$mvars = array('FNAME' => $fname);
				$json = json_encode([
				    'email_address' => $email,
				    'merge_fields'    => $mvars,
				    'status'        => 'subscribed', //pass 'subscribed' or 'pending'
				]);
				 
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				//curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				$result = curl_exec($ch);
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				echo $status_code;

			}

			// send an email
	
			$emailto			= $contact_form->emailto;

			
			$emailfrom				= $siteemail;
			$emailfromname			= 'CYDA Website Enquiry';
			$replyto				= $email;
			$replytoname			= $fname;
			$bcc					= "360@360south.com.au";
			$cc						= "";
			$attachment				= "";
			$subject 				= $sitename . ': ' . $contact_form->title;

			# prepare email body text
			$adminBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'. $subject .'</title>
			</head>
			<body>
			<p>The following enquiry was submitted online for '. $sitename .'.</p>
			<hr />
			<p>'. $message .'</p>
			<hr />
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100" align="left" valign="top">Name:</td>
					<td align="left" valign="top">'. $fname .' '.$lname.'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Job Title:</td>
					<td align="left" valign="top">'. $jtitle .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Email:</td>
					<td align="left" valign="top">'. $email .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Phone:</td>
					<td align="left" valign="top">'. $phone .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Company:</td>
					<td align="left" valign="top">'. $company .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Is your company a member of ICCI Melbourne?</td>
					<td align="left" valign="top">'. $member .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Which audiences are you most interested in?</td>
					<td align="left" valign="top">'. $reason  .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				
				
			</table>
			</body>
			</html>';

			$senderBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'. $subject .'</title>
			</head>
			<body>
			<p>We have received your email, please see below for details.</p>
			<hr />
			<p>'. $message .'</p>
			<hr />
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100" align="left" valign="top">Name:</td>
					<td align="left" valign="top">'. $fname .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Email:</td>
					<td align="left" valign="top">'. $email .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Phone:</td>
					<td align="left" valign="top">'. $phone .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Reason:</td>
					<td align="left" valign="top">'. $reason  .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				
			</table>
			</body>
			</html>';

			$adminBody = rawurldecode($adminBody);
			$adminMail = JFactory::getMailer();
			$adminMail->Encoding = 'base64';
			$adminMail->setSender(array($emailfrom, $emailfromname))
			     ->addRecipient($emailto)
			     ->setSubject($subject)
			     ->setBody($adminBody)
			     ->isHTML(true);
			if ($cc) {
				$adminMail->addCc($cc);
			}
			if ($bcc) {
				$adminMail->addBcc($bcc);
			}
			if ($attachment) {
				$adminMail->addAttachment($attachment);
			}
			if ($replyto) {
				if ($replytoname) {
					$adminMail->addReplyTo($replyto, $replytoname);
				} else {
					$adminMail->addReplyTo($replyto);
					
				}
			}

			$senderMail = JFactory::getMailer();
			$senderMail->Encoding = 'base64';
			$senderMail->setSender(array($emailfrom, $emailfromname))
			     ->addRecipient($email)
			     ->setSubject($subject)
			     ->setBody($senderBody)
			     ->isHTML(true);
			$senderMail->Send();

			if($adminMail->Send()) {
				$_SESSION['response_code'] = 1; // thankyou
				?>
				<script>
			        $(function() {
			            $('html, body').animate({
			                scrollTop: $("#contact-form").offset().top
			            }, 2000);
			         });
			    </script>
			<?php 
				unset(
					$_SESSION['c_fname'],
					$_SESSION['c_email'],
					$_SESSION['c_phone'],
					$_SESSION['c_reason'],
					$_SESSION['c_message']
				);

			} else {
				$_SESSION['response_code'] = 2; // error
			}

			$linky = JURI::base().substr(JRoute::_('index.php'),1);
			//$linky =JFactory::getURI();
			JFactory::getApplication()->redirect( $linky );
			die;
		}
	}
	public function contact_sponsorsubmit()
	{
		// Check for request forgeries.
        #		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));


		$config    = JFactory::getConfig();
		$sitename  = $config->get('sitename');
		$siteemail = $config->get('mailfrom');

		$db 		= JFactory::getDBO();

		$query 		= $db->getQuery(true);
		$query->select('*')
		      ->from('#__contactform_items')
		      ->where('id = 2');
		$db->setQuery($query);
		$contact_form = $db->loadObject();

		$captcha 	= JRequest::getVar('g-recaptcha-response');
		$fname    	= urldecode(strip_tags(JRequest::getVar('c_fname')));
		$lname    	= urldecode(strip_tags(JRequest::getVar('c_lname')));
		$jtitle    	= urldecode(strip_tags(JRequest::getVar('c_jtitle')));
		$email   	= urldecode(strip_tags(JRequest::getVar('c_email')));
		$phone   	= urldecode(strip_tags(JRequest::getVar('c_phone')));
		$company   	= urldecode(strip_tags(JRequest::getVar('c_company')));
		$member    	= urldecode(strip_tags(JRequest::getVar('c_member')));


		$_SESSION['c_fname']    = $fname;
		$_SESSION['c_lname']    = $lname;
		$_SESSION['c_jtitle']    = $jtitle;
		$_SESSION['c_email']   	= $email;
		$_SESSION['c_phone']   	= $phone;
		$_SESSION['c_company']  = $company;
		$_SESSION['c_member']    = $member;




		$secret = '6LccwS8UAAAAAEF1lq8lc7Y6Wsmnol1EWtQBmQHm';
		$gVerifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha);
		$captcha_response = json_decode($gVerifyResponse);


		if (!$captcha_response->success) {
			$_SESSION['response_code'] = 3; // spam
			$linky = JURI::base().substr(JRoute::_('index.php'),1);
			JFactory::getApplication()->redirect( $linky );
			die;
			

		} else {
			date_default_timezone_set('Australia/Melbourne');

			// backup to db
			$submission = new stdClass();
			$submission->datetime 	= date("Y-m-d H:i:s");
			$submission->fname     	= $fname;
			$submission->lname     	= $lname;
			$submission->jtitle     	= $jtitle;
			$submission->email    	= $email;
			$submission->phone    	= $phone;
			$submission->company    = $company;
			$submission->member     	= $member;


			$db->insertObject('#__contactform_sponsor_submissions', $submission, 'id');


			if (!empty($mailinglist)) {
				$api_key = "ba2aa4a654466fd2552038cc7d05bfc6-us3";
				$list_id = "d7b362d5c4";

				 
				$data_center = substr($api_key,strpos($api_key,'-')+1);
				$email_id = md5($email);
				$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'.$email_id;
				$mvars = array('FNAME' => $fname);
				$json = json_encode([
				    'email_address' => $email,
				    'merge_fields'    => $mvars,
				    'status'        => 'subscribed', //pass 'subscribed' or 'pending'
				]);
				 
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				//curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
				$result = curl_exec($ch);
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				echo $status_code;

			}

			// send an email
	
			$emailto			= $contact_form->emailto;

			
			$emailfrom				= $siteemail;
			$emailfromname			= 'CYDA Website Enquiry';
			$replyto				= $email;
			$replytoname			= $fname;
			$bcc					= "360@360south.com.au";
			$cc						= "";
			$attachment				= "";
			$subject 				= $sitename . ': ' . $contact_form->title;

			# prepare email body text
			$adminBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'. $subject .'</title>
			</head>
			<body>
			<p>The following enquiry was submitted online for '. $sitename .'.</p>
			<hr />
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100" align="left" valign="top">Name:</td>
					<td align="left" valign="top">'. $fname .' '.$lname.'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Job Title:</td>
					<td align="left" valign="top">'. $jtitle .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Email:</td>
					<td align="left" valign="top">'. $email .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Phone:</td>
					<td align="left" valign="top">'. $phone .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Company:</td>
					<td align="left" valign="top">'. $company .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Is your company a member of ICCI Melbourne?</td>
					<td align="left" valign="top">'. $member .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				
				
			</table>
			</body>
			</html>';

			$senderBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>'. $subject .'</title>
			</head>
			<body>
			<p>We have received your email, please see below for details.</p>
			<hr />
			<p>'. $message .'</p>
			<hr />
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100" align="left" valign="top">Name:</td>
					<td align="left" valign="top">'. $fname .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Email:</td>
					<td align="left" valign="top">'. $email .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Phone:</td>
					<td align="left" valign="top">'. $phone .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				<tr>
					<td align="left" valign="top">Reason:</td>
					<td align="left" valign="top">'. $reason  .'</td>
				</tr>
				<tr><td>&nbsp;</td><td></td></tr>
				
			</table>
			</body>
			</html>';

			$adminBody = rawurldecode($adminBody);
			$adminMail = JFactory::getMailer();
			$adminMail->Encoding = 'base64';
			$adminMail->setSender(array($emailfrom, $emailfromname))
			     ->addRecipient($emailto)
			     ->setSubject($subject)
			     ->setBody($adminBody)
			     ->isHTML(true);
			if ($cc) {
				$adminMail->addCc($cc);
			}
			if ($bcc) {
				$adminMail->addBcc($bcc);
			}
			if ($attachment) {
				$adminMail->addAttachment($attachment);
			}
			if ($replyto) {
				if ($replytoname) {
					$adminMail->addReplyTo($replyto, $replytoname);
				} else {
					$adminMail->addReplyTo($replyto);
					
				}
			}

			$senderMail = JFactory::getMailer();
			$senderMail->Encoding = 'base64';
			$senderMail->setSender(array($emailfrom, $emailfromname))
			     ->addRecipient($email)
			     ->setSubject($subject)
			     ->setBody($senderBody)
			     ->isHTML(true);
			$senderMail->Send();

			if($adminMail->Send()) {
				$_SESSION['response_code'] = 1; // thankyou
				?>
				<script>
			        $(function() {
			            $('html, body').animate({
			                scrollTop: $("#contact-form").offset().top
			            }, 2000);
			         });
			    </script>
			<?php 
				unset(
					$_SESSION['c_fname'],
					$_SESSION['c_email'],
					$_SESSION['c_phone'],
					$_SESSION['c_reason'],
					$_SESSION['c_message']
				);

			} else {
				$_SESSION['response_code'] = 2; // error
			}

			$linky = JURI::base().substr(JRoute::_('index.php'),1);
			//$linky =JFactory::getURI();
			JFactory::getApplication()->redirect( $linky );
			die;
		}
	}

	public function rsmembership_logouplod()
	{
		// Check for request forgeries.
        #		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));


		$config    = JFactory::getConfig();
		$sitename  = $config->get('sitename');
		$siteemail = $config->get('mailfrom');

		$db 		= JFactory::getDBO();

		$logofilename = $_POST['logofilename'];
		$logofilesize = $_POST['logofilesize'];
		$logofiletype = $_POST['logofiletype'];
		$logofiledata = $_POST['logofiledata'];

		echo $logofiledata;

		$fileuploadsuccess = "";
		$fileuploaderror = "";
		$target_logo_file = JPATH_ROOT.'/images/logo/'.basename($logofilename);
		$uploadOk = 1;
		if ($logofilename != ""){
			
			$cFileType = pathinfo($target_logo_file ,PATHINFO_EXTENSION);
			
			// Check if file already exists
			if (basename($logofilename) != "") {
				if (file_exists($target_logo_file)) {
				    $fileuploaderror =  "Sorry, file already exists.";
				    $uploadOk = 0;
				}
			}
			// Check file size
			if ($logofilesize > 2000000 ) {
			    $fileuploaderror = "Sorry, your file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if (basename($logofilename) != ""){
				if($cFileType != "png" && $cFileType != "jpg" ) {
			    	$fileuploaderror= "Sorry, only .png .jpg files are allowed.";
			    	$uploadOk = 0;
				}
			}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    $fileuploaderror = $fileuploaderror;
			// if everything is ok, try to upload file
			} else {
				$target_logo_file_new = JPATH_ROOT.'/images/logo/'.basename($logofilename);

				$logofile = fopen( $target_logo_file_new, 'wb' ); 

			    // split the string on commas
			    // $data[ 0 ] == "data:image/png;base64"
			    // $data[ 1 ] == <actual base64 string>
			    $data = explode( ',', $logofiledata );

			    // we could add validation here with ensuring count( $data ) > 1
			    fwrite( $logofile, base64_decode( $data[ 1 ] ) );

			    // clean up the file resource
			    fclose( $logofile ); 


			    /*if (move_uploaded_file($_FILES["memberlogo"]["tmp_name"], $target_logo_file_new)) {
			        $fileuploadsuccess = "Your file has been uploaded.";

			    } else {
			        $fileuploaderror = "Sorry, there was an error uploading your file.";
			    }*/
			    
			}
		}

		$return['error'] =  $fileuploaderror ;
		$return['success'] =  $fileuploadsuccess ;

		header("Content-Type: application/json");
		echo json_encode($return); exit;


	}
	
}