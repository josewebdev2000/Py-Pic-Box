# Py Pic Box
<div align="center">
<img src="photo.png" alt="Py Pic Icon" width="700">
</div>

## Overview

<div>
    <p>This project is my personal Web API to upload images to an online web server.</p>
    <h4>Why is this required?</h4>
    <p>Imagine you are working on a programming project that requires you to share images.</p>
    <p><b>How will you share those images to people that use other computers?</b></p>
    <p><b>Where will you host those images and access them through code?</b></p>
    <p>Well, I have answered those questions for myself by building this project.</p>
    <ol>
        <li>I will share images to people of other computer by uploading those images online and sharing the url of the image</li>
        <li>I will host them in my own web server that has a web service that gives me back the url for the images I uploaded</li>
    </ol>
</div>

## Mechanism
<div>
    <p>My Web API has three routes:</p>
    <ul style="list-style-type: square;">
        <li><b>/upload</b>: Route used to upload images to the server (POST Request Only)</li>
        <li><b>/delete</b>: Route used to delete images to the server (POST Request Only)</li>
        <li><b>/pics: Route used to show images stored in the web server (GET Request Only)</b></li>
    </ul>
    <p>My Web API takes a JSON string with the following fields for the <b>/upload</b> route:</p>
    <ul style="list-style-type: square;">
        <li><b>key</b>: <b>API KEY</b> required to use the API</li>
        <li><b>imgbase64</b>: Base 64 Code that contains the data that describes the image</li>
        <li><b>name</b>: Name the user wants for this image to have</li>
    </ul>
    <p>The <b>imgbase64</b> field expects the HTML Base 64 encoded version for image data that follows the following format: </p>
    <p><code>data:[mediatype];base64,</code></p>
    <p>Example:</p>
    <p><code>data:image/png;base64,{base64 pure code starts from here on}</code></p>
    <p>A successful response for the <b>/upload</b> route will contain this following JSON field:</p>
    <ul style="list-style-type: square;">
        <li><b>img_url</b>: URL to the image the user just uploaded through Base 64 Code</li>
    </ul>
    <p>My Web API takes a JSON string with the following fields for the <b>/delete</b> route:</p>
    <ul style="list-style-type: square;">
        <li><b>key</b>: <b>API KEY</b> required to used the API</li>
        <li><b>imgurl:</b> URL for the image hosted in the web server that is to be deleted</li>
    </ul>
    <p>A successful response for the <b>/delete</b> route will contain this following JSON field:</p>
    <ul style="list-style-type: square;">
        <li><b>message</b>: A confirmation that contains the old URL of the deleted image which leads to a 404 response</li>
    </ul>
    <p>An erroneous response for either the <b>/upload</b> route the <b>/delete</b> route will contain the following JSON field:</p>
    <ul style="list-style-type: square;">
        <li><b>error</b>: Description of the error raised</li>
    </ul>
    <p>To trigger a successful response from the <b>/pics</b> route:</p>
    <ol >
        <li>Trigger a GET request to <code>https://{link-to-api}/pics/{image-filename}</code></li>
        <li>Receive the image</li>
    </ol>
</div>

## Skills
<div>
    <p>The following technologies were required to compelte this project</p>
    <ul style="list-style-type: square;">
        <li><b>Python</b></li>
        <li><b>Flask</b></li>
    </ul>
</div>

## Copyright
<div>
    <blockquote>
        <a href="https://github.com/josewebdev2000">&copy; josewebdev2000</a> 2024
    </blockquote>
</div>