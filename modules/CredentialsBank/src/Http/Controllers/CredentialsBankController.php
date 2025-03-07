<?php

namespace Modules\CredentialsBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\CredentialsBank\Models\CredentialsBank;
use Illuminate\Support\Facades\Log;

class CredentialsBankController extends Controller
{
    private const AES_METHOD = 'aes-256-cbc';

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view credentials.');
        }

        try {
            $credentials = Auth::user()->credentialsBank()->paginate(10);

            foreach ($credentials as $credential) {
                if ($credential->uses_individual_key && !$credential->is_decrypted) {
                    $credential->decrypted_username = '*****';
                    $credential->decrypted_password = '*****';
                } else {
                    $decrypted = $this->decryptWithAES(
                        $credential->encrypted_username,
                        $credential->encrypted_aes_key,
                        $credential->iv,
                        $credential->encrypted_password
                    );
                    $credential->decrypted_username = $decrypted['username'];
                    $credential->decrypted_password = $decrypted['password'];
                }
            }
        } catch (\Exception $e) {
            Log::error("Error fetching credentials: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return view('credentialsbank::credentials-bank', compact('credentials'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|regex:/^\S*$/',
        'password' => 'required|string|regex:/^\S*$/',
        'use_individual_key' => 'sometimes|boolean',
    ], [
        'username.regex' => 'Username cannot contain spaces.',
        'password.regex' => 'Password cannot contain spaces.',
    ]);

    try {
        $aesKey = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::AES_METHOD));

        $encryptedUsernameData = $this->encryptWithAES($validated['username'], $aesKey, $iv);
        $encryptedPasswordData = $this->encryptWithAES($validated['password'], $aesKey, $iv);

        $usesIndividualKey = $request->input('use_individual_key', false);
        $individualKey = null;
        $downloadUrl = null;

        if ($usesIndividualKey) {
            $individualKey = base64_encode($aesKey);
            $fileName = 'user_' . Auth::id() . '_' . time() . '.txt';
            Storage::put("individual_keys/$fileName", $individualKey);
            Log::info("Individual key saved at: " . storage_path("app/individual_keys/$fileName"));

            // Ensure download URL is correctly set
            $downloadUrl = route('credentials-bank.download-key', ['file' => $fileName]);
        }

        // ✅ Ensure `$credential` is assigned BEFORE returning it
        $credential = Auth::user()->credentialsBank()->create([
            'encrypted_username' => $encryptedUsernameData['encrypted_data'],
            'encrypted_password' => $encryptedPasswordData['encrypted_data'],
            'encrypted_aes_key'  => $this->encryptAESKeyWithRSA($aesKey),
            'iv'                 => base64_encode($iv),
            'uses_individual_key' => $usesIndividualKey,
            'is_decrypted'        => false,
        ]);

        return response()->json([
            'message' => 'Credentials stored securely.',
            'credential' => [
                'id' => $credential->id,
                'uses_individual_key' => $credential->uses_individual_key,
                'decrypted_username' => $credential->uses_individual_key ? '*****' : $validated['username'],
                'decrypted_password' => $credential->uses_individual_key ? '*****' : $validated['password']
            ],
            'download_url' => $downloadUrl
        ]);
    } catch (\Exception $e) {
        Log::error("Error storing credentials: " . $e->getMessage());
        return response()->json(['error' => 'Failed to store credentials.'], 500);
    }
}

    public function decrypt(Request $request, $id)
    {
        $credential = CredentialsBank::findOrFail($id);
        $userKey = $request->input('individual_key');

        try {
            $aesKey = base64_decode($userKey);
            $decryptedUsername = openssl_decrypt(base64_decode($credential->encrypted_username), self::AES_METHOD, $aesKey, 0, base64_decode($credential->iv));
            $decryptedPassword = openssl_decrypt(base64_decode($credential->encrypted_password), self::AES_METHOD, $aesKey, 0, base64_decode($credential->iv));

            if ($decryptedUsername === false || $decryptedPassword === false) {
                Log::warning("Decryption failed for credential ID: {$credential->id} - Invalid Key Entered", [
                    'user_id' => Auth::id()
                ]);

                return response()->json(['error' => 'Invalid decryption key provided.'], 400);
            }

            $credential->update(['is_decrypted' => true]);

            return response()->json([
                'username' => $decryptedUsername,
                'password' => $decryptedPassword,
            ]);
        } catch (\Exception $e) {
            Log::error("Decryption error for credential ID: {$credential->id}", ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error decrypting credentials.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $credential = CredentialsBank::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|regex:/^\S*$/',
            'password' => 'required|string|regex:/^\S*$/',
        ]);

        try {
            $aesKey = openssl_random_pseudo_bytes(32);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::AES_METHOD));

            $encryptedUsernameData = $this->encryptWithAES($validated['username'], $aesKey, $iv);
            $encryptedPasswordData = $this->encryptWithAES($validated['password'], $aesKey, $iv);

            $encryptedAESKey = $this->encryptAESKeyWithRSA($aesKey);

            $credential->update([
                'encrypted_username' => $encryptedUsernameData['encrypted_data'],
                'encrypted_password' => $encryptedPasswordData['encrypted_data'],
                'encrypted_aes_key'  => $encryptedAESKey,
                'iv'                 => base64_encode($iv),
                'is_decrypted'       => false,
            ]);

            return redirect()->route('credentials-bank.index')->with('message', 'Credential updated successfully.');
        } catch (\Exception $e) {
            Log::error("Error updating credential: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update credential.'], 500);
        }
    }

    public function destroy($id)
    {
        $credential = CredentialsBank::findOrFail($id);
        $credential->delete();

        return redirect()->route('credentials-bank.index')->with('message', 'Credential deleted successfully.');
    }

    private function encryptWithAES($data, $aesKey, $iv)
    {
        $encryptedData = openssl_encrypt($data, self::AES_METHOD, $aesKey, 0, $iv);
        return ['encrypted_data' => base64_encode($encryptedData)];
    }

    private function decryptWithAES($encryptedUsername, $encryptedAESKey, $iv, $encryptedPassword = null)
    {
        $aesKey = $this->decryptAESKeyWithRSA($encryptedAESKey);

        return [
            'username' => openssl_decrypt(base64_decode($encryptedUsername), self::AES_METHOD, $aesKey, 0, base64_decode($iv)),
            'password' => $encryptedPassword
                ? openssl_decrypt(base64_decode($encryptedPassword), self::AES_METHOD, $aesKey, 0, base64_decode($iv))
                : null,
        ];
    }

    private function encryptAESKeyWithRSA($aesKey)
    {
        $publicKeyPath = storage_path('app/credentials-bank/public_key.pem');
        if (!file_exists($publicKeyPath)) {
            throw new \Exception('Public key not found.');
        }

        $publicKey = file_get_contents($publicKeyPath);
        if (!openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey)) {
            throw new \Exception('RSA encryption failed. Please check the public key.');
        }

        return base64_encode($encryptedAESKey);
    }

    private function decryptAESKeyWithRSA($encryptedAESKey)
    {
        $privateKeyPath = storage_path('app/credentials-bank/private_key.pem');
        if (!file_exists($privateKeyPath)) {
            throw new \Exception('Private key not found.');
        }

        $privateKey = file_get_contents($privateKeyPath);
        if (!openssl_private_decrypt(base64_decode($encryptedAESKey), $aesKey, $privateKey)) {
            throw new \Exception('RSA decryption failed.');
        }

        return $aesKey;
    }
}