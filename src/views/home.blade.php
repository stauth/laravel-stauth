<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

    <script src="http://cdn.auth0.com/js/lock/10.15.1/lock.min.js"></script>
    <script src="https://www.stauth.io/js/laravel-protection.js"></script>
</head>
<body onload="showLoginForm('{{ csrf_token() }}', '{{ url('/') }}');">
</body>
</html>