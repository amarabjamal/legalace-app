<!DOCTYPE html>
<html>
<head>
    <title>Legal Ace</title>
</head>
<body>
    <h1>Password Reset</h1>
    <a href="{{ url('/resetpassword/' . $data['token'])}}">Reset Your Password</a>
</body>
</html>