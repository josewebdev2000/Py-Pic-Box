from flask import jsonify, url_for, request
from uuid import uuid4
from io import BytesIO
from PIL import Image
import os
import base64
import re

def custom_response(res_data, http_code):
    """Produce your own JSON response by providing JSON data along with an associated HTTP code"""
    
    res = jsonify(res_data)
    res.status_code = http_code
    return res

def get_unique_identifier():
    """Return an ID to be associated to an object."""
    
    return str(uuid4())

def get_img_url_from_base64_data(base64_data, full_filename, file_name, file_extension):
    try:
        # Decode base64 data
        image_data = base64.b64decode(base64_data)
        
        # Create BytesIO object
        image_stream = BytesIO(image_data)
        
        # Open the image using PIL (Python Imaging Library)
        image = Image.open(image_stream)

        image.save(full_filename)
    
    except Exception as e:
        raise Exception("Error decoding image:", e)
    
        # Return URL to the image
    return request.url_root[:-1] + url_for("pics", filename = file_name) + "." + file_extension

def check_keys(dictionary, keys):
    """Return true when JSON contains all expected keys"""
    return all(key in dictionary for key in keys)

def extract_pure_base_64(base64_html_string):
    """Remove the part that describe the image to get pure base 64 code"""
    return base64_html_string.split(",")[-1]

def extract_image_extension(base64_data):
    """Extract image extension from HTML Base 64 Image Code"""
    try:
        # Extract the image format from the base64 string
        format_match = re.search(r'(?<=data:image\/)(.*?)(?=;)', base64_data)
        if format_match:
            return format_match.group(0)
        else:
            return ""
    
    except Exception as e:
        raise Exception("Error extracting image extension:", e)

def get_new_image_filename(folder_path, filename, extension):
    """Return the name of a new image file name"""
    
    filename_with_ext = f"{filename}{extension.lower()}" if extension.startswith(".") else f"{filename}.{extension.lower()}"
    return os.path.join(folder_path, filename_with_ext)