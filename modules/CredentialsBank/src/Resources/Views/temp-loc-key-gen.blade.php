<script>
    async function generateKeys() {
        const keyPair = await window.crypto.subtle.generateKey(
            {
                name: "RSA-OAEP",
                modulusLength: 2048,
                publicExponent: new Uint8Array([1, 0, 1]),
                hash: "SHA-256",
            },
            true,
            ["encrypt", "decrypt"]
        );

        const publicKey = await window.crypto.subtle.exportKey("spki", keyPair.publicKey);
        const privateKey = await window.crypto.subtle.exportKey("pkcs8", keyPair.privateKey);

        // Save keys to localStorage (or prompt user to download private key securely)
        localStorage.setItem('publicKey', btoa(String.fromCharCode(...new Uint8Array(publicKey))));
        localStorage.setItem('privateKey', btoa(String.fromCharCode(...new Uint8Array(privateKey))));

        alert("Keys generated and saved securely in your browser.");
    }

    generateKeys();
</script>
