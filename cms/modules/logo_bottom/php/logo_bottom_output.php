<?php
/**
 * Description: Display Logo on bottom of page
 *
 * @version    1.0.0
 * @author     Rudi Bieller
 * @copyright  four for business AG <www.4fb.de>
 *
 * {@internal
 *   created 2008-04-07
 *   $Id$
 * }}
 */

if (!isset($tpl) || !is_object($tpl)) {
    $tpl = new Template();
}

$tpl->reset();
$tpl->generate('templates/logo_bottom.html');

?>