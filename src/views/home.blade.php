<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="http://cdn.auth0.com/js/lock/10.15.1/lock.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <script>

        function authorizeUser(idToken) {
            var http = new XMLHttpRequest();
            var url = "/stauth/authorize";
            var params = "token=" + idToken;

            http.open("POST", url, true);
            //Send the proper header information along with the request
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.setRequestHeader("Accept", "application/json");
            http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            http.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");

            http.onreadystatechange = function () {

                if (http.readyState === 4 && http.status === 200) {
                    window.location = '{{ url('/') }}';
                }

                if (http.readyState === 4 && http.status === 403) {
                    console.log('Unauthorized..');
                }

            };
            http.send(params);
        }

        function showLoginForm() {
            // Initiating our Auth0Lock
            var lock = new Auth0Lock(
                'T8e9hraC0ffSzT4kADqXrjrPTmoVY_x4',
                'st-auth.eu.auth0.com',
                {
                    auth: {
                        redirect: false
                    },
                    theme: {
                        logo: 'https://stauth.io/favicon-32x32.png',
                        primaryColor: '#397C6A',
                        displayName: 'Stauth protection'
                    },
                    closable: false
                }
            );

            lock.on("show", function() {
                document.body.addEventListener('DOMSubtreeModified', function(event) {
                    const elements = document.getElementsByClassName('auth0-lock-badge-bottom');
                    while (elements.length > 0) elements[0].remove();
                });
            });

            // Listening for the authenticated event
            lock.on("authenticated", function (authResult) {
                lock.getUserInfo(authResult.accessToken, function (error, profile) {
                    if (error) {
                        // Handle error
                        return;
                    }

                    var contentBox = document.getElementsByClassName('auth0-lock-confirmation-content')[0];
                    contentBox.innerHTML += 'Waiting for authorization...';

                    authorizeUser(authResult.idToken);
                    setInterval(authorizeUser, 10000, authResult.idToken);
                });
            });

            lock.show();
        }
    </script>
</head>
<body onload="showLoginForm();">
</body>
</html>