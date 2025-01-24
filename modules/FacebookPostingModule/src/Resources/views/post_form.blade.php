<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Posting Module</title>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js"></script>
    <style>
        /* General styles */
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Adjusted to match the original */
            color: #333;
            height: 100%;
            overflow: hidden; /* Remove scrollbars */
        }

        /* Top bar styles */
        .top-bar {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            height: 50px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .top-bar span {
            margin-left: 5px;
        }

        /* Sidebar styles */
        .sidebar {
            background: #333; /* Dark grey */
            color: #fff;
            width: 250px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 50px; /* To align with the top bar */
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            margin-top: 20px; /* Lower placement for "Dashboard" */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar a:hover {
            background-color: #444; /* Slightly darker grey on hover */
        }

        /* Main content area */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Content box styles */
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
        <span>Facebook Posting Module</span>
    </div>

    <div class="sidebar">
        <a href="http://taskhub.local">Dashboard</a>
    </div>

    <div class="main-content">
        <div class="content">
            <h1>Login to Facebook</h1>
            <button class="btn" onclick="facebookLogin()">Login with Facebook</button>
        </div>
    </div>
</body>
</html>
