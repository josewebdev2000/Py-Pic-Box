"""
        Py Pic Box

        Grab an image's base-64 data, upload it to the server and delete it after a while
"""

from flask import Flask, request, abort, send_file
from helpers import custom_response, get_unique_identifier, extract_image_extension, get_new_image_filename, get_img_url_from_base64_data, extract_pure_base_64
from dotenv import load_dotenv
from os import getenv, remove
from os.path import basename, join, exists

# Load env vars from .env file
load_dotenv()

# Grab the Allowed API Key
API_KEY = getenv("API_KEY")

# Folder to save pics
PICS_FOLDER_NAME = "pics"

app = Flask(__name__)

# Custom HTTP Error Handlers
@app.errorhandler(404)
def not_found(e):
    return custom_response({"error": "Resource Not Found"}, 404)

@app.errorhandler(405)
def method_not_allowed(e):
    return custom_response({"error": "Method Not Allowed"}, 405)

@app.errorhandler(415)
def unsuported_media_type(e):
    return custom_response({"error": "Unsuported Media Type"}, 415)

@app.errorhandler(500)
def internal_server_error(e):
    return custom_response({"error": "Internal Server Error"}, 500)

# API Routes
@app.route("/", methods=["POST"])
def home():
    default_response = {"message": "Py Pic Box Image Uploading API"}
    return custom_response(default_response, 200)

@app.route("/pics/<filename>", methods = ["GET"])
def pics(filename):
    try:
        return send_file(f"pics/{filename}")
    
    except FileNotFoundError:
        abort(404)
    
    except Exception:
        abort(500)

@app.route("/upload", methods=["POST"])
def upload():
    """API Route to upload an image to the server"""

    # Begin to build response message
    response = {}
    
    # Grab the JSON from the request
    request_data = request.json

    # Validate every single field of the request data
    try:
        validate_data_request(request_data, "upload")
    
    except Exception as e:
        return custom_response({"error": str(e)}, 400)
        
    # Create a file name for the new image
    img_folder = PICS_FOLDER_NAME
    img_name = f"{request_data.get('name', '')}-${get_unique_identifier()}"

    try:
        img_extension = extract_image_extension(request_data.get("imgbase64", ""))
    
    except Exception as e:
        return custom_response({"error": "Invalid Base 64 Data"}, 400)
    
    img_complete_filename = get_new_image_filename(img_folder, img_name, img_extension)

    # Now decode the base 64 code that represent the image
    try:
        # Grab pure base 64 code
        pure_64_code = extract_pure_base_64(request_data.get("imgbase64", ""))
        img_url = get_img_url_from_base64_data(pure_64_code, img_complete_filename, img_name, img_extension)

    except Exception as e:
        return custom_response({"error": "Could not upload image"}, 500)
    
    response["img_url"] = img_url

    return custom_response(response, 200)

@app.route("/delete", methods=["POST"])
def delete():
    """API Route to delete an image from a given URL"""
    
    # Grab the JSON from the request
    request_data = request.json

    # Validate every single field of the request data
    try:
        validate_data_request(request_data, "delete")
    
    except Exception as e:
        return custom_response({"error": str(e)}, 400)
    
    # Extract filename from the URL
    image_file_name = basename(request_data.get("imgurl", ""))
    complete_image_file_name = join(PICS_FOLDER_NAME, image_file_name)

    if exists(complete_image_file_name):

        try:
            remove(complete_image_file_name)
        except:
            return custom_response({"error": "Could not delete image"}, 500)

        return custom_response({"message": "The resource " + request_data.get("imgurl", "") + " was successfully deleted"}, 200)
    
    else:
        return custom_response({"error": "Given URL does not exist"}, 404)

# Functions
def validate_data_request(request_data, route_method = "upload"):
    """Validate data request from the user."""

    # First of all, check API key is right and set
    if (request_data.get("key", "") != API_KEY):
        raise Exception("Invalid API Key")

    if route_method.lower() == "upload":
        
        # Check the imgbase64 field contains a base64 image
        if not isinstance(request_data.get("imgbase64", ""), str):
            raise Exception ("Image Base 64 Code must come as a string")
        
        elif len(request_data.get("imgbase64")) < 1:
            raise Exception("Image Base 64 Code cannot be empty")
        
        if not isinstance(request_data.get("name", ""), str):
            raise Exception("Name must come as a string")
        
        elif len(request_data.get("name")) < 1:
            raise Exception("Name cannot be empty")

    elif route_method.lower() == "delete":
        
        if not isinstance(request_data.get("imgurl", ""), str):
            raise Exception ("Image URL must come as a string")
        
        elif len(request_data.get("imgurl", "")) < 1:
            raise Exception("Image URL cannot be empty")

    else:
        return True