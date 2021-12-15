<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Role Index</title>
</head>
<body>
<h1>You are in Roles Index</h1>
<h2>List of Roles is here</h2>
@foreach ($roles as $role)
    {{ $role->title }} -- {{ $role->slug }} <br>
@endforeach
</body>
</html>