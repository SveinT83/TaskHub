<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class FindCustomerForm extends Component
{

    public $email;
    public $tel;
    public $name;
    public $isLoading = false;

    public $customerId;
    public $contactData = [];
    public $customerInfo = [];
    public $serviceItemData = [];

    public $apiRequest;
    public $apiResponse;

    public function searchCustomer()
    {
        $this->isLoading = true; // Start spinneren
        session()->flash('success', 'Søkeknappen ble klikket!');

        try {
            // Hent token for Basic Auth
            $username = env('MSP_MANAGER_EMAIL');
            $password = env('MSP_MANAGER_PASSWORD');

            // Send GET-forespørsel for alle kontakter
            $response = Http::withBasicAuth($username, $password)
                ->get('https://api.mspmanager.com/odata/contacts');

            if ($response->successful()) {
                $contacts = collect($response->json('value'));

                // Filtrer kontakter basert på input
                $filteredContact = $contacts->first(function ($contact) {
                    return ($this->email && $contact['EmailAddress'] === $this->email)
                        || ($this->tel && $contact['PhoneMobile'] === $this->tel)
                        || ($this->name && str_contains(strtolower($contact['FirstName']), strtolower($this->name)));
                });

                if ($filteredContact) {
                    $this->contactData = [
                        'ContactId' => $filteredContact['ContactId'],
                        'FirstName' => $filteredContact['FirstName'],
                        'EmailAddress' => $filteredContact['EmailAddress'],
                        'PhoneMobile' => $filteredContact['PhoneMobile'],
                        'CustomerId' => $filteredContact['CustomerId'],
                    ];
                } else {
                    session()->flash('error', 'Ingen kunde funnet med oppgitte kriterier.');
                }
            } else {
                session()->flash('error', 'Kunne ikke hente kontakter fra MSP Manager API.');
            }
        } finally {
            $this->isLoading = false; // Stopp spinneren
        }
    }

    public function fetchCustomerInfo()
    {
        if (!$this->customerId) {
            session()->flash('error', 'CustomerId mangler.');
            return;
        }

        $username = env('MSP_MANAGER_EMAIL');
        $password = env('MSP_MANAGER_PASSWORD');

        $url = "https://api.mspmanager.com/odata/customers({$this->customerId})";

        $response = Http::withBasicAuth($username, $password)->get($url);

        if ($response->successful()) {
            $this->customerInfo = $response->json();
        } else {
            session()->flash('error', 'Kunne ikke hente kundeinformasjon.');
        }
    }

    public function fetchServiceItems()
    {
        if (!$this->customerId) {
            session()->flash('error', 'CustomerId mangler.');
            return;
        }

        $username = env('MSP_MANAGER_EMAIL');
        $password = env('MSP_MANAGER_PASSWORD');

        $url = "https://api.mspmanager.com/odata/customers({$this->customerId})/serviceitems";

        $response = Http::withBasicAuth($username, $password)->get($url);

        if ($response->successful()) {
            $serviceItems = collect($response->json('value'));

            // Filtrer service items basert på 'isDefault'
            $defaultServiceItem = $serviceItems->firstWhere('IsDefault', true);

            if ($defaultServiceItem) {
                $this->serviceItemData = [
                    'ServiceItemId' => $defaultServiceItem['ServiceItemId'],
                    'ServiceItemName' => $defaultServiceItem['ServiceItemName'],
                ];
            }
        } else {
            session()->flash('error', 'Kunne ikke hente service items.');
        }
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.FindCustomerForm');
    }
}
