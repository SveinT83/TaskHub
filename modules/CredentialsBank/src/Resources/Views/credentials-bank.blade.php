<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credentials Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #4267B2;
            color: white;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }

        .btn-primary { background-color: #4267B2; }
        .btn-danger { background-color: #d9534f; }
        .btn-warning { background-color: #f0ad4e; }
        .btn-secondary { background-color: #5bc0de; }

        .masked {
            font-weight: bold;
            letter-spacing: 3px;
        }

        #individualKeyNotice {
            color: #d9534f;
            font-weight: bold;
            margin-top: 5px;
            display: none;
        }

        #downloadKeyButton {
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>Your Encrypted Credentials</h2>

    @if(session('message'))
        <div style="color: green;">{{ session('message') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="credentials-table">
            @foreach($credentials as $credential)
                <tr>
                    <td>
                        @if($credential->uses_individual_key && !$credential->is_decrypted)
                            <span class="masked">*****</span>
                        @else
                            {{ $credential->decrypted_username }}
                        @endif
                    </td>
                    <td>
                        @if($credential->uses_individual_key && !$credential->is_decrypted)
                            <span class="masked">*****</span>
                            <button onclick="promptForKey('{{ $credential->id }}')">🔑 Decrypt</button>
                        @else
                            <input type="password" value="{{ $credential->decrypted_password }}" readonly id="pass-{{ $credential->id }}">
                            <button onclick="togglePassword({{ $credential->id }})">👁️</button>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="openEditModal('{{ $credential->id }}', '{{ $credential->decrypted_username }}', '{{ $credential->decrypted_password }}')">✏️ Edit</button>
                        <button class="btn btn-danger" onclick="openDeleteModal('{{ $credential->id }}')">🗑️ Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Add New Credential</h3>
    <form action="{{ route('credentials-bank.store') }}" method="POST" id="addForm" onsubmit="return handleFormSubmit(event)">
        @csrf
        <input type="text" name="username" placeholder="Enter Username" required><br><br>
        <input type="password" name="password" placeholder="Enter Password" required><br><br>
        <input type="checkbox" name="use_individual_key" id="use_individual_key" onchange="toggleIndividualKey()">
        <label for="use_individual_key">Use Individual Decryption Key</label><br>
        <small id="individualKeyNotice">⚠️ This will download a private key, keep it safe!</small><br><br>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" id="downloadKeyButton" class="btn btn-secondary" onclick="downloadIndividualKey()">Download Key Again</button>
    </form>

    <script>
        let individualKey = '';
        let currentCredentialId = '';

        function togglePassword(id) {
            const input = document.getElementById('pass-' + id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function handleFormSubmit(event) {
            if (document.getElementById('use_individual_key').checked) {
                event.preventDefault();
                downloadIndividualKey(() => {
                    setTimeout(() => {
                        document.getElementById('addForm').submit();
                    }, 500);
                });
            }
        }

        function toggleIndividualKey() {
            const notice = document.getElementById('individualKeyNotice');
            const downloadBtn = document.getElementById('downloadKeyButton');

            if (document.getElementById('use_individual_key').checked) {
                notice.style.display = 'block';
                downloadBtn.style.display = 'none';
                individualKey = btoa(window.crypto.getRandomValues(new Uint8Array(32)).join(''));
            } else {
                notice.style.display = 'none';
                downloadBtn.style.display = 'none';
                individualKey = '';
            }
        }

        function downloadIndividualKey(callback) {
            if (!individualKey) {
                alert("No individual key available to download.");
                return;
            }

            const blob = new Blob([individualKey], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'individual_key.txt';
            a.click();
            URL.revokeObjectURL(url);

            document.getElementById('downloadKeyButton').style.display = 'block';

            if (callback) callback();
        }

        function promptForKey(credentialId) {
            const userKey = prompt("Enter your individual key:");
            if (userKey) {
                alert("Decryption logic would run here using the key: " + userKey);
            } else {
                alert("Decryption cancelled.");
            }
        }
    </script>

</body>
</html>