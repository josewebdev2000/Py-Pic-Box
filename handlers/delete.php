<?php require_once "helpers.php"; 

/*
error_reporting(E_ALL);

// Enable display of errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

// PHP Code to delete resources
$delete_data = get_json_request();

$img_url = $delete_data["img_url"];

// Grab the file path
$file_path = parse_url($img_url, PHP_URL_PATH);

$abs_path = $_SERVER["DOCUMENT_ROOT"] . $file_path;

if (file_exists($abs_path))
{
    if (unlink($abs_path))
    {
        return send_json_response(["success" => "The file that points to the URL $img_url has been successfully deleted"]);
    }

    else
    {
        return send_json_error_response(["error" => "Could not delete file found in $img_url"], 500);
    }
}

else
{
    return send_json_error_response(["error" => "The given URL $img_url points to a resource that could be found"], 404);
}

?>