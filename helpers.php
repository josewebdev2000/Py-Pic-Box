<?php 

function get_unique_identifier() 
{
    // Return an ID to be associated to an object
    return uniqid();
}

// Common Functions for Development of this API
function has_exact_keys($assoc_arr, $expected_keys)
{
    // Return true if the assoc array has the expected arrays
    // Get the keys of the array
    $array_keys = array_keys($assoc_arr);

    // Sort both arrays to ensure order doesn't matter
    sort($array_keys);
    sort($expected_keys);

    // Compare the sorted arrays
    return $array_keys === $expected_keys;
}

function get_base_url()
{
    // Check the server protocol
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // Get the server name
    $serverName = $_SERVER['SERVER_NAME'];

    // PHP SELF
    $scriptName = dirname($_SERVER["SCRIPT_NAME"]); 

    // Construct base URL
    $baseUrl = $protocol . $serverName . $scriptName;

    return $baseUrl . DIRECTORY_SEPARATOR;
}

function is_post_request()
{
    return $_SERVER["REQUEST_METHOD"] == "POST";
}

function is_get_request()
{
    return $_SERVER["REQUEST_METHOD"] == "GET";
}

function get_json_request()
{
    // Grab a JSON request from the front-end
    // Grab JSON data from the front-end as a file
    $jsonData = file_get_contents("php://input");

    // Decode the JSON data
    $decodedData = json_decode($jsonData, true);

    return $decodedData;
}

function extract_image_extension($base64_data) 
{
    if (preg_match('/data:image\/(.*?);/', $base64_data, $matches)) 
    {
        return $matches[1];
    } 
    
    else 
    {
        return "";
    }
}

function upload_img_from_base64_data($base64_data, $full_filename) 
{
    try 
    {
        // Decode base64 data
        $image_data = base64_decode($base64_data);
        
        // Create a file handle
        $file_handle = fopen($full_filename, 'w');
        
        // Write image data to the file
        fwrite($file_handle, $image_data);
        
        // Close the file handle
        fclose($file_handle);

        return ["success" => true];
    
    } 
    
    catch (Exception $e) 

    {
        return ["error" => "Image Could Not Be Uploaded"];
    }
}

function extract_pure_base_64($base64_html_string) 
{
    // Split the base64 string by comma
    $parts = explode(',', $base64_html_string);
    
    // Return the last part of the array
    return end($parts);
}

function get_new_image_filename($folder_path, $filename, $extension) 
{
    // Ensure extension starts with a dot and is in lowercase
    $extension_with_dot = strpos($extension, '.') === 0 ? strtolower($extension) : "." . strtolower($extension);
    
    // Concatenate filename and extension
    $filename_with_ext = $filename . $extension_with_dot;
    
    // Join folder path and filename with extension
    // Using DIRECTORY_SEPARATOR ensures the path is correct for the operating system
    return rtrim($folder_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename_with_ext;
}

function send_json_response($json_response)
{
    // Set the header to send a JSON response
    header("Content-Type: application/json");

    // Send it with echo
    echo json_encode($json_response);
}

function send_json_error_response($json_error_response, $error_http_code)
{
    // Send a JSON error to tell the client what went wrong
    http_response_code($error_http_code);

    // Set the header to send a JSON response
    header("Content-Type: application/json");

    echo json_encode($json_error_response);
}

?>