<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class FindCustomerForm extends Component
{
    public $email;
    public $tel;
    public $name;

    public $customerId;
    public $contactData;
    public $serviceItemData;

    private function getApiToken()
    {
        // API URL for autentisering
        $authUrl = 'https://api.mspmanager.com/authenticate';

        // E-post og passord fra miljøvariabler
        $email = 'sveintore@tronderdata.no';
        $password = 'JEstayeq9J';

        // Send POST-forespørsel for autentisering
        $response = Http::post($authUrl, [
            'username' => $email,
            'password' => $password,
        ]);

        if ($response->successful()) {
            // Returner token
            return $response->json('token'); // Avhengig av API-strukturen
        }

        // Håndter feil ved autentisering
        throw new \Exception('Autentisering feilet: ' . $response->body());
    }

    public function searchCustomer()
    {
        $token = $this->getApiToken(); // Hent token

        // Bygg query-params for søket
        $query = [];

        if ($this->email) {
            $query['EmailAddress'] = $this->email;
        }
        if ($this->tel) {
            $query['PhoneMobile'] = $this->tel;
        }
        if ($this->name) {
            $query['FirstName'] = $this->name;
        }

        // Send GET-forespørsel til MSP Manager API med token
        $response = Http::withToken($token)
            ->get('https://api.mspmanager.com/odata/contacts', $query);

        if ($response->successful()) {
            // Hent og filtrer data
            $this->contactData = collect($response->json('value'))->map(function ($contact) {
                return [
                    'ContactId' => $contact['ContactId'],
                    'FirstName' => $contact['FirstName'],
                    'EmailAddress' => $contact['EmailAddress'],
                    'PhoneMobile' => $contact['PhoneMobile'],
                    'CustomerId' => $contact['CustomerId'],
                ];
            })->first();
        } else {
            session()->flash('error', 'Kunde ikke funnet.');
        }
    }

    public function fetchServiceItem()
    {
        if (!$this->customerId) {
            session()->flash('error', 'Ingen kunde valgt.');
            return;
        }

        $response = Http::get("https://api.mspmanager.com/odata/customers/{$this->customerId}/serviceitems");

        if ($response->successful()) {
            $this->serviceItemData = collect($response->json('value'))->firstWhere('IsDefault', true);
        } else {
            session()->flash('error', 'Ingen aktive kontrakter funnet.');
        }
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.FindCustomerForm')
            ->layout('layouts.app');
    }
}
