<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <tr data-id="{{ $credential->id }}">
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
                        <form action="{{ route('credentials-bank.destroy', $credential->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Add New Credential</h3>
    <form action="{{ route('credentials-bank.store') }}" method="POST" id="addForm" onsubmit="return handleFormSubmit(event)">
        @csrf
        <input type="text" name="username" placeholder="Enter Username" required id="usernameField"><br><br>
        <input type="password" name="password" placeholder="Enter Password" required id="passwordField"><br><br>
        <input type="checkbox" name="use_individual_key" id="use_individual_key" onchange="toggleIndividualKey()">
        <label for="use_individual_key">Use Individual Decryption Key</label><br>
        <small id="individualKeyNotice">⚠️ This will download a private key, keep it safe! If you lose it, we cannot recover the information.</small><br><br>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" id="downloadKeyButton" class="btn btn-secondary" onclick="downloadIndividualKey()" style="display: none;">Download Key Again</button>
    </form>

    <script>
    console.log("🚀 Script Loaded!"); // ✅ Debugging: Check if script runs

    function handleFormSubmit(event) {
        event.preventDefault(); // ✅ Prevents default form submission

        const form = document.getElementById("addForm");
        const submitButton = form.querySelector("button[type='submit']");

        // ✅ Prevent double submissions
        if (submitButton.disabled) return;
        submitButton.disabled = true;

        const formData = new FormData(form);
        formData.append("use_individual_key", document.getElementById("use_individual_key").checked ? 1 : 0);

        console.log("📡 Submitting Form...");

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            },
        })
        .then(response => response.json())
        .then(data => {
            console.log("📨 Server Response:", data);

            if (data.error) {
                alert(data.error);
                submitButton.disabled = false; // ✅ Re-enable button on error
                return;
            }

            if (!data.credential) {
                console.error("❌ Credential data is missing from response:", data);
                alert("Something went wrong. Please try again.");
                submitButton.disabled = false; // ✅ Re-enable button on error
                return;
            }

            // ✅ If an individual key was used, trigger download WITHOUT REDIRECT
            if (data.download_url) {
    console.log("⬇️ Downloading Key from:", data.download_url);

    fetch(data.download_url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            console.log("Fetch response OK, creating blob...");
            return response.blob();
        })
        .then(blob => {
            console.log("Blob created, triggering download...");
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = url;
            a.download = "individual_key.txt";
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            console.log("Download should have started.");
        })
        .catch(error => {
            console.error("Download error:", error);
        });
}

            // ✅ Append new credential row dynamically
            const tableBody = document.getElementById("credentials-table");
            const newRow = document.createElement("tr");
            newRow.setAttribute("data-id", data.credential.id);
            newRow.innerHTML = `
                <td>${data.credential.uses_individual_key ? '*****' : data.credential.decrypted_username}</td>
                <td>
                    ${data.credential.uses_individual_key ? '*****' : `<input type="password" value="${data.credential.decrypted_password}" readonly>`}
                    ${data.credential.uses_individual_key ? `<button onclick="promptForKey('${data.credential.id}')">🔑 Decrypt</button>` : ''}
                </td>
                <td>
                    <button class="btn btn-primary" onclick="openEditModal('${data.credential.id}', '${data.credential.decrypted_username}', '${data.credential.decrypted_password}')">✏️ Edit</button>
                    <form action="/credentials-bank/credentials/${data.credential.id}" method="POST" style="display:inline;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute("content")}">
                        <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                    </form>
                </td>
            `;
            tableBody.appendChild(newRow);

            form.reset();
            submitButton.disabled = false; // ✅ Re-enable button after success
        })
        .catch(error => {
            console.error("❌ Error:", error);
            alert("Something went wrong. Please try again.");
            submitButton.disabled = false; // ✅ Re-enable button on failure
        });
    }

    document.getElementById("addForm").addEventListener("submit", handleFormSubmit);

    function toggleIndividualKey() {
        const notice = document.getElementById('individualKeyNotice');
        const useKeyCheckbox = document.getElementById('use_individual_key');

        if (useKeyCheckbox.checked) {
            notice.style.display = 'block';
        } else {
            notice.style.display = 'none';
        }
    }

    function togglePassword(id) {
        const input = document.getElementById('pass-' + id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function promptForKey(credentialId) {
    const userKey = prompt("Enter your individual key:");
    if (!userKey) {
        alert("Decryption cancelled.");
        return;
    }

    fetch(`/credentials-bank/decrypt/${credentialId}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ individual_key: userKey }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }

        // Update table row with decrypted values
        const row = document.querySelector(`tr[data-id='${credentialId}']`);
        row.children[0].innerHTML = data.username;
        row.children[1].innerHTML = `
            <input type="password" value="${data.password}" readonly id="pass-${credentialId}">
            <button onclick="togglePassword(${credentialId})">👁️</button>
        `;
    })
    .catch(error => {
        console.error("❌ Error:", error);
        alert("Failed to decrypt credentials.");
    });
}

    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll('#usernameField, #passwordField');

        inputs.forEach(input => {
            input.addEventListener("keydown", function (event) {
                if (event.key === " ") {
                    event.preventDefault();
                }
            });

            input.addEventListener("input", function () {
                this.value = this.value.replace(/\s/g, "");
            });
        });
    });
</script>

</body>
</html>