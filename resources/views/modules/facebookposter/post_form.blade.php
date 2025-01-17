<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post to Facebook</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Adjust based on your CSS setup -->
</head>
<body>
    <div class="container">
        <h1>Post to Facebook</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('facebook.post') }}">
            @csrf
            <div class="form-group">
                <label for="page_id">Facebook Page ID</label>
                <input type="text" name="page_id" id="page_id" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Post to Facebook</button>
        </form>
    </div>
</body>
</html>
