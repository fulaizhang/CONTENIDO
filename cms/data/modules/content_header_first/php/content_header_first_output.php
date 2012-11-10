<?php

/**
 * description: standard first header (H1)
 * Header will not be output if no or an empty text is given.
 *
 * @package Module
 * @subpackage content_header_first
 * @version SVN Revision $Rev:$
 * @author marcus.gnass@4fb.de
 * @copyright four for business AG
 * @link http://www.4fb.de
 */

// get header as content type HTMLHEAD with index 1
$header = "CMS_HTMLHEAD[1]";

$label = NULL;
if (cRegistry::isBackendEditMode()) {
	// When in backend edit mode add a label so the author
	// knows what to type in the shown field.
    $label = mi18n("LABEL_HEADER_FIRST");
} else {
	// When not in backend edit mode any tags are removed
	// for the template is responsible for displaying the
	// given text as a header.
    $header = str_replace('&nbsp;', ' ', $header);
	$header = strip_tags($header);
	$header = trim($header);
}

// use smarty template to output header text
$tpl = Contenido_SmartyWrapper::getInstance();
$tpl->assign('label', $label);
$tpl->assign('header', $header);
$tpl->display('content_header_first/template/get.tpl');

?>