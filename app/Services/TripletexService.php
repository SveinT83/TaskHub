<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TripletexService
{
    // -------------------------------------------------
    // Variables
    // -------------------------------------------------
    private $sessionToken;



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CONSTRUCTOR
    // --------------------------------------------------------------------------------------------------
    // This function is called when the class is instantiated
    // --------------------------------------------------------------------------------------------------
    public function __construct()
    {
        // -------------------------------------------------
        // Generate a session token for the Tripletex API
        // -------------------------------------------------
        $this->sessionToken = $this->generateSessionToken();
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - GENERATE SESSION TOKEN
    // --------------------------------------------------------------------------------------------------
    // This function generates a session token for the Tripletex API
    // --------------------------------------------------------------------------------------------------
    private function generateSessionToken()
    {
        // -------------------------------------------------
        // Get the consumer and employee tokens from the .env file
        // -------------------------------------------------
        $consumerToken = env('TRIPLETEX_CONSUMER_TOKEN');
        $employeeToken = env('TRIPLETEX_EMPLOYEE_TOKEN');
        $expirationDate = now()->addDay()->toDateString(); // Setter utløpsdato til neste dag

        // -------------------------------------------------
        // Send a request to the Tripletex API to generate a session token
        // -------------------------------------------------
        $response = Http::withHeaders([
            'Content-Type' => 'application/json', // Angi at vi sender JSON
        ])->put("https://api.tripletex.io/v2/token/session/:create?consumerToken={$consumerToken}&employeeToken={$employeeToken}&expirationDate={$expirationDate}");

        // -------------------------------------------------
        // Handle the response
        // -------------------------------------------------
        if ($response->successful()) {
            return $response->json('value.token'); // Returner sessionToken hvis vellykket
        }

        // -------------------------------------------------
        // Throw an exception if the request failed
        // -------------------------------------------------
        else {
            throw new \Exception('Failed to authenticate with Tripletex API: ' . $response->body());
        }
    }



    public function searchCustomerByOrgNr($orgNr)
    {
        try {
            // Bygg HTTP-forespørselen med autorisasjonshodet
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode("0:{$this->sessionToken}"),
                'Content-Type' => 'application/json', // Angi at vi sender JSON
            ])->get("https://api.tripletex.io/v2/customer", [
                'orgNumber' => $orgNr, // Send organisasjonsnummer som forespørselparameter
            ]);

            // Sjekk om forespørselen var vellykket
            if ($response->successful()) {
                $customers = $response->json('values');

                // Returner kundedata hvis funnet
                if (!empty($customers)) {
                    return $customers;
                } else {
                    return null; // Ingen kunder funnet
                }
            } else {
                // Logg feilmeldingen fra API
                throw new \Exception('Error from Tripletex API: ' . $response->body());
            }
        } catch (\Exception $e) {
            // Logg og returner feil for videre debugging
            \Log::error('Tripletex API error: ' . $e->getMessage());
            throw new \Exception('Failed to fetch customer data: ' . $e->getMessage());
        }
    }

}
