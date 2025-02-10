<?php

namespace Modules\CredentialsBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\CredentialsBank\Models\CredentialsBank;

class CredentialsBankController extends Controller
{
    private const AES_METHOD = 'aes-256-cbc';

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view credentials.');
        }

        try {
            $credentials = Auth::user()->credentialsBank()->get([
                'id', 'encrypted_username', 'encrypted_password', 'encrypted_aes_key', 'iv'
            ]);

            foreach ($credentials as $credential) {
                $credential->decrypted_username = $this->decryptWithAES(
                    $credential->encrypted_username,
                    $credential->encrypted_aes_key,
                    $credential->iv
                );
                $credential->decrypted_password = $this->decryptWithAES(
                    $credential->encrypted_password,
                    $credential->encrypted_aes_key,
                    $credential->iv
                );
            }
        } catch (\Exception $e) {
            Log::error('Error fetching credentials: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return response()->json(['error' => 'Failed to fetch credentials.'], 500);
        }

        return view('credentialsbank::credentials-bank', compact('credentials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $aesKey = openssl_random_pseudo_bytes(32);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::AES_METHOD));

            $encryptedUsernameData = $this->encryptWithAES($validated['username'], $aesKey, $iv);
            $encryptedPasswordData = $this->encryptWithAES($validated['password'], $aesKey, $iv);

            $encryptedAESKey = $this->encryptAESKeyWithRSA($aesKey);

            Auth::user()->credentialsBank()->create([
                'encrypted_username' => $encryptedUsernameData['encrypted_data'],
                'encrypted_password' => $encryptedPasswordData['encrypted_data'],
                'encrypted_aes_key'  => $encryptedAESKey,
                'iv'                 => base64_encode($iv),
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing credentials: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return response()->json(['error' => 'Failed to store credentials.'], 500);
        }

        return redirect()->route('credentials-bank.index')->with('message', 'Credentials stored securely.');
    }

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

    public function rotateKeys(Request $request)
    {
        try {
            $this->rotateEncryptionKeys();
            return response()->json(['message' => 'Keys rotated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error rotating keys: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to rotate keys.'], 500);
        }
    }

    private function rotateEncryptionKeys()
    {
        $oldPrivateKeyPath = storage_path('app/credentials-bank/old_private_key.pem');
        $oldPublicKeyPath = storage_path('app/credentials-bank/old_public_key.pem');

        $resource = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($resource, $newPrivateKey);
        file_put_contents(storage_path('app/credentials-bank/private_key.pem'), $newPrivateKey);

        $newPublicKey = openssl_pkey_get_details($resource)['key'];
        file_put_contents(storage_path('app/credentials-bank/public_key.pem'), $newPublicKey);

        if (file_exists($oldPrivateKeyPath)) {
            rename(storage_path('app/credentials-bank/private_key.pem'), $oldPrivateKeyPath);
            rename(storage_path('app/credentials-bank/public_key.pem'), $oldPublicKeyPath);
        }

        // Re-encrypt the AES keys with the new RSA public key.
        $this->reEncryptAESKeys();
    }

    private function reEncryptAESKeys()
    {
        $credentials = CredentialsBank::all();
        foreach ($credentials as $credential) {
            $aesKey = $this->decryptAESKeyWithRSA($credential->encrypted_aes_key);
            $newEncryptedAESKey = $this->encryptAESKeyWithRSA($aesKey);
            $credential->update(['encrypted_aes_key' => $newEncryptedAESKey]);
        }
    }

    private function encryptWithAES($data, $aesKey, $iv)
    {
        $encryptedData = openssl_encrypt($data, self::AES_METHOD, $aesKey, 0, $iv);
        return ['encrypted_data' => base64_encode($encryptedData)];
    }

    private function decryptWithAES($encryptedData, $encryptedAESKey, $iv)
    {
        $aesKey = $this->decryptAESKeyWithRSA($encryptedAESKey);
        return openssl_decrypt(base64_decode($encryptedData), self::AES_METHOD, $aesKey, 0, base64_decode($iv));
    }

    private function encryptAESKeyWithRSA($aesKey)
    {
        $publicKeyPath = storage_path('app/credentials-bank/public_key.pem');
        if (!file_exists($publicKeyPath)) {
            throw new \Exception('Public key not found.');
        }
        $publicKey = file_get_contents($publicKeyPath);
        openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey);
        return base64_encode($encryptedAESKey);
    }

    private function decryptAESKeyWithRSA($encryptedAESKey)
    {
        $privateKeyPath = storage_path('app/credentials-bank/private_key.pem');
        if (!file_exists($privateKeyPath)) {
            throw new \Exception('Private key not found.');
        }
        $privateKey = file_get_contents($privateKeyPath);
        openssl_private_decrypt(base64_decode($encryptedAESKey), $aesKey, $privateKey);
        return $aesKey;
    }
}