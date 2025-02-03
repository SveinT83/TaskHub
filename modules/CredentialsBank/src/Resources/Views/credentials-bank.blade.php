<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credentials Bank</title>
    <style>
        /* General styles */
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            height: 100%;
            overflow: hidden;
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
            background: #333;
            color: #fff;
            width: 250px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 50px;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 20px;
            margin-top: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar a:hover {
            background-color: #444;
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
            text-align: center;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        .modal-button {
            padding: 10px 20px;
            background-color: #4267B2;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .modal-button:hover {
            background-color: #365899;
        }

    </style>
</head>
<body>
    <div class="top-bar">
        <span>CredentialsBank</span>
    </div>

    <div class="sidebar">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </div>

    <div class="main-content">
        <div class="content">
            <h2>Your Encrypted Credentials</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody id="credentials-table">
                    <!-- Decrypted rows will be populated here -->
                </tbody>
            </table>

            <form id="credentials-form" method="POST" action="{{ route('credentials-bank.store') }}">
                @csrf
                <input type="text" id="username" name="username" placeholder="Enter Username" required />
                <input type="password" id="password" name="password" placeholder="Enter Password" required />
                <input type="hidden" id="encrypted_username" name="encrypted_username" />
                <input type="hidden" id="encrypted_password" name="encrypted_password" />
                <button type="submit" class="btn">Save</button>
            </form>

            <button id="downloadKey" class="btn">Download Private Key</button>
        </div>
    </div>

    <!-- Modal for Private Key Download -->
    <div id="key-modal" class="modal">
        <div class="modal-content">
            <h3>Important!</h3>
            <p>Download and securely store your private key. You will need it to decrypt your credentials.</p>
            <button class="modal-button" onclick="downloadPrivateKey()">Download Private Key</button>
        </div>
    </div>

    <script>
        let privateKey = null;

        // Fetch decrypted credentials and display them
        async function loadCredentials() {
            const response = await fetch('/credentials-bank');
            const credentials = await response.json();

            const table = document.getElementById('credentials-table');
            table.innerHTML = '';

            for (const credential of credentials) {
                const decryptedUsername = await decryptData(privateKey, credential.encrypted_username);
                const decryptedPassword = await decryptData(privateKey, credential.encrypted_password);

                const row = document.createElement('tr');
                row.innerHTML = `<td>${decryptedUsername}</td><td>${decryptedPassword}</td>`;
                table.appendChild(row);
            }
        }

        // Encrypt and submit credentials
        async function encryptAndSubmit(event) {
            event.preventDefault();

            const publicKey = await fetch('/get-public-key').then(res => res.text());
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const encryptedUsername = await encryptData(publicKey, username);
            const encryptedPassword = await encryptData(publicKey, password);

            document.getElementById('encrypted_username').value = encryptedUsername;
            document.getElementById('encrypted_password').value = encryptedPassword;

            document.getElementById('credentials-form').submit();
        }

        // Download the private key
        function downloadPrivateKey() {
            const blob = new Blob([privateKey], { type: 'text/plain' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'privateKey.txt';
            link.click();
        }

        // Show modal on first load to download private key
        window.onload = function() {
            // Simulate generation of private key here (for demo purposes)
            privateKey = 'generated_private_key_here';  // This key should be securely generated and stored elsewhere
            document.getElementById('key-modal').style.display = 'block';
        };

    </script>
</body>
</html>
