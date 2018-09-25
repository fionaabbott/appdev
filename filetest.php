<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="https://file.myfontastic.com/NKswsFyxiJD5odyk5WkAvG/icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="portalSiteTest.css" type="text/css" rel="stylesheet">
  <link href="inside.css" type="text/css" rel="stylesheet" />
<title>TierPoint Application Addresses</title>
<style>
table,td,th,tr{font-size:.8vw}
tr:nth-child(even) {background-color: #f2f2f2;}
td,th{border:1px solid black;padding:2px}
</style>
</head>
<body>

<?php
$filename = "./uploads/TierPointApplicationAddresses.csv";

$file = file ($filename);
if (!$file) {
    echo "<p>Unable to open remote file for reading.\n";
    exit;
}

//echo "<pre>";
//print_r($file);
//echo "</pre>";

function isLink($string){
    if(strstr($string,"http") !== false){
        return true;
    }
}

function linkify($row_arr){
    foreach($row_arr as $key=>$cell){
        if(isLink($cell)){
            $row_arr[$key] = '<a href="'.$cell.'">'.$cell.'</a>';
        }
    }

    return implode(",",$row_arr);
}

function cleanUpCSV($file){
    $file_arr = array();
    $max = 0;
    foreach($file as $row=>$str){
        //$str = trim($str,",");
        //echo "row: ".$row."<br />";
        //echo "str: ".$str."<br />";

        //[a-z-[aeiuo]]
        $match = preg_match("/^(?!,).*$/",$str);
        if($match === 1){
            $arr = explode(",",$str);
            //echo "<pre>";
            //print_r($arr);
            //echo "</pre>";
            if(count($arr) > $max){
                $max = count($arr);
            }
            array_push($file_arr,$arr);        $arr = explode(",",$str);
        //echo "<pre>";
        //print_r($arr);
        //echo "</pre>";
        if(count($arr) > $max){
            $max = count($arr);
        }
        //array_push($file_arr,$arr);
        }

    }

    //echo "Max: ".$max."<br />";

    foreach($file_arr as $key=>$arr){
        if(count($arr) < $max){
            array_pad($arr,$max," ");
            $file_arr[$key] = $arr;
        }
    }
    return $file_arr;
}

$file = cleanUpCSV($file);
//echo "<pre>";
//print_r($file);
//echo "</pre>";

$table = "<table border='1' cellpadding='3' cellspacing='0'>";

foreach($file as $rownum =>$row){
    
    //$row_arr=explode(",",$row);  
    $row = linkify($row);
    if($rownum === 0){
        $row = str_replace(",","</th><th>",$row);
        $row = "<tr><th>".$row."</th></tr>";
    }else{
        $row = str_replace(",","</td><td>",$row);
        $row = "<tr><td>".$row."</td></tr>";
    }

    $table .= $row;
}

$table .= "</table>";
//echo $table;

function get_mime_type($file) {
    $mtype = false;
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $file);
        finfo_close($finfo);
    } elseif (function_exists('mime_content_type')) {
        $mtype = mime_content_type($file);
    } 
    return $mtype;
}

function handleFileUpload($files,$filename){
    try {
    
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
            !isset($files['upfile']['error']) ||
            is_array($files['upfile']['error'])
        ) {
            throw new RuntimeException('Invalid parameters.');
        }
    
        // Check $_FILES['upfile']['error'] value.
        switch ($files['upfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
    
        // You should also check filesize here. 
        if ($files['upfile']['size'] > 1000000) {
            throw new RuntimeException('Exceeded filesize limit.');
        }
    
        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        //$mime = mime_content_type($files['upfile']['tmp_name']);
        $mime = get_mime_type($files['upfile']['tmp_name']);

        //echo $mime."<br />";

        //echo "<pre>";
        //print_r($files);
        //echo "</pre>";
        $allowed_mimes = array('application/vnd.ms-excel','text/plain','text/csv');
        if (!in_array($mime,$allowed_mimes)) {
            throw new RuntimeException("Invalid file format: {$files['upfile']['tmp_name']}");
        }

        $ext = "csv";
    
        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        if (!move_uploaded_file(
            $files['upfile']['tmp_name'],
            $filename
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    
        echo 'File is uploaded successfully.';
    
    } catch (RuntimeException $e) {
    
        echo $e->getMessage();
    
    }
    
}


if(!empty($_FILES)){
    handleFileUpload($_FILES,$filename);
}

function getLastModifiedFile($dir){
    if ($handle = opendir($dir)) {
        echo "Directory handle: $handle\n";
        echo "Entries:\n";
    
        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            echo "$entry<br />";
            echo "last modified: ".date('M d, Y',filemtime($dir.$entry))."<br /><br />";
        }
    
    
        closedir($handle);
    }
}

//getLastModifiedFile("./uploads/");

?>
<div class="container-fluid">
<h1>TierPoint Application Addresses</h1>

<div class="alert alert-info" role="alert">
Please upload a .csv file to update the information displayed below:
</div>

<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div class="form-row align-items-center">
<div class="col-sm-1 my-1">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- Name of input element determines name in $_FILES array -->
    <label for="upfile">Send this file: </label>
    </div>
    <div class="col-sm-2 my-1"><input name="upfile" type="file" class="form-control-file" />
    </div>
    <div class="col-sm-1 my-1">
    <input type="submit" value="Send File" />
    </div>
    </div>
</form>
<?php
echo $table;
?>
</div>
</body>
</html>