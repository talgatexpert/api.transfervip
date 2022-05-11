<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Notification</title>
</head>
<body>

<h1>Contact Notification</h1>
@foreach($items as $key => $text )
    <div>
        <span>{{ $key  }}</span>
        <span>{{$text}}</span>
    </div>
@endforeach

</body>
</html>