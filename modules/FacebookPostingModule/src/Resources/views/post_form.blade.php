<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Login</title>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js"></script>
    <style>
        /* General styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Top bar styles */
        .top-bar {
            background-color: #333;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
            font-size: 1.5rem;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }

        /* Sidebar styles */
        .sidebar {
            background-color: #e0e0e0;
            padding: 15px;
            position: absolute;
            top: 50px;
            bottom: 0;
            left: 0;
            width: 200px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Centered content styles */
        .content {
            background: #fff;
            padding: 20px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        /* Button styles */
        .btn {
            background-color: #4267B2;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #365899;
        }
    </style>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{ env('FACEBOOK_APP_ID') }}', // Facebook App ID
                cookie: true,
                xfbml: true,
                version: 'v21.0' // Latest Graph API version
            });
        };

        function facebookLogin() {
            FB.login(function(response) {
                if (response.authResponse) {
                    // Send the token to the backend
                    fetch('/facebook/exchange-token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            accessToken: response.authResponse.accessToken
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Login successful!');
                        } else {
                            alert('Login failed.');
                        }
                    });
                } else {
                    alert('User cancelled login or did not fully authorize.');
                }
            }, { scope: 'public_profile,email,pages_manage_posts,publish_to_groups' });
        }
    </script>
</head>
<body>
    <div class="top-bar">
        TaskHub - Facebook Login
    </div>

    <div class="sidebar">
        <h3>Menu</h3>
        <a href="{{ route('dashboard') }}">Dashboard</a><br>
        <a href="{{ route('facebook.login') }}">Facebook Login</a>
    </div>

    <div class="content">
        <h1>Login to Facebook</h1>
        <button class="btn" onclick="facebookLogin()">Login with Facebook</button>
    </div>
</body>
</html>
