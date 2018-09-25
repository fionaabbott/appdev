<?php
include "functions.php";

$searchURL = "https://abecrm.atlassian.net/wiki/rest/api/content/search?cql=text%20~%20%22password%22%20OR%20text%20~%20%22username%22";


//for general listing of content
$response = makeCurlCall($searchURL);
$response = json_decode($response);
/*
echo "<pre>";
print_r($response);
echo "</pre>";
*/
//$count = count(get_object_vars($response));

//echo "count: ".count($response->results)."<br />";

$topHTML = file_get_contents("top.html");
$topHTML = str_replace("{TITLE}","PCI Check Results",$topHTML);
$bottomHTML = file_get_contents("bottom.html");
$PCIcheckLinks = "";

buildLinks($response,$PCIcheckLinks);

echo $topHTML;

echo '<div class="col-sm-4"><h3>Results of PCI Content Search</h3>';
echo $PCIcheckLinks;
echo '</div>';


echo $bottomHTML;


?>