<?php require_once "helpers.php";

/*error_reporting(E_ALL);

// Enable display of errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

// PHP Code to upload image
// Grab Base 64 Code
// Grab Pure Code
// Convert it to image
// Save it to pics folder

$upload_data = get_json_request();

// Have the name of the pics folder
$folder = "pics";
$img_name = $upload_data["name"] . "-" . get_unique_identifier();

// Extract the image extension
$img_ext = extract_image_extension($upload_data["imgbase64"]);

// If no extension could be extracted, the base 64 string is invalid
if (empty($img_ext))
{
    return send_json_error_response(["error" => "Invalid Image Base 64 Field"], 400);
}

// Extract the pure base 64
$pure_base64 = extract_pure_base_64($upload_data["imgbase64"]);

// Form the new path for the image
$new_img_path = get_new_image_filename($folder, $img_name, $img_ext);

// Create the image file
$img_saved_status = upload_img_from_base64_data($pure_base64, $new_img_path);

if (isset($img_saved_status["error"]))
{
    return send_json_error_response($img_saved_status, 400);
}

// Build the Full URL for the image resource
$url_path = get_base_url().$new_img_path;

return send_json_response(["img_url" => $url_path]);

?>