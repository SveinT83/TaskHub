<?php

namespace Modules\CredentialsBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Modules\CredentialsBank\Models\CredentialsBank;

class CredentialsBankController extends Controller
{
    /**
     * Fetch encrypted credentials for the authenticated user.
     */
    public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to view credentials.');
    }

    try {
        // Fetch the encrypted credentials
        $credentials = Auth::user()->credentialsBank()->get(['id', 'encrypted_username', 'encrypted_password']);

        // Decrypt each credential using the decryptWithAES function
        foreach ($credentials as $credential) {
            $decryptedUsername = $this->decryptWithAES($credential->encrypted_username, $credential->encrypted_aes_key, $credential->iv);
            $decryptedPassword = $this->decryptWithAES($credential->encrypted_password, $credential->encrypted_aes_key, $credential->iv);

            // Optionally, store decrypted values in the object for rendering
            $credential->decrypted_username = $decryptedUsername;
            $credential->decrypted_password = $decryptedPassword;
        }
    } catch (\Exception $e) {
        Log::error('Error fetching credentials: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch credentials.'], 500);
    }

    return view('credentialsbank::credentials-bank', compact('credentials'));
}

    /**
     * Store encrypted credentials for the authenticated user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'encrypted_username' => 'required|string',
            'encrypted_password' => 'required|string',
        ]);

        try {
            Auth::user()->credentialsBank()->create([
                'encrypted_username' => $validated['encrypted_username'],
                'encrypted_password' => $validated['encrypted_password'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing credentials: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to store credentials.'], 500);
        }

        return response()->json(['message' => 'Credentials stored securely.']);
    }

    /**
     * Provide the public key for encryption.
     */
    public function publicKey()
    {
        $publicKeyPath = storage_path('app/credentials-bank/public_key.pem');
        if (file_exists($publicKeyPath)) {
            return response()->json(['public_key' => file_get_contents($publicKeyPath)]);
        } else {
            Log::error('Public key not found.');
            return response()->json(['error' => 'Public key not found.'], 500);
        }
    }

    /**
     * Rotate the keys and re-encrypt all stored credentials.
     */
    public function rotateKeys(Request $request)
    {
        try {
            // Call the helper function to perform the key rotation logic
            $this->rotateEncryptionKeys();
            return response()->json(['message' => 'Keys rotated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error rotating keys: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to rotate keys.'], 500);
        }
    }

    /**
     * Helper function to rotate encryption keys.
     */
    private function rotateEncryptionKeys()
    {
        // Path to old keys (if applicable)
        $oldPrivateKeyPath = storage_path('app/credentials-bank/old_private_key.pem');
        $oldPublicKeyPath = storage_path('app/credentials-bank/old_public_key.pem');

        // New key pair generation
        $resource = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        // Extract private key and save
        openssl_pkey_export($resource, $privateKey);
        file_put_contents(storage_path('app/credentials-bank/private_key.pem'), $privateKey);

        // Extract public key and save
        $publicKey = openssl_pkey_get_details($resource)['key'];
        file_put_contents(storage_path('app/credentials-bank/public_key.pem'), $publicKey);

        // Optionally, store the old keys for reference
        if (file_exists($oldPrivateKeyPath)) {
            rename(storage_path('app/credentials-bank/private_key.pem'), $oldPrivateKeyPath);
            rename(storage_path('app/credentials-bank/public_key.pem'), $oldPublicKeyPath);
        }

        // Now, re-encrypt existing credentials (and any future credentials) with new keys
        $credentials = CredentialsBank::all();
        foreach ($credentials as $credential) {
            $newEncryptedUsername = $this->encryptWithAES($credential->encrypted_username);
            $newEncryptedPassword = $this->encryptWithAES($credential->encrypted_password);

            $credential->update([
                'encrypted_username' => $newEncryptedUsername['encrypted_data'],
                'encrypted_password' => $newEncryptedPassword['encrypted_data'],
            ]);
        }
    }

    /**
     * Encrypt data with AES encryption and RSA encryption for the AES key.
     */
    private function encryptWithAES($data)
    {
        // Generate AES key and IV
        $aesKey = openssl_random_pseudo_bytes(32); // 256-bit AES key
        $iv = openssl_random_pseudo_bytes(16); // AES block size for IV

        // Encrypt data with AES
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $aesKey, 0, $iv);

        // Encrypt the AES key with RSA
        $publicKey = file_get_contents(storage_path('app/credentials-bank/public_key.pem'));
        openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey);

        return [
            'encrypted_data' => base64_encode($encryptedData),
            'encrypted_aes_key' => base64_encode($encryptedAESKey),
            'iv' => base64_encode($iv),
        ];
    }

    /**
     * Decrypt AES-encrypted data using the stored private RSA key and AES key.
     */
    private function decryptWithAES($encryptedData, $encryptedAESKey, $iv)
    {
        // Load the private RSA key
        $privateKey = file_get_contents(storage_path('app/credentials-bank/private_key.pem'));

        // Decrypt the AES key with the private RSA key
        openssl_private_decrypt(base64_decode($encryptedAESKey), $aesKey, $privateKey);

        // Decrypt the actual data with the AES key
        return openssl_decrypt(base64_decode($encryptedData), 'aes-256-cbc', $aesKey, 0, base64_decode($iv));
    }
}
