<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        <messages-component :auth-user-id="{{ auth()->id() }}"></messages-component>
    </div>
</body>
</html>
