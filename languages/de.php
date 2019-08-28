<?php

global $spcms;
global $sptext;
$sptext = array();


/*
 * German language file
 */

$sptext['title']                       = 'SafePassword';
$sptext['connection']                  = 'Verbindung';
$sptext['connect_to']                  = 'Verbinden mit';
$sptext['connected_to']                = 'Verbunden mit';
$sptext['i_m_agree']                   = 'Ich bin mit den <a target=\'blank\' href=\''.$spcms['terms_and_conditions'].'\'>Allgemeinen Geschäftsbedingungen einverstanden</a>.';
$sptext['must_agree']                  = 'Sie müssen mit den Allgemeinen Geschäftsbedingungen für die Nutzung unseres Dienstes einverstanden sein.';
$sptext['account_type']                = 'Typ';
$sptext['loading']                     = 'Lädt....';
$sptext['wait']                        = 'Bitte warten Sie... wird in Kürze fertiggestellt...';
$sptext['completed']                   = 'Abgeschlossen';
$sptext['refresh']                     = 'Bitte warten Sie... wird aktualisiert...';
$sptext['disconnect']                  = 'Sind Sie sicher, dass Sie die Verbindung trennen wollen?';
$sptext['no_dashboard']                = 'Sie werden unser SafePassword nicht nutzen können...';
$sptext['button_yes']                  = 'JA, ich stimme zu';
$sptext['button_no']                   = 'NEIN, Abbrechen...';
$sptext['warning']                     = 'Achtung!!!';
$sptext['save']                        = 'Speichern';
$sptext['hint']                        = 'Hinweis !';
$sptext['yearly']                      = 'Jährlich';
$sptext['monthly']                     = 'Monatlich';
$sptext['email']                       = 'E-Mail';
$sptext['phone']                       = 'Telefon';
$sptext['send']                        = 'Senden';
$sptext['language']                    = 'Sprache';
$sptext['the_field']                   = 'Das Feld';
$sptext['already_exist']               = 'ist bereits in unserer Datenbank vorhanden.';
$sptext['the_fields']                  = 'Die Felder';
$sptext['and']                         = 'und';
$sptext['must_be_identique']           = 'müssen identisch sein.';
$sptext['error_is_required']           = 'Bitte dieses Feld ankreuzen!';
$sptext['error_is_email']              = 'Falsche E-Mail-Adresse !';
$sptext['error_is_phone']              = 'Falsche Telefonnummer !';
$sptext['error_is_lower_than']         = 'Muss gleich oder kleiner als %s sein !';
$sptext['error_is_higher_than']        = 'Muss gleich oder höher als %s sein !';
$sptext['error_invalid_url']           = 'Falsche Website !';
$sptext['error_min_chars']             = 'Dieses Feld muss mindestens %s Zeichen enthalten !';
$sptext['error_allowed_characters']    = 'Nur %s Zeichen sind erlaubt !';
$sptext["registration"]                = "Kontodaten";
$sptext["country"]                     = "Land";
$sptext["state"]                       = "Zustand";
$sptext["pro_account"]                 = "PRO-Konto";
$sptext["pro_account_total"]           = "Gesamt";

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
$sptext['get_sp_description']          = 'If you had enabled SafePassword method click on link bellow to get it.'; //-n ( must be added )
$sptext['login_with_safepassword']     = 'Login with SafePassword'; //-n ( must be added )
$sptext['enable_safepassword']         = 'Do you want to disable default authentication and enable the SafePassword\'s authentication ?'; //-n ( must be added )
$sptext['sync_with_safepassword_app']  = 'If you want to sync it with SafePassword android/iOS app use this qr code.'; //-n ( must be added )
$sptext['account_type_sp']             = 'Account type'; //-n ( must be added )
$sptext['upgrade_to_pro']              = 'If you want to upgrade your account to PRO you must disable SafePassword and enable it again with PRO account.'; //-n ( must be added )
$sptext['auto_renew']                  = 'Auto-renew'; //-n ( must be added )
$sptext['sp_error']                    = 'Error'; //-n ( must be added )
$sptext['sp_error_incorrect_expired']  = 'The SafePassword you entered is incorrect or expired.'; //-n ( must be added )
$sptext['p_error_incorrect_expired']   = 'The Password you entered is incorrect or expired.'; //-n ( must be added )
$sptext['sp_error_empty_username']     = 'The username field is empty.'; //-n ( must be added )
$sptext['sp_error_empty_password']     = 'The Password field is empty.'; //-n ( must be added )
$sptext['sp_error_invalid_username']   = 'Invalid username.'; //-n ( must be added 
$sptext['sp_error_maintenance_mode']   = 'The SafePassword\'s servers are in maintenance mode. Please try again later.'; //-n ( must be added 
$sptext['sp_error_username_password']  = 'The SafePassword you entered for the username <strong>%1$s</strong> is incorrect or expired.'; //-n ( must be added
$sptext['sp_error_localhost']          = 'Sorry. The localhost websites are not accepted by the Safe Password because it\'s can\'t be verified.'; //-n ( must be added )
$sptext['recharge']                    = 'Recharge'; //-n ( must be added )
$sptext['credits']                     = 'Credits'; //-n ( must be added )
$sptext['recharge_cancel']             = 'You have cancelled the recharging.'; //-n ( must be added )
$sptext['recharge_success']            = 'Congratulations ! Your account has been successfully recharged.'; //-n ( must be added )