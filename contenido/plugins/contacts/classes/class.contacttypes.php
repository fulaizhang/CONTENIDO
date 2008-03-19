<?php
/**********************************************************************************
* File			:   class.contacttypes.php
* Project		:   Contact Form Administration Plugin
*
* Author		:   Maxim Spivakovsky
*               
* Created		:   28.02.2006
* Modified		:   01.03.2006
*
* � four for business AG, www.4fb.de
***********************************************************************************/

class cContactTypes {

	var $oDBI;
	var $aGetByProperties = array("1=1");

	function cContactTypes ($oDBI)	{
		$this->oDBI = $oDBI;
	}

	function resetGetByProperties() {
		$this->aGetByProperties = array("1=1");
	}

	function addGetByProperty($sPropertyName, $sPropertyValue) {
		$this->aGetByProperties[] = $sPropertyName . "='" . urlencode($sPropertyValue) . "'";
	}

	function getIdContactType($client, $lang, $sContactType) {
        global $cfg;
        
		$sql = "SELECT
					idcontacttype
				FROM
					".$cfg['tab']['contact_types']."
				WHERE
					idclient='".$client."' AND
					idlang='".$lang."' AND
					type='".$sContactType."'";
		$this->oDBI->query($sql);
		
		if($this->oDBI->num_rows() > 0) {
			$this->oDBI->next_record();
			return $this->oDBI->f("idcontacttype");
		}
		else {
			return false;
		}
	}
	
	function getContactTypeById($iIdContactType) {
        global $cfg;
        
		$sql = "SELECT
					type
				FROM
					".$cfg['tab']['contact_types']."
				WHERE
					idcontacttype='".$iIdContactType."'";
		$this->oDBI->query($sql);
		
		$sResult = "";
		
		if($this->oDBI->num_rows() > 0) {
			$this->oDBI->next_record();
			return $this->oDBI->f("type");
		}
		else {
			return "";
		}
	}

	function getContactLabelByType($sContactType, $client, $lang) {
        global $cfg;
        
		$sql = "SELECT
					label
				FROM
					".$cfg['tab']['contact_types']."
				WHERE
					type='".$sContactType."' AND
					idclient='".$client."' AND
					idlang='".$lang."'";
		$this->oDBI->query($sql);
		
		$sResult = "";
		
		if($this->oDBI->num_rows() > 0) {
			$this->oDBI->next_record();
			return urldecode($this->oDBI->f("label"));
		}
		else {
			return "";
		}
	}

	function getContactTypes($sOrderBy = array("'idcontacttype'")) {
        global $cfg;
        
		$aExtractedFields = array("idclient", "idlang", "type", "label", "created", "createdby", "modified", "modifiedby");
		$sql = "SELECT
					*
				FROM
					".$cfg['tab']['contact_types']."
				WHERE " . (implode(" AND ", $this->aGetByProperties)) . "
				ORDER BY " . (implode(" ,", $sOrderBy));
					
		$this->oDBI->query($sql);
		
		$aResults = array();
		
		if($this->oDBI->num_rows() > 0) {
			while($this->oDBI->next_record()) {
				$iIdRecord = $this->oDBI->f('idcontacttype');
				foreach($aExtractedFields as $sFieldName) {
					$aResults[$iIdRecord][$sFieldName] = urldecode($this->oDBI->f($sFieldName));
				}
			}
		}
		
		return $aResults;
	}
	
	function existsContactType($iIdContactType) {
        global $cfg;
        
		$sql = "SELECT
					idcontacttype
				FROM
					".$cfg['tab']['contact_types']."
				WHERE
					idcontacttype='".$iIdContactType."'";
		$this->oDBI->query($sql);
		
		if($this->oDBI->num_rows() > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function makeContactType($sLabel) {
		return preg_replace("/[^a-z0-9]+/i", "_", strtolower(capiStrReplaceDiacritics($sLabel)));		
	}
	
	function storeContactType($sLabel, $client, $lang, $sIdAuthor) {
        global $cfg;
        
		$sType = $this->makeContactType($sLabel);
				
		$iIdContactTypeNext = $this->oDBI->nextid($cfg['tab']['contact_types']);
		
		$sql = "INSERT INTO
					".$cfg['tab']['contact_types']."
				SET
					idcontacttype='" . $iIdContactTypeNext . "', 
					idclient='".$client."',
					idlang='".$lang."',
					type='".$sType."',
					label='".urlencode($sLabel)."',
					created=NOW(),
					createdby='".$sIdAuthor."',
					modified=NOW(),
					modifiedby='".$sIdAuthor."'";
		$this->oDBI->query($sql);
		
		return $iIdContactTypeNext;
	}
	
	function deleteContactType($iIdContactType) {
        global $cfg;
        
		$sql = "DELETE FROM
					".$cfg['tab']['contact_types']."
				WHERE
					idcontacttype='".$iIdContactType."'";
		$this->oDBI->query($sql);
		
		return true;
	}
	
	
}
?>