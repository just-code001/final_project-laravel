<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Mail</title>
</head>
<body>
    <h1>{{$mailData['title']}}</h1>
    <h3>{{$mailData['body']}}</h3>
    <br><br>
    <p><b>OTP :- </b><b><u>{{$mailData['otp']}}</u></b></p>
    <br><br>
    <h5>Thank you for register.</h5>
</body>
</html>