<?php 
/******************************************
* File      :   include.rights_create.php
* Project   :   Contenido
* Descr     :   Displays languages
*
* Author    :   Timo A. Hummel
* Created   :   30.04.2003
* Modified  :   07.05.2003
*
* @internal {
*      modified 2008-06-24 timo.trautmann storage for valid from valid to added
* }
* � four for business AG
*****************************************/

if (!$perm->have_perm_area_action($area, $action))
{
	$notification->displayNotification("error", i18n("Permission denied"));
} else
{

	if ($action == "user_createuser")
	{
		if ($username == "")
		{
			$error = i18n("Username can't be empty");
		} else
		{

			$stringy_perms = array ();
			if ($msysadmin)
			{
				array_push($stringy_perms, "sysadmin");
			}

			if (is_array($madmin))
			{
				foreach ($madmin as $value)
				{
					array_push($stringy_perms, "admin[$value]");
				}
			}

			if (is_array($mclient))
			{
				foreach ($mclient as $value)
				{
					array_push($stringy_perms, "client[$value]");
				}
			} else {
				// Add user to the current client, if the current user
				// isn't sysadmin and no client has been specified.
				// This avoids new accounts which are not accessible by the 
				// current user (client admin) anymore
				$aUserPerm = split(",", $auth->auth["perm"]);

				if (!in_array("sysadmin", $aUserPerm))
				{
					array_push($stringy_perms, "client[$client]");
				}
			}

			if (is_array($mlang))
			{
				foreach ($mlang as $value)
				{
					array_push($stringy_perms, "lang[$value]");
				}
			}

			$sql = "SELECT user_id FROM ".$cfg["tab"]["phplib_auth_user_md5"].' WHERE LOWER(username) = "'.strtolower($username).'"';

			$db->query($sql);

			if ($db->next_record())
			{
				$error = i18n("Username already exists");
			} else
			{

				if (strcmp($password, $passwordagain) == 0)
				{
					$newuserid = md5($username);
					$sql = 'INSERT INTO
					                            '.$cfg["tab"]["phplib_auth_user_md5"].'
					                          SET
					                		    username="'.$username.'",
					                            password="'.md5($password).'",
					                            realname="'.$realname.'",
					                            email="'.$email.'",
					                            telephone="'.$telephone.'",
					                            address_street="'.$address_street.'",
					                            address_city="'.$address_city.'",
					                            address_country="'.$address_country.'",
					        	        	    address_zip="'.$address_zip.'",
					                            wysi="'.$wysi.'",
                                                valid_from="'.$valid_from.'",
							                    valid_to="'.$valid_to.'",
					                            perms="'.implode(",", $stringy_perms).'",
					        		            user_id="'.$newuserid.'"';

					$db->query($sql);

				} else
				{
					$error = i18n("Passwords don't match");
				}
			}
		}

	}

	$tpl->reset();

	$sql = "SELECT
	                username, password, realname, email, telephone,
	                address_street, address_zip, address_city, address_country, wysi
	            FROM
	                ".$cfg["tab"]["phplib_auth_user_md5"]."
	            WHERE
	                user_id = '".$userid."'";

	$db->query($sql);

	$user_perms = array ();
	$user_perms = explode(",", $rights_perms);
	$db2 = new DB_Contenido;

	$form = '<form name="user_properties" method="post" action="'.$sess->url("main.php?").'">
	                 '.$sess->hidden_session().'
	                 <input type="hidden" name="area" value="'.$area.'">
	                 <input type="hidden" name="action" value="user_createuser">
	                 <input type="hidden" name="frame" value="'.$frame.'">
	                 <input type="hidden" name="idlang" value="'.$lang.'">';

	$db->next_record();

	$tpl->set('s', 'FORM', $form);
	$tpl->set('s', 'BORDERCOLOR', $cfg["color"]["table_border"]);
	$tpl->set('s', 'BGCOLOR', $cfg["color"]["table_dark"]);
	$tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
	if ($error)
	{
		$notification->displayNotification("warning", $error);
	}

	$tpl->set('d', 'CATNAME', i18n("Property"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_header"]);
	$tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', i18n("Value"));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Username"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
	$tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "username", "", 40, 32));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Name"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "realname", $db->f("realname"), 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("New password"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("password", "password", "", 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Confirm new password"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("password", "passwordagain", "", 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("E-Mail"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "email", $db->f("email"), 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Phone number"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "telephone", $db->f("telephone"), 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Street"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "address_street", $db->f("address_street"), 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("ZIP code"));
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "address_zip", $db->f("address_zip"), 10, 10));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("City"));
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "address_city", $db->f("address_city"), 40, 255));
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Country"));
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_dark"]);
	$tpl->set('d', 'CATFIELD', formGenerateField("text", "address_country", $db->f("address_country"), 40, 255));
	$tpl->next();

	$userperm = split(",", $auth->auth["perm"]);

	if (in_array("sysadmin", $userperm))
	{
		$tpl->set('d', 'CLASS', 'text_medium');
		$tpl->set('d', 'CATNAME', i18n("System administrator"));
		$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
		$tpl->set('d', "BGCOLOR", $cfg["color"]["table_light"]);
		$tpl->set('d', "CATFIELD", formGenerateCheckbox("msysadmin", "1", in_array("sysadmin", $user_perms)));
		$tpl->next();
	}

	$sql = "SELECT * FROM ".$cfg["tab"]["clients"];
	$db2->query($sql);
	$client_list = "";
	$gen = 0;
	while ($db2->next_record())
	{

		if (in_array("admin[".$db2->f("idclient")."]", $userperm) || in_array("sysadmin", $userperm))
		{
			$client_list .= formGenerateCheckbox("madmin[".$db2->f("idclient")."]", $db2->f("idclient"), in_array("admin[".$db2->f("idclient")."]", $user_perms), $db2->f("name")." (".$db2->f("idclient").")")."<br>";
			$gen = 1;
		}
	}

	if ($gen == 1)
	{
		$tpl->set('d', 'CLASS', 'text_medium');
		$tpl->set('d', 'CATNAME', i18n("Administrator"));
		$tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
		$tpl->set('d', "BGCOLOR", $cfg["color"]["table_dark"]);
		$tpl->set('d', "CATFIELD", $client_list);
		$tpl->next();
	}

	$sql = "SELECT * FROM ".$cfg["tab"]["clients"];
	$db2->query($sql);
	$client_list = "";

	while ($db2->next_record())
	{
		if (in_array("client[".$db2->f("idclient")."]", $userperm) || in_array("sysadmin", $userperm) || in_array("admin[".$db2->f("idclient")."]", $userperm))
		{
			$client_list .= formGenerateCheckbox("mclient[".$db2->f("idclient")."]", $db2->f("idclient"), in_array("client[".$db2->f("idclient")."]", $user_perms), $db2->f("name")." (".$db2->f("idclient").")")."<br>";
		}

	}
	$tpl->set('d', 'CLASS', 'text_medium');
	$tpl->set('d', 'CATNAME', i18n("Access clients"));
	$tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
	$tpl->set('d', "BGCOLOR", $cfg["color"]["table_light"]);
	$tpl->set('d', "CATFIELD", $client_list);
	$tpl->next();

	$sql = "SELECT
	                a.idlang as idlang,
	                a.name as name,
	                b.name as clientname FROM
	                ".$cfg["tab"]["lang"]." as a,
	                ".$cfg["tab"]["clients_lang"]." as c,
	                ".$cfg["tab"]["clients"]." as b
	                WHERE
	                    a.idlang = c.idlang AND
	                    c.idclient = b.idclient";

	$db2->query($sql);
	$client_list = "";

	while ($db2->next_record())
	{
		//            if($perm->have_perm_client_lang($client, $db2->f("idlang")in_array("lang[".$db2->f("idlang")."]",$userperm) || in_array("sysadmin",$userperm) || $perm->have_perm())
		if ($perm->have_perm_client("lang[".$db2->f("idlang")."]") || $perm->have_perm_client("admin[".$db2->f("idclient")."]"))
		{
			$client_list .= formGenerateCheckbox("mlang[".$db2->f("idlang")."]", $db2->f("idlang"), in_array("lang[".$db2->f("idlang")."]", $user_perms), $db2->f("name")." (".$db2->f("clientname").")")."<br>";
		}

	}
	$tpl->set('d', 'CLASS', 'text_medium');
	$tpl->set('d', 'CATNAME', i18n("Access languages"));
	$tpl->set('d', 'BORDERCOLOR', $cfg["color"]["table_border"]);
	$tpl->set('d', "BGCOLOR", $cfg["color"]["table_dark"]);
	$tpl->set('d', "CATFIELD", $client_list);
	$tpl->next();

	$tpl->set('d', 'CATNAME', i18n("Use WYSIWYG-Editor"));
	$tpl->set('d', "BORDERCOLOR", $cfg["color"]["table_border"]);
	$tpl->set('d', 'BGCOLOR', $cfg["color"]["table_light"]);
	$tpl->set('d', 'CATFIELD', formGenerateCheckbox("wysi", "1", true	  	));
	$tpl->next();

	$sInputValidFrom = '<style type="text/css">@import url(./scripts/jscalendar/calendar-contenido.css);</style>
					<script type="text/javascript" src="./scripts/jscalendar/calendar.js"></script>
					<script type="text/javascript" src="./scripts/jscalendar/lang/calendar-'.substr(strtolower($belang),0,2).'.js"></script>
					<script type="text/javascript" src="./scripts/jscalendar/calendar-setup.js"></script>';
	$sInputValidFrom .= '<input type="text" id="valid_from" name="valid_from" value="" />&nbsp;<img src="images/calendar.gif" id="trigger" /">';
	$sInputValidFrom .= '<script type="text/javascript">
  					Calendar.setup(
    					{
      					inputField  : "valid_from",
      					ifFormat    : "%Y-%m-%d",
      					button      : "trigger",
      					weekNumbers	: true,
      					firstDay	:	1
    					}
  					);
					</script>';

    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Valid from"));
    $tpl->set('d', 'BORDERCOLOR',  $cfg["color"]["table_border"]);
    $tpl->set('d', "BGCOLOR", $cfg["color"]["table_dark"]);
    $tpl->set('d', "CATFIELD", $sInputValidFrom);
    $tpl->next(); 

	$sInputValidTo = '<input type="text" id="valid_to" name="valid_to" value="" />&nbsp;<img src="images/calendar.gif" id="trigger_to" /">';
	$sInputValidTo .= '<script type="text/javascript">
  							Calendar.setup(
    							{
								inputField  : "valid_to",
								ifFormat    : "%Y-%m-%d",
								button      : "trigger_to",
		      					weekNumbers	: true,
		      					firstDay	:	1
							    }
							);
							</script>';

    $tpl->set('d', 'CLASS', 'text_medium');
    $tpl->set('d', 'CATNAME', i18n("Valid to"));
    $tpl->set('d', 'BORDERCOLOR',  $cfg["color"]["table_border"]);
    $tpl->set('d', "BGCOLOR", $cfg["color"]["table_light"]);
    $tpl->set('d', "CATFIELD", $sInputValidTo);
    $tpl->next(); 

	# Generate template
	$tpl->generate($cfg['path']['templates'].$cfg['templates']['rights_create']);
}
?>
