<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    {{ $slot  }}

    @vite('resources/js/app.js')
</body>
</html>
