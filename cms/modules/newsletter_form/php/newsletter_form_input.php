?><?php
/**
 * Description: Newsletter form Input
 *
 * @version    1.0.0
 * @author     unknown
 * @copyright  four for business AG <www.4fb.de>
 *
 * {@internal
 *   created unknown
 *   $Id$
 * }}
 */

// Includes
cInclude('classes', 'class.input.helper.php'); # Note, this file is located in <client-path>/includes!

// Initialisation
$oClientLang = new cApiClientLanguage(false, $client, $lang);

/*
 *  Used variables:
 *  JoinSel:         Selection, which group will be joined (Default, Selected, UserSelected)
 *  JoinMultiple:    If JoinSel = UserSelected then: If more than one group has been specified, select more than one
 *  JoinGroups:      Selected group(s)
 *  JoinMessageType: Message type for new recipients: User select (user), text or html
 *  OptNewWindow:    Open handler window in new browser window?
 */
$aSettings = array(
    'JoinSel'         => $oClientLang->getProperty('newsletter', 'joinsel'),
    'JoinMultiple'    => $oClientLang->getProperty('newsletter', 'joinmultiple'),
    'JoinGroups'      => $oClientLang->getProperty('newsletter', 'joingroups'),
    'JoinMessageType' => $oClientLang->getProperty('newsletter', 'joinmessagetype'),
    'OptNewWindow'    => "CMS_VALUE[4]",
);

// Setting default values
/*
 *  If nothing is set or if the option "UserSelected" has been activated, but no groups
 *  have been selected, set option "Default". Note, that requiring to select groups when option
 *  "Selected" has been activated doesn't make so much sense here (even, as it is possible to do).
 *  Why? Because the groups to be joined have to be specified on the handler page, not here...
 *  To prevent users to select a group (or not to save the selection) when the option "Selected" is
 *  active should make more problems than just to ignore the selection in the Output area
 */

if ($aSettings['JoinSel'] == '' || ($aSettings['JoinSel'] == 'UserSelected' && $aSettings['JoinGroups'] == '')) {
    $aSettings['JoinSel'] = 'Default';
}
if (!is_numeric($_REQUEST['selHandlerCatArt'.$cnumber]) || $_REQUEST['selHandlerCatArt'.$cnumber] < 0) {
    $_REQUEST['selHandlerCatArt'.$cnumber] = 0;
}

// Saving changes, if any
if ($_REQUEST['hidAction'.$cnumber] == 'save') {
    if ($_REQUEST['radJoin'.$cnumber] != '' && $_REQUEST['radJoin'.$cnumber] != $aSettings['JoinSel']) {
        $aSettings['JoinSel'] = $_REQUEST['radJoin'.$cnumber];
        $oClientLang->setProperty('newsletter', 'joinsel', $aSettings['JoinSel']);
    }
    if ($_REQUEST['ckbJoinMultiple'.$cnumber] != $aSettings['JoinMultiple']) {
        $aSettings['JoinMultiple'] = $_REQUEST['ckbJoinMultiple'.$cnumber];
        $oClientLang->setProperty('newsletter', 'joinmultiple', $aSettings['JoinMultiple']);
    }
    if ($_REQUEST['hidJoinGroups'.$cnumber] != '' && $_REQUEST['hidJoinGroups'.$cnumber] != $aSettings['JoinGroups']) {
        $aSettings['JoinGroups'] = $_REQUEST['hidJoinGroups'.$cnumber];
        $oClientLang->setProperty('newsletter', 'joingroups', $aSettings['JoinGroups']);
    }
    if ($_REQUEST['selMessageType'.$cnumber] != $aSettings['JoinMessageType']) {
        $aSettings['JoinMessageType'] = $_REQUEST['selMessageType'.$cnumber];
        $oClientLang->setProperty('newsletter', 'joinmessagetype', $aSettings['JoinMessageType']);
    }
    if ($_REQUEST['ckbUpdateHandlerID'.$cnumber] == 'enabled') {
        // Trick: If UpdateHandlerID is enabled, save id as client setting
        $iHandlerCatArt = $_REQUEST['selHandlerCatArt'.$cnumber];
        $oClientLang->setProperty('newsletter', 'idcatart', $iHandlerCatArt);
    }
}
// Getting current handler article id
$iHandlerCatArt = $oClientLang->getProperty('newsletter', 'idcatart');
unset($oClientLang);

// Showing options
$oCfgTable = new UI_Config_Table();

$oHidAction = new cHTMLHiddenField('hidAction'.$cnumber, 'save');

$oSelHandlerCatArt = new cHTMLInputSelectElement('selHandlerCatArt'.$cnumber, 1, '', true);
$oOption           = new cHTMLOptionElement(mi18n("- Please select -"), '');
$oSelHandlerCatArt->addOptionElement(0, $oOption);
$oSelHandlerCatArt->addCategories(0, true, false, false, true, true);
$oSelHandlerCatArt->setDefault($iHandlerCatArt);

$oCkbUpdate = new cHTMLCheckbox('ckbUpdateHandlerID'.$cnumber, 'enabled');
$oCkbUpdate->setEvent('click', 'if (this.checked) {document.forms[0].selHandlerCatArt'.$cnumber.'.disabled = false;} else {document.forms[0].selHandlerCatArt'.$cnumber.'.disabled = true;}');

$oCfgTable->setCell('handler', 0, mi18n("Handler Article:"));
$oCfgTable->setCell('handler', 1, $oHidAction->render().$oSelHandlerCatArt->render()."\n&nbsp;".$oCkbUpdate->toHTML(false).mi18n("Update"));

// Getting newsletter groups (if any)
$oRcpGroups = new NewsletterRecipientGroupCollection();
$oRcpGroups->setWhere('idclient', $client);
$oRcpGroups->setWhere('idlang',   $lang);
$oRcpGroups->setWhere('defaultgroup', '0');
$oRcpGroups->setOrder('defaultgroup DESC, groupname ASC');
$oRcpGroups->query();

// Join options
// If newsletter groups are available, provide group options, otherwise show only
// 'Default' option. This is necessary, as there may have been groups specified (and used)
// but they have been deleted, later on.

$oCfgTable->setCell('join_01', 0, mi18n("Join:"));

if ($oRcpGroups->Count() == 0) {
    // No groups available, only default group possible

    $oRadJoinDefault = new cHTMLRadioButton('radJoin'.$cnumber, 'Default', '', true);
    $oCfgTable->setCell('join_01', 1, $oRadJoinDefault->toHTML(false).mi18n("default group"));
} else {
    // Groups available, show different group join options

    // Join default group only
    if ($aSettings['JoinSel'] == 'Default') {
        $oRadJoinDefault = new cHTMLRadioButton('radJoin'.$cnumber, 'Default', '', true);
    } else {
        $oRadJoinDefault = new cHTMLRadioButton('radJoin'.$cnumber, 'Default');
    }
    $oRadJoinDefault->setEvent('click', "document.forms[0].elements['ckbJoinMultiple".$cnumber."'].disabled = true; document.forms[0].selGroup".$cnumber.".disabled = true;");
    $oCfgTable->setCell('join_01', 1, $oRadJoinDefault->toHTML(false).mi18n("default group"));
    $oCfgTable->setRowBgColor('join_01', $cfg["color"]["table_light"]);

    // Join admin selected groups automatically
    if ($aSettings['JoinSel'] == 'Selected') {
        $oRadJoinSelected = new cHTMLRadioButton('radJoin'.$cnumber, 'Selected', '', true);
    } else {
        $oRadJoinSelected = new cHTMLRadioButton('radJoin'.$cnumber, 'Selected');
    }
    $oRadJoinSelected->setEvent('click', "document.forms[0].elements['ckbJoinMultiple".$cnumber."'].disabled = false; document.forms[0].selGroup".$cnumber.".disabled = false;");
    $oCfgTable->setCell('join_02', 0, '');
    $oCfgTable->setCell('join_02', 1, $oRadJoinSelected->toHTML(false).mi18n("selected group(s)"));
    $oCfgTable->setRowBgColor('join_02', $cfg["color"]["table_light"]);

    // Join the groups the user has selected (-> provide a list for the user), optionally, the user may select more than one group
    if ($aSettings['JoinSel'] == 'UserSelected') {
        $oRadJoinUserSel  = new cHTMLRadioButton('radJoin'.$cnumber, 'UserSelected', '', true);
        $oCkbJoinMultiple = new cHTMLCheckbox('ckbJoinMultiple'.$cnumber, 'enabled', '', $aSettings['JoinMultiple']);
    } else {
        $oRadJoinUserSel  = new cHTMLRadioButton('radJoin'.$cnumber, 'UserSelected');
        $oCkbJoinMultiple = new cHTMLCheckbox('ckbJoinMultiple'.$cnumber, 'enabled', '', false, true);
    }
    $oRadJoinUserSel->setEvent('click', "document.forms[0].elements['ckbJoinMultiple".$cnumber."'].disabled = false; document.forms[0].selGroup".$cnumber.".disabled = false;");
    $oCfgTable->setCell('join_03', 0, '');
    $oCfgTable->setCell('join_03', 1, $oRadJoinUserSel->toHTML(false).mi18n("group(s) as selected by user").'<br />'."\n".$oCkbJoinMultiple->toHTML(false).mi18n("Multiple group selection allowed"));
    $oCfgTable->setRowBgColor('join_03', $cfg["color"]["table_light"]);

    $oCfgTable->setCell('groups', 0, mi18n("Select group(s):"));

    // Show groups
    // Trick: To save multiple selections in <select>-Element, add some JS which saves the
    // selection, comma separated in a hidden input field on change.
    $sSkript = '              <script type="text/javascript"><!--'."\n".
             '                  function fncUpdateSel() {'."\n".
             '                     var strSel = "";'."\n".
             '                     for(i = 0; i < document.forms[0].selGroup'.$cnumber.'.length; i++) {'."\n".
             '                        if(document.forms[0].selGroup'.$cnumber.'.options[i].selected == true) {'."\n".
             '                           if (strSel != "")'."\n".
             '                              strSel = strSel + ",";'."\n".
             '                           strSel = strSel + document.forms[0].selGroup'.$cnumber.'.options[i].value;'."\n".
             '                        }'."\n".
             '                     }'."\n".
             '                     document.forms[0].elements["hidJoinGroups'.$cnumber.'"].value = strSel;'."\n".
             '                  }'."\n".
             '                  //--></script>'."\n";

    if ($aSettings['JoinSel'] == 'Default') {
        $oSelGroup = new cHTMLSelectElement('selGroup'.$cnumber, '', '', true);
    } else {
        $oSelGroup = new cHTMLSelectElement('selGroup'.$cnumber, '');
    }
    $oSelGroup->setSize(5);
    $oSelGroup->setMultiselect();
    $oSelGroup->setEvent('change', "fncUpdateSel()");

    $aGroups = explode(',', $aSettings['JoinGroups']);
    while ($oRcpGroup = $oRcpGroups->next()) {
        $iID = $oRcpGroup->get('idnewsgroup');
        if (in_array($iID, $aGroups)) {
            $oOption = new cHTMLOptionElement($oRcpGroup->get('groupname'), $iID, true);
        } else {
            $oOption = new cHTMLOptionElement($oRcpGroup->get('groupname'), $iID, false);
        }
        $oSelGroup->addOptionElement($iID, $oOption);
    }

    $oHidGroups = new cHTMLHiddenField('hidJoinGroups'.$cnumber, $aSettings['JoinGroups']);
    $oCfgTable->setCell('groups', 1, $sSkript.$oSelGroup->render().$oHidGroups->render());
}

// Options: Message type (user [->selectbox], text or html)
$oCfgTable->setCell('options_01', 0, mi18n("Options:"));

$oSelMsgType = new cHTMLSelectElement('selMessageType'.$cnumber);
$oOption = new cHTMLOptionElement(mi18n("User selected"), "user");
$oSelMsgType->addOptionElement(0, $oOption);
$oOption = new cHTMLOptionElement(mi18n("Text only"), "text");
$oSelMsgType->addOptionElement(1, $oOption);
$oOption = new cHTMLOptionElement(mi18n("HTML and text"), "html");
$oSelMsgType->addOptionElement(2, $oOption);
$oSelMsgType->setDefault($aSettings['JoinMessageType']);

$oCfgTable->setCell('options_01', 1, mi18n("Default message type:").'&nbsp;'.$oSelMsgType->render());

// Options: Open handler article in new window?
$oCfgTable->setCell('options_02', 0, '');
$oCkbNewWindow = new cHTMLCheckbox("CMS_VAR[4]", 'enabled', '', "CMS_VALUE[4]");
$oCfgTable->setCell('options_02', 1, $oCkbNewWindow->toHTML(false).mi18n("Open handler article in new window"));
$oCfgTable->setRowBgColor('options_02', $cfg["color"]["table_light"]);

$oCfgTable->render(true);

?><?php