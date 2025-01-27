<div id="app">
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

    <form id="credentials-form" onsubmit="encryptAndSubmit(event)">
        <input type="text" id="username" placeholder="Enter Username" required />
        <input type="password" id="password" placeholder="Enter Password" required />
        <input type="hidden" id="encrypted_username" name="encrypted_username" />
        <input type="hidden" id="encrypted_password" name="encrypted_password" />
        <button type="submit">Save</button>
    </form>
</div>

<script>
    const privateKey = localStorage.getItem('privateKey');
    if (!privateKey) {
        alert('No private key found! Generate and save one securely before using this feature.');
        // Optionally redirect to a key generation page.
    }

    // Encryption and Decryption Functions
    async function encryptAndSubmit(event) {
        event.preventDefault();

        const publicKey = localStorage.getItem('publicKey');
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        const encryptedUsername = await encryptData(publicKey, username);
        const encryptedPassword = await encryptData(publicKey, password);

        document.getElementById('encrypted_username').value = encryptedUsername;
        document.getElementById('encrypted_password').value = encryptedPassword;

        document.getElementById('credentials-form').submit();
    }

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

    loadCredentials();
</script>
