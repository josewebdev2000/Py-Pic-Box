<?php 
require_once "envs.php";
require_once "helpers.php"; 

error_reporting(E_ALL);

// Enable display of errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Define allowed actions
$ACTIONS = ["upload", "delete"];

// Grab a POST request
if (is_post_request())
{
    // Read JSON
    $request_data = get_json_request();

    // Validate JSON data
    $data_validation_output = validate_json($request_data);

    //var_dump($data_validation_output);

    // Data is valid
    if (isset($data_validation_output["success"]))
    {
        if ($request_data["action"] == "upload")
        {
            require_once "handlers/upload.php";
        }

        elseif ($request_data["action"] == "delete")
        {
            require_once "handlers/delete.php";
        }
    }

    // Data is invalid
    else
    {
        return send_json_error_response($data_validation_output["error"] ,400);
    }
}

function validate_json($request_data)
{
    global $ACTIONS;

    // First of all, check API key is right and set
    if (!isset($request_data["key"]) || $request_data["key"] != API_KEY)
    {
        return ["error" => "Invalid API Key"];
    }

    // Check if the action key is set, is not empty, or at least its value is in the actions array
    if (!isset($request_data["action"]) || !in_array($request_data["action"], $ACTIONS))
    {
        return ["error" => "Invalid Action Field"];
    }

    elseif (strtolower($request_data["action"]) == "upload")
    {
        // Check the other three main keys to be there
        if (!isset($request_data["imgbase64"]) || empty($request_data["imgbase64"]))
        {
            return ["error" => "Invalid Image Base 64 Field"];
        }

        if (!isset($request_data["name"]) || empty($request_data["name"]))
        {
            return ["error" => "Invalid Name Field"];
        }
    }

    elseif (strtolower($request_data["action"]) == "delete")
    {

        if (!isset($request_data["img_url"]) || empty($request_data["img_url"]))
        {
            return ["error" => "Invalid Image URL"];
        }
    }

   return ["success" => true];
}

?>