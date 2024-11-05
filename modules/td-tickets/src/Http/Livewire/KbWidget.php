<?php

// src/Http/Livewire/KbWidget.php
namespace tronderdata\TdTickets\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class KbWidget extends Component
{
    public $articles = [];
    public $ticket;

    public function mount($ticket)
    {
        $this->ticket = $ticket;
    }

    // Sjekk om "articles" tabellen eksisterer
    protected function articlesTableExists()
    {
        return DB::getSchemaBuilder()->hasTable('articles');
    }

    // Del opp beskrivelsen i setningsbaserte søkefraser
    protected function splitDescriptionIntoPhrases($description)
    {
        // Del opp ved punktum, komma eller annen tegnsetting og trim resultatet
        $phrases = preg_split('/[.,]/', $description);
        return array_map('trim', $phrases);
    }

    // Generer ordkombinasjoner for søk
    protected function generateWordCombinations($description)
    {
        $words = preg_split('/\s+/', $description); // Split ved mellomrom
        $combinations = [];

        // Lag kombinasjoner av hvert tredje ord
        for ($i = 0; $i < count($words) - 2; $i++) {
            $combinations[] = implode(' ', array_slice($words, $i, 3));
        }

        return $combinations;
    }

    // Søk etter artikler som matcher søkefrasene
    public function searchArticles()
    {
        if (!$this->articlesTableExists()) {
            return;
        }

        // Generer søkefraser fra beskrivelsen
        $sentencePhrases = $this->splitDescriptionIntoPhrases($this->ticket->description);
        $wordCombinations = $this->generateWordCombinations($this->ticket->description);

        // Slå sammen setningsfrasene og ordkombinasjonene
        $allPhrases = array_merge($sentencePhrases, $wordCombinations);

        // Utfør søket og beregn poeng for hver artikkel
        $articles = DB::table('articles')
            ->where('status', 'published')
            ->get();

        $this->articles = $articles->filter(function ($article) use ($allPhrases) {
            $score = 0;

            // Beregn poeng basert på antall treff i artikkelens innhold
            foreach ($allPhrases as $phrase) {
                if (stripos($article->content, $phrase) !== false) {
                    $score++;
                }
            }

            // Sett en terskel for antall nødvendige treff
            return $score >= 3; // Juster antall nødvendige treff her
        });
    }

    public function render()
    {
        return view('tdtickets::livewire.widget.kb-widget')
            ->layout('layouts.app');
    }
}
