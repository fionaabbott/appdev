<?php
include "functions.php";
$basicContentURL = "https://abecrm.atlassian.net/wiki/rest/api/content/";
$searchURL = "https://abecrm.atlassian.net/wiki/rest/api/content/search?cql=space=AD%20and%20type=page%20and%20lastmodified%20%3E%20startOfWeek%28%29";
$searchURL2 = "https://abecrm.atlassian.net/wiki/rest/api/content/search?cql=type=page%20and%20lastmodified%20%3E%20startOfWeek%28%29";

//for general listing of content
$responseGen = makeCurlCall($basicContentURL);
$responseGen = json_decode($responseGen);

//for AD space
$response = makeCurlCall($searchURL);
$response = json_decode($response);

//for all spaces last modified in the past week
$allSpaces = makeCurlCall($searchURL2);
$allSpaces = json_decode($allSpaces);

//echo "<pre>";
//print_r($response);
//echo "</pre>";
$topHTML = file_get_contents("top.html");
$topHTML = str_replace("{TITLE}","Confluence API Proof of Concept",$topHTML);
$bottomHTML = file_get_contents("bottom.html");
$confluenceGeneralContentLinks = "";
$adLastModifiedLinks = "";
$allSpacesLastModifiedLInks = "";

buildLinks($responseGen,$confluenceGeneralContentLinks);
buildLinks($response,$adLastModifiedLinks);
buildLinks($allSpaces,$allSpacesLastModifiedLInks);

echo $topHTML;

echo '<div class="col-sm-4"><h3>Confluence General Links</h3>';
echo $confluenceGeneralContentLinks;
echo '</div>';

echo '<div class="col-sm-4"><h3>Apps Dev Content Updated in the Last Week</h3>';
echo $adLastModifiedLinks;
echo '</div>';

echo '<div class="col-sm-4"><h3>Content in All Spaces Updated in the Last Week</h3>';
echo $allSpacesLastModifiedLInks;
echo '</div>';
echo "<p>Quick little change to test git.</p>";

echo $bottomHTML;

?>