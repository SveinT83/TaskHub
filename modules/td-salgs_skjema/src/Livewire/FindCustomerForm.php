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
    public $contactForm = false;

    public $apiRequest;
    public $apiResponse;

    public function searchCustomer()
    {

        $this->contactForm = true;

        session([
            'email' => $this->email,
            'tel' => $this->tel,
            'name' => $this->name,
        ]);

        try {
            // Hent token for Basic Auth
            $username = 'sveintore@tronderdata.no';
            $password = 'JEstayeq9J';

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

                    session([
                        'contactData' => $this->contactData,
                        'customerId' => $this->customerId,
                    ]);

                    $this->customerId = $filteredContact['CustomerId'];

                    // Hent kundeinformasjon og service levels
                    $this->fetchCustomerInfo();
                    $this->fetchServiceItems();

                } else {
                    session()->flash('error', 'Ingen kunde funnet med oppgitte kriterier.');
                }
            } else {
                session()->flash('error', 'Kunne ikke hente kontakter fra MSP Manager API.');
            }
        } finally {

            if ($this->customerId) {
                session()->flash('success', 'Kunde funnet.');
            }
            $this->isLoading = false; // Stopp spinneren
        }
    }

    public function fetchCustomerInfo()
    {
        if (!$this->customerId) {
            session()->flash('error', 'CustomerId mangler.');
            return;
        }

        $username = 'sveintore@tronderdata.no';
            $password = 'JEstayeq9J';

        $url = "https://api.mspmanager.com/odata/customers({$this->customerId})";

        $response = Http::withBasicAuth($username, $password)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            $fullName = $data['CustomerName'] ?? null;

            if ($fullName) {
                // Ekstraher kundenummer og navn
                preg_match('/^(\d+)\s*-\s*(.+)$/', $fullName, $matches);

                $this->customerInfo = [
                    'fullName' => $fullName,
                    'customerNumber' => $matches[1] ?? null, // Kundenummer
                    'customerName' => $matches[2] ?? $fullName, // Navn uten kundenummer og "-"
                ];

                session([
                    'customerInfo' => $this->customerInfo,
                ]);
            } else {
                session()->flash('error', 'Kundenavn ikke tilgjengelig.');
            }
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

        $username = 'sveintore@tronderdata.no';
        $password = 'JEstayeq9J';

        $url = "https://api.mspmanager.com/odata/customers({$this->customerId})/serviceitems";

        $response = Http::withBasicAuth($username, $password)->get($url);

        if ($response->successful()) {
            $serviceItems = collect($response->json('value'));

            // Filtrer for 'IsDefault: true' først
            $defaultServiceItem = $serviceItems->firstWhere('IsDefault', true);

            // Hvis ingen default, ta første aktive service item
            if (!$defaultServiceItem) {
                $defaultServiceItem = $serviceItems->firstWhere('IsActive', true);
            }

            // Hvis vi finner et gyldig service item
            if ($defaultServiceItem) {
                $this->serviceItemData = [
                    'ServiceItemId' => $defaultServiceItem['ServiceItemId'],
                    'ServiceItemName' => $defaultServiceItem['ServiceItemName'],
                    'StartDate' => $defaultServiceItem['StartDate'],
                    'EndDate' => $defaultServiceItem['EndDate'],
                    'InvoiceDescription' => $defaultServiceItem['InvoiceDescription'] ?? 'Ingen beskrivelse',
                ];

                session([
                    'serviceItemData' => $this->serviceItemData,
                ]);

            } else {
                // Ingen service items funnet
                session()->flash('error', 'Ingen relevante service items funnet.');
            }
        } else {
            session()->flash('error', 'Kunne ikke hente service items fra MSP Manager API.');
        }
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.FindCustomerForm');
    }
}
