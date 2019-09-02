<?php

global $spcms;
global $sptext;
$sptext = array();


/*
 * English language file
 */

$sptext['title']                       = 'SafePassword';
$sptext['connection']                  = 'Connection';
$sptext['connect_to']                  = 'Connect to';
$sptext['connected_to']                = 'Connected to';
$sptext['i_m_agree']                   = 'I agree with the <a target=\'blank\' href=\''.$spcms['terms_and_conditions'].'\'>terms and conditions</a>';
$sptext['must_agree']                  = 'You must agree with the terms and conditions for using our service';
$sptext['account_type']                = 'Type';
$sptext['loading']                     = 'Loading....';
$sptext['wait']                        = 'Please wait... will be finished soon...';
$sptext['completed']                   = 'Completed';
$sptext['refresh']                     = 'Please wait... will be refreshed...';   
$sptext['disconnect']                  = 'Are you sure that you want to disconnect ?';
$sptext['button_yes']                  = 'YES, I Agree';
$sptext['button_no']                   = 'NO, Cancel...';
$sptext['warning']                     = 'Warning !!!';
$sptext['save']                        = 'Save';
$sptext['hint']                        = 'Hint !';
$sptext['yearly']                      = 'Yearly';
$sptext['monthly']                     = 'Monthly';
$sptext['email']                       = 'Email';
$sptext['phone']                       = 'Phone';
$sptext['send']                        = 'Send';
$sptext['language']                    = 'Language';
$sptext['the_field']                   = 'The field';
$sptext['already_exist']               = 'already exists in our database.';
$sptext['the_fields']                  = 'The fields';
$sptext['and']                         = 'and';
$sptext['must_be_identique']           = 'must be the same.';
$sptext['error_is_required']           = 'Please check this field !';
$sptext['error_is_email']              = 'Wrong email address !';
$sptext['error_is_phone']              = 'Wrong phone number !';
$sptext['error_is_lower_than']         = 'Must be equal or lower than %s !';
$sptext['error_is_higher_than']        = 'Must be equal or higher than %s !';
$sptext['error_invalid_url']           = 'Wrong website !';
$sptext['error_min_chars']             = 'This field must have at least %s characters !';
$sptext['error_allowed_characters']    = 'Only %s characters are allowed !';
$sptext['no_access']                   = 'Sorry. You don\'t have the rights to see this page.';
$sptext['registration']                = 'Account details';
$sptext['country']                     = 'Country';
$sptext['state']                       = 'State';
$sptext['pro_account']                 = 'PRO account';
$sptext['pro_account_total']           = 'Total';

$sptext['email_hint']                  = 'You will receive your SafePassword at this email address.'; //-n ( must be added )
$sptext['phone_hint']                  = 'You will receive your SafePassword at this phone number.';  //-n ( must be added )
$sptext['send_via']                    = 'Send through'; //-n ( must be added )
$sptext['send_via_email']              = 'Email'; //-n ( must be added )
$sptext['send_via_sms']                = 'SMS ( PRO only )'; //-n ( must be added )
$sptext['send_via_hint']               = 'How do you want to receive your SafePassword.'; //-n ( must be added )
$sptext['paying']                      = 'Redirecting to PayPal for the payment'; //-n ( must be added )
$sptext['account_success']             = 'Congratulations ! Your account has been successfully created.'; //-n ( must be added )
$sptext['account_cancellation']        = 'If you want to cancel the subscription please send us an email at'; //-n ( must be added )
$sptext['no_dashboard']                = 'Your users won\'t be able to use SafePassword...'; //-m (modified)
$sptext['warning_error']               = '%s ... Please try again later or '; //-m (modified)
$sptext['warning_contact']             = 'contact SafePassword support'; //-m (modified)
$sptext['read']                        = 'Read more'; //-n ( must be added )
$sptext['error_cancel_subscription']   = 'You have cancelled the subscription to your pro account so you\'re now using a free account.'; //-n ( must be added )
$sptext['email_credits']               = 'Email credits'; //-n ( must be added )
$sptext['phone_credits']               = 'Phone credits'; //-n ( must be added )
$sptext['get_safepassword']            = 'Get your SafePassword !'; //-n ( must be added )
$sptext['get_sp_description']          = 'If you have enabled SafePassword method click on link below to get it.'; //-n ( must be added )
$sptext['login_with_safepassword']     = 'Login with SafePassword'; //-n ( must be added )
$sptext['enable_safepassword']         = 'Do you want to disable default authentication and enable SafePassword\'s authentication ?'; //-n ( must be added )
$sptext['sync_with_safepassword_app']  = 'If you want to sync it with SafePassword android/iOS app use this QR code.'; //-n ( must be added )
$sptext['account_type_sp']             = 'Account type'; //-n ( must be added )
$sptext['upgrade_to_pro']              = 'If you want to upgrade your account to PRO you must disable SafePassword and enable it again with the PRO account.'; //-n ( must be added )
$sptext['auto_renew']                  = 'Auto-renew'; //-n ( must be added )
$sptext['sp_error']                    = 'Error'; //-n ( must be added )
$sptext['sp_error_incorrect_expired']  = 'The SafePassword you entered is incorrect or expired.'; //-n ( must be added )
$sptext['p_error_incorrect_expired']   = 'The Password you entered is incorrect or expired.'; //-n ( must be added )
$sptext['sp_error_empty_username']     = 'The username field is empty.'; //-n ( must be added )
$sptext['sp_error_empty_password']     = 'The Password field is empty.'; //-n ( must be added )
$sptext['sp_error_invalid_username']   = 'Invalid username.'; //-n ( must be added )
$sptext['sp_error_maintenance_mode']   = 'SafePassword servers are in maintenance. Please try again later.'; //-n ( must be added 
$sptext['sp_error_username_password']  = 'The SafePassword you entered for the username <strong>%1$s</strong> is incorrect or expired.'; //-n ( must be added
$sptext['sp_error_localhost']          = 'Sorry. Localhost websites are not accepted by Safe Password because they can\'t be verified.'; //-n ( must be added )
$sptext['recharge']                    = 'Recharge'; //-n ( must be added )
$sptext['credits']                     = 'Credits'; //-n ( must be added )
$sptext['recharge_cancel']             = 'You have cancelled the recharging.'; //-n ( must be added )
$sptext['recharge_success']            = 'Congratulations ! Your account has been successfully recharged.'; //-n ( must be added )