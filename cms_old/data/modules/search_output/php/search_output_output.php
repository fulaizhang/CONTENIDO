<?php
/**
 * Description: Search output box
 *
 * @version    1.0.2
 * @author     Willi Man
 * @copyright  four for business AG <www.4fb.de>
 *
 * {@internal
 *   created 2004-05-04
 *   $Id$
 * }}
 */

// System properties in use:
// Type: searchrange, Name: include
// Contains comma-separated list of cats to be included into search (sub-cats are included automatically)

// Logical combination of search terms with AND or OR

define('CON_SEARCH_ITEMSPERPAGE', 10);
define('CON_SEARCH_MAXLEN_TEASERTEXT', 200);

// Includes
cInclude('includes', 'functions.api.string.php');

// Initiliaze template object
if (!is_object($tpl)) {
    $tpl = new cTemplate();
}
$tpl->reset();

$cApiClient = new cApiClient($client);
$sSearchRange = $cApiClient->getProperty('searchrange', 'include');
$aSearchRange = explode(',', $sSearchRange);

// Multilingual settings
$sYourSearchFor = mi18n("YOUR_SEARCH_FOR");
$sMore = mi18n("MORE");

// Get search term and pre-process it
if (isset ($_GET['searchterm'])) {
    $searchterm = urldecode(conHtmlentities(strip_tags(stripslashes($_GET['searchterm']))));
} elseif (isset ($_POST['searchterm'])) {
    $searchterm = urldecode(conHtmlentities(strip_tags(stripslashes($_POST['searchterm']))));
}
$searchterm = str_replace(' + ', ' AND ', $searchterm);
$searchterm = str_replace(' - ', ' NOT ', $searchterm);
$searchterm_display = $searchterm;

// Get all article specs
$sql = "SELECT idartspec, artspec FROM " . $cfg['tab']['art_spec'] . " WHERE "
     . "client=$client AND lang=$lang AND online=1";

$db->query($sql);
$rows = $db->num_rows();
$aArtspecOnline = array();
$aArtSpecs = array();
$c = 1;
$d = 1;
$e = 1;
while ($db->next_record()) {
    $aArtSpecs[] = $db->f('idartspec');
}
$aArtSpecs[] = 0;

if (strlen(trim($searchterm)) > 0) {
    // Parse search term and set search options
    $searchterm = conHtmlEntityDecode($searchterm);

    if (stristr($searchterm, ' or ') === false) {
        $combine = 'and';
    } else {
        $combine = 'or';
    }
    $searchterm = str_replace(' and ', ' ', strtolower($searchterm));
    $searchterm = str_replace(' or ', ' ', strtolower($searchterm));

    $options = array(
        'db' => 'regexp', // use db function regexp
        'combine' => $combine, // combine searchterms with and
        'exclude' => false, // => searchrange specified in 'cat_tree', 'categories' and 'articles' is excluded, otherwise included (exclusive)
        'cat_tree' => $aSearchRange, // searchrange
        'artspecs' => $aArtSpecs, // array of article specifications => search only articles with these artspecs
        'protected' => true, // => do not search articles or articles in categories which are offline or protected
    );

    $search = new cSearch($options);

    $cms_options = array('head', 'html', 'htmlhead', 'htmltext', 'text'); // search only in these cms-types
    $search->setCmsOptions($cms_options);

    // Execute search
    $aSearchResults = $search->searchIndex($searchterm, '');

    // Build results page
    if (count($aSearchResults) > 0) {
        $tpl->set('s', 'result_page', mi18n("RESULT_PAGE").':');

        // Build meessage
        $message = $sYourSearchFor." '".conHtmlSpecialChars(strip_tags($searchterm_display))."' ".mi18n("GAVE_RESULTS").":";
        $message = str_replace('$$$', count($aSearchResults), $message);
        $tpl->set('s', 'MESSAGE', $message);

        // Number of results per page
        $number_of_results = CON_SEARCH_ITEMSPERPAGE;
        $oSearchResults = new cSearchResult($aSearchResults, $number_of_results);

        $num_res = $oSearchResults->getNumberOfResults() + $pdf_count;
        $num_pages = $oSearchResults->getNumberOfPages();
        $oSearchResults->setReplacement('<strong>', '</strong>'); // html-tags to emphasize the located searchterms

        // Get current result page
        if (isset ($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
            $page = $_GET['page'];
            $res_page = $oSearchResults->getSearchResultPage($page);
        } else {
            $page = 1;
            $res_page = $oSearchResults->getSearchResultPage($page);
        }

        // Build links to other result pages
        for ($i = 1; $i <= $num_pages; $i ++) {
            // this is just for sample client - modify to your needs!
            if ($cfg['url_builder']['name'] == 'front_content' || $cfg['url_builder']['name'] == 'MR') {
                $aParams = array(
                    'lang' => $lang, 'idcat' => $idcat, 'idart' => $idart,
                    'searchterm' => $searchterm_display, 'page' => ($i.$sArtSpecs)
                );
            } else {
                $aParams = array(
                    'search' => array('lang' => $lang, 'idcat' => $idcat, 'idart' => $idart,
                    'searchterm' => $searchterm_display, 'page' => ($i.$sArtSpecs)),
                    'idcat' => $idcat, // needed to build category path
                    'lang' => $lang, // needed to build category path
                    'level' => 1, // needed to build category path
                );
            }
            try {
                $nextlink = cUri::getInstance()->build($aParams);
            } catch (cInvalidArgumentException $e) {
                $nextlink = $sess->url('front_content.php?idcat='.$idcat.'&idart='.$idart.'&searchterm='.$searchterm_display.'&page='.$i.$sArtSpecs);
            }
            if ($i == $page) {
                $nextlinks .= '<span style="white-space:nowrap;"> <strong>'.$i.'</strong> </span>';
            } else {
                $nextlinks .= '<span style="white-space:nowrap;"> <a href="'.$nextlink.'" title="'.$i.'. '.mi18n("VIEW_RESULT_PAGE").'">'.$i.'</a> </span>';
            }
        }
        $tpl->set('s', 'PAGES', $nextlinks);

        // Build link to next result page
        if ($page < $num_pages) {
            $n = $page +1;
            // this is just for sample client - modify to your needs!
            if ($cfg['url_builder']['name'] == 'front_content' || $cfg['url_builder']['name'] == 'MR') {
                $aParams = array(
                    'lang' => $lang, 'idcat' => $idcat, 'idart' => $idart,
                    'searchterm' => $searchterm_display, 'page' => ($n.$sArtSpecs)
                );
            } else {
                $aParams = array(
                    'search' => array('lang' => $lang, 'idcat' => $idcat, 'idart' => $idart,
                    'searchterm' => $searchterm_display, 'page' => ($n.$sArtSpecs)),
                    'idcat' => $idcat, // needed to build category path
                    'lang' => $lang, // needed to build category path
                    'level' => 1, // needed to build category path
                );
            }
            try {
                $next = cUri::getInstance()->build($aParams);
            } catch (cInvalidArgumentException $e) {
                $next = $sess->url('front_content.php?idcat='.$idcat.'&idart='.$idart.'&searchterm='.$searchterm.'&page='.$n.$sArtSpecs);
            }
            $nextpage .= ' <a href="'.$next.'" title="'.mi18n("VIEW_NEXT_RESULT_PAGE").'">'.mi18n("NEXT").'  <img src="images/link_pfeil_klein.gif" alt="" /></a>';
            $tpl->set('s', 'NEXT', $nextpage);
        } else {
            $tpl->set('s', 'NEXT', '');
        }

        // Build link to previous result page
        if ($page > 1) {
            $p = $page -1;
            // this is just for sample client - modify to your needs!
            if ($cfg['url_builder']['name'] == 'front_content' || $cfg['url_builder']['name'] == 'MR') {
                $aParams = array(
                    'lang' => $lang, 'idcat' => $idcat, 'idart' => $idart,
                    'searchterm' => $searchterm_display, 'page' => ($p.$sArtSpecs)
                );
            } else {
                $aParams = array(
                    'search' => array('lang' => $lang, 'idcat' => $idcat, 'idart' => $idart,
                    'searchterm' => $searchterm_display, 'page' => ($p.$sArtSpecs)),
                    'idcat' => $idcat, // needed to build category path
                    'lang' => $lang, // needed to build category path
                    'level' => 1, // needed to build category path
                );
            }
            try {
                $pre = cUri::getInstance()->build($aParams);
            } catch (cInvalidArgumentException $e) {
                $pre = $sess->url('front_content.php?idcat='.$idcat.'&idart='.$idart.'&searchterm='.$searchterm.'&page='.$p.$sArtSpecs);
            }
            $prevpage .= '<a href="'.$pre.'" title="'.mi18n("VIEW_PREVIOUS_PAGE").'"><img src="images/link_pfeil_klein_links.gif" alt="" />  '.mi18n("PREVIOUS").'</a> ';
            $tpl->set('s', 'PREV', $prevpage);
        } else {
            $tpl->set('s', 'PREV', '');
        }

        if (count($res_page) > 0) {
            $i = 1;
            // Build single search result on result page
            foreach ($res_page as $key => $val) {
                $num = $i + (($page -1) * $number_of_results);
                $oArt = new cApiArticleLanguage();
                $oArt->loadByArticleAndLanguageId($key, $lang);
                // Get publishing date of article
                $pub_system = $oArt->getField('published');
                $pub_user = trim(strip_tags($oArt->getContent('HEAD', 90)));
                if ($pub_user != '') {
                    $show_pub_date = "[".$pub_user."]";
                } else {
                    $show_pub_date = '';
                    if ($pub_system[8] != '0') {
                        $show_pub_date .= $pub_system[8];
                    }
                    $show_pub_date .= $pub_system[9].'.';
                    if ($pub_system[5] != '0') {
                        $show_pub_date .= $pub_system[5];
                    }
                    $show_pub_date .= $pub_system[6].".".$pub_system[0].$pub_system[1].$pub_system[2].$pub_system[3]."]";
                    $show_pub_date = "[".$show_pub_date;
                }

                // Get text and headline of current article
                $aHeadline = $oSearchResults->getSearchContent($key, 'HTMLHEAD', 1);
                $aSubheadline = $oSearchResults->getSearchContent($key, 'HTMLHEAD', 2);
                $text = $oSearchResults->getSearchContent($key, 'HTML', 1);
                $text = cApiStrTrimAfterWord($text[0], CON_SEARCH_MAXLEN_TEASERTEXT);
                $headline = cApiStrTrimAfterWord($aHeadline[0], CON_SEARCH_MAXLEN_TEASERTEXT); # conflict with cApiStrTrimAfterWord and setReplacement('<strong>', '</strong>')
                $subheadline = cApiStrTrimAfterWord($aSubheadline[0], CON_SEARCH_MAXLEN_TEASERTEXT);

                $cat_id = $oSearchResults->getArtCat($key);
                $similarity = $oSearchResults->getSimilarity($key);

                $similarity = sprintf("%.0f", $similarity);

                // Send output to template
                // this is just for sample client - modify to your needs!
                if ($cfg['url_builder']['name'] == 'front_content' || $cfg['url_builder']['name'] == 'MR') {
                    $aParams = array('lang' => $lang, 'idcat' => $cat_id, 'idart' => $key);
                } else {
                    $aParams = array(
                        'search' => array('lang' => $lang, 'idcat' => $cat_id, 'idart' => $key),
                        'idcat' => $idcat, // needed to build category path
                        'lang' => $lang, // needed to build category path
                        'level' => 1, // needed to build category path
                    );
                }
                try {
                    $href = cUri::getInstance()->build($aParams);
                } catch (cInvalidArgumentException $e) {
                    $href = $sess->url("front_content.php?idcat=$cat_id&idart=$key");
                }
                $tpl->set('d', 'more', $sMore);
                $tpl->set('d', 'HREF', $href);
                $tpl->set('d', 'TITLE', mi18n("LINK_SEARCH_RESULTS").' '.$i);
                $tpl->set('d', 'NUM', $num);
                $tpl->set('d', 'CATNAME', $headline);
                $tpl->set('d', 'HEADLINE', $text);
                $tpl->set('d', 'SUBHEADLINE', $subheadline);
                $tpl->set('d', 'SIMILARITY', $similarity);
                $tpl->set('d', 'TARGET', '_self');
                $tpl->set('d', 'PUB_DATE', $show_pub_date);
                $tpl->next();
                $i ++;

            }
            $tpl->generate('templates/search_output.html');
        }
    } else {
        // No results
        $tpl->set('s', 'MESSAGE', $sYourSearchFor." '".conHtmlSpecialChars(strip_tags($searchterm))."' ".mi18n("GAVE_NO_RESULTS").".");
        $tpl->set('s', 'NEXT', '');
        $tpl->set('s', 'PREV', '');
        $tpl->set('s', 'PAGES', '');
        $tpl->set('s', 'result_page', '');
        $tpl->generate('templates/search_output.html');
    }
} else {
    echo '<div id="searchResults">';
    echo '<h1>'.mi18n("TXT_NO_SEARCH_RESULTS").'</h1>';
    echo '</div>';
}

/**
 * @deprecated 2012-08-24 Use cApiArticleSpecificationCollection instead
 */
class Article_Property {
    var $globalConfig;
    var $oDBInstance;

    /**
     * Constructor
     * Hint: Call constructor with Article_Property($db, $cfg);
     * @param  oDBInstance instance of class DB_Contenido
     * @param  globalConfig
     * @deprecated 2012-08-24 Use cApiArticleSpecificationCollection instead
     */
    function Article_Property($oDBInstance, $globalConfig) {
        cDeprecated('Use cApiArticleConfiguration instead');
        $this->globalConfig = $globalConfig;
        $this->oDBInstance = $oDBInstance;
    }

    /**
     * Get specification of an article
     *
     * @param   $iArticleId
     * @return  id of article specification
     * @deprecated 2012-08-24 Use cApiArticleSpecificationCollection instead
     */
    function getArticleSpecification($iArticleId, $iLangId) {
        cDeprecated('Use cApiArticleSpecificationCollection instead');
        $sql = "SELECT artspec FROM " . $this->globalConfig['tab']['art_lang'] . " WHERE "
             . "idart=" . (int) $iArticleId . " AND idlang=" . (int) $iLangId." AND searchable = 1";

        #echo "<pre>$sql</pre>";
        $this->oDBInstance->query($sql);

        if ($this->oDBInstance->next_record()) {
            return $this->oDBInstance->f('artspec');
        } else {
            return false;
        }
    }
}

?>