<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post to Facebook</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        /* Add styles for layout */
    </style>
</head>
<body>
    <div class="top-bar">
        <h2>TaskHub - Facebook Posting</h2>
    </div>

    <div class="sidebar">
        <h3>Sidebar</h3>
        <a href="{{ route('facebook.post-form') }}">Home</a>
    </div>

    <div class="content">
        <div class="container">
            <h1>Login to Facebook</h1>

            <!-- Facebook Login Button using the route for Facebook authentication -->
            <a href="{{ route('facebook.login') }}" class="btn">Login with Facebook</a>

            <div id="status"></div>

            <div id="facebookPostForm" style="display: none;">
                <h2>Post to Facebook</h2>
                <form id="postForm">
                    <textarea id="message" required placeholder="Write your message here..."></textarea>
                    <button type="button" onclick="postToFacebook()">Post</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Facebook login function
        function facebookLogin() {
            FB.login(function(response) {
                if (response.authResponse) {
                    document.getElementById('status').innerHTML = 'Logged in!';
                    localStorage.setItem('facebook_token', response.authResponse.accessToken);
                    document.getElementById('facebookPostForm').style.display = 'block';
                } else {
                    document.getElementById('status').innerHTML = 'Login failed.';
                }
            }, { scope: 'public_profile,email,manage_pages,publish_to_groups' });
        }

        // Function to handle posting to Facebook
        function postToFacebook() {
            const token = localStorage.getItem('facebook_token');
            const message = document.getElementById('message').value;
            fetch('/facebook/post-to-wall', {
                method: 'POST',
                body: JSON.stringify({ message, accessToken: token }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => alert(data.success ? 'Post successful!' : 'Post failed.'));
        }
    </script>
</body>
</html>
