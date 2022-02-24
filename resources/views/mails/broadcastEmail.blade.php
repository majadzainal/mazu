<!DOCTYPE html>
<html>
    <head>
        <style>

        </style>
    </head>
    <body>
        <h3>{{ $bc->header_text }}</h3>
        <p>{{ $bc->opening_text }}</p>
        <img src="{{ 'https://mazurejeki88-system.com/uploads/'.$bc->banner }}" style="width:100%" />
        <p>{{ $bc->content_text }}</p>
        <p>{{ $bc->regards_text }}</p>
        <p>{{ $bc->regards_value_text }}</p>
        <p>{{ $bc->footer_text }}</p>
    </body>
</html>
