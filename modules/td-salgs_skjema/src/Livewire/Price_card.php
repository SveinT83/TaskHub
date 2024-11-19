<?php

namespace tronderdata\TdSalgsSkjema\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use tronderdata\TdSalgsSkjema\Models\AlacarteItems;
use tronderdata\TdSalgsSkjema\Models\ServicePakke;

class Price_card extends Component
{
    public $totalItems = 0;
    public $totalPrice = 0;
    public $discount = 0;
    public $matchingServiceName = null;

    public function mount()
    {
        $this->calculateTotals();
    }

    public function pb()
    {
        if (session('private')) {
            return "Privat";
        } elseif (session('business')) {
            return "Bedrift";
        } else {
            return "Ikke valgt";
        }
    }

    private function getSessionQuantities()
    {
        return session('quantities', []);
    }

    private function getMatchingService($quantities)
    {

        //Sjekk om serviceavtale finnes i session
        if (session('selectedService')) {
            $servicePakke = ServicePakke::where('id', session('selectedService'))->first();
            return $servicePakke;

        } else {
            $servicePakker = ServicePakke::with('alacarteItems')->get();

            foreach ($servicePakker as $servicePakke) {
                $alacarteIds = $servicePakke->alacarteItems->pluck('id')->toArray();

                // Sjekk om alle alacarte-elementene i servicepakken finnes i sessionQuantities
                $isMatch = true;

                foreach ($alacarteIds as $alacarteId) {
                    if (!isset($quantities[$alacarteId]) || $quantities[$alacarteId] <= 0) {
                        $isMatch = false;
                        break;
                    }
                }

                // Sjekk om det finnes ekstra elementer i sessionQuantities som ikke er en del av servicepakken
                foreach ($quantities as $alacarteId => $quantity) {
                    if (!in_array($alacarteId, $alacarteIds)) {
                        $isMatch = false;
                        break;
                    }
                }

                if ($isMatch) {
                    return $servicePakke;
                }
            }

            return null;
        }
    }

    private function calculateDiscount($price)
    {
        // Hent alle alacarte-elementene som er lagt til i handlekurven
        $quantities = $this->getSessionQuantities();

        // Finn servicepakken som matcher alacarte-elementene i handlekurven
        $matchingService = $this->getMatchingService($quantities);

        if ($matchingService) {
            $this->totalDiscount = 0; // Reset totalDiscount

            // Hent alle alacarte-elementer tilknyttet servicepakken
            $serviceItems = $matchingService->alacarteItems;

            foreach ($serviceItems as $item) {
                // Hent antall brukere fra session
                $quantity = $item->pr === 'user' || $item->pr === 'enhet' 
                    ? session('amountUsers', 1) 
                    : 1;

                // Beregn rabatt for elementet
                $this->totalDiscount += $quantity * $item->price;
            }

            return $this->totalDiscount;
        }

        return 0; // Ingen rabatt hvis ingen matchende servicepakke
    }

    public function calculateTotals()
    {
        // Hent alle alacarte-elementene som er lagt til i handlekurven
        $quantities = $this->getSessionQuantities();

        // Hent alle alacarte-elementene fra databasen
        $items = AlacarteItems::whereIn('id', array_keys($quantities))->get();

        $this->totalItems = 0;
        $this->totalPrice = 0;

        // Gå gjennom alle alacarte-elementene og regn ut totalpris
        foreach ($items as $item) {
            $quantity = $quantities[$item->id] ?? 0;

            if ($quantity > 0) {
                $this->totalItems++;
                $this->totalPrice += $quantity * $item->price;
            }
        }

        $matchingService = $this->getMatchingService($quantities);

        if ($matchingService || session('selectedService')) {
            $this->matchingServiceName = $matchingService->name ?? session('selectedService')['name'];
            $this->discount = $this->calculateDiscount($this->totalPrice);
            $this->totalPrice -= $this->discount;

            // Beregn minimumspris basert på servicepakken
            $minimumPrice = $matchingService->price * session('amountUsers', 1);
            $this->totalPrice = max($this->totalPrice, $minimumPrice); // Sett totalpris til minimumspris hvis nødvendig
        } else {
            $this->matchingServiceName = null;
            $this->discount = 0;
        }
    }

    public function refresh()
    {
        $this->calculateTotals();
    }

    public function render()
    {
        return view('TdSalgsSkjema::livewire.price_card', [
            'totalItems' => $this->totalItems,
            'totalPrice' => number_format($this->totalPrice, 2),
            'discount' => number_format($this->discount, 2),
            'pb' => $this->pb(),
            'service' => $this->matchingServiceName
        ]);
    }
}
