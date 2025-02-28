<?php

namespace Modules\CredentialsBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        // ✅ Log successfully fetched credentials (Masking decrypted values for security)
        Log::info('Fetched credentials:', [
            'user_id' => Auth::id(),
            'credentials_count' => $credentials->count(),
            'masked_credentials' => $credentials->map(fn($cred) => [
                'id' => $cred->id,
                'masked_username' => str_repeat('*', strlen($cred->decrypted_username ?? '*****')),
                'masked_password' => str_repeat('*', strlen($cred->decrypted_password ?? '*****')),
            ]),
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching credentials: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => $e->getMessage()], 500);
    }

    return view('credentialsbank::credentials-bank', compact('credentials'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'use_individual_key' => 'sometimes|boolean',
        ]);

        try {
            $aesKey = openssl_random_pseudo_bytes(32);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::AES_METHOD));

            $encryptedUsernameData = $this->encryptWithAES($validated['username'], $aesKey, $iv);
            $encryptedPasswordData = $this->encryptWithAES($validated['password'], $aesKey, $iv);

            $usesIndividualKey = $request->input('use_individual_key', false);

            if ($usesIndividualKey) {
                $individualKey = base64_encode($aesKey);
                Storage::put('individual_keys/user_' . Auth::id() . '_' . time() . '.txt', $individualKey);
            }

            $encryptedAESKey = $this->encryptAESKeyWithRSA($aesKey);

            Auth::user()->credentialsBank()->create([
                'encrypted_username' => $encryptedUsernameData['encrypted_data'],
                'encrypted_password' => $encryptedPasswordData['encrypted_data'],
                'encrypted_aes_key'  => $encryptedAESKey,
                'iv'                 => base64_encode($iv),
                'uses_individual_key' => $usesIndividualKey,
                'is_decrypted'        => false, // Default to masked until user decrypts
            ]);

            return redirect()->route('credentials-bank.index')->with('message', 'Credentials stored securely.');
        } catch (\Exception $e) {
            Log::error('Error storing credentials: ' . $e->getMessage(), ['user_id' => Auth::id()]);
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

            $credential->update([
                'is_decrypted' => true,
            ]);

            return response()->json([
                'username' => $decryptedUsername,
                'password' => $decryptedPassword,
            ]);
        } catch (\Exception $e) {
            Log::error('Error decrypting credential: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            return response()->json(['error' => 'Invalid decryption key.'], 400);
        }
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