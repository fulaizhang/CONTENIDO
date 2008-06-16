<?php
/**
 * File      :   $RCSfile$
 * Project   :   Contenido
 * Descr     :   Cron Job to move old statistics into the stat_archive table
 *
 * Author    :   Bj�rn Behrens
 *
 * Created   :   26.05.2003
 * Modified  :   $Date: 2007/10/12 13:53:00 $
 *
 * @version $Revision$
 * @copyright four for business AG, www.4fb.de
 *
 * @internal  {
 *  modified 2008-06-16, H. Librenz - Hotfix: Added check for malicious script call
 *
 *  $Id$
 * }
 **/
if (isset($_REQUEST['cfg']) || isset($_REQUEST['contenido_path'])) {
    die ('Illegal call!');
}

if (isset($cfg['path']['contenido'])) {
	include_once ($cfg['path']['contenido'].$cfg["path"]["includes"] . 'startup.php');
} else {
	include_once ('../includes/startup.php');
}

cInclude ("classes", 'class.genericdb.php');
cInclude ("classes", 'class.properties.php');
cInclude ("classes", 'class.newsletter.jobs.php');

global $cfg;

if(!isRunningFromWeb || function_exists("runJob") || $area == "cronjobs")
{
	$oJobs = new cNewsletterJobCollection;
	$oJobs->setWhere("status", 1);
	$oJobs->setWhere("use_cronjob", 1);
	$oJobs->setLimit("0", "1"); 		// Load only one job at a time
	$oJobs->setOrder("created DESC");	// Newest job will be run first
	$oJobs->query();

	if ($oJob = $oJobs->next())
	{
		// Active jobs found, run job
		$oJob->runJob();
	} else {

		// Nothing to do, check dead jobs
		$oJobs->resetQuery();
		$oJobs->setWhere("status", 2);
		$oJobs->setWhere("use_cronjob", 1);
		$oJobs->setLimit("0", "1"); 		// Load only one job at a time
		$oJobs->setOrder("created DESC");	// Newest job will be run first
		$oJobs->query();

		if ($oJob = $oJobs->next())
		{
			// Maybe hanging jobs found, run job
			$oJob->runJob();

		}


	}

}
?>
