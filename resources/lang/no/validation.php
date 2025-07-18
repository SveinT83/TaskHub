<?php

return [
    'accepted' => ':attribute må aksepteres.',
    'accepted_if' => ':attribute må aksepteres når :other er :value.',
    'active_url' => ':attribute er ikke en gyldig URL.',
    'after' => ':attribute må være en dato etter :date.',
    'after_or_equal' => ':attribute må være en dato etter eller lik :date.',
    'alpha' => ':attribute kan kun inneholde bokstaver.',
    'alpha_dash' => ':attribute kan kun inneholde bokstaver, tall, bindestreker og understreker.',
    'alpha_num' => ':attribute kan kun inneholde bokstaver og tall.',
    'array' => ':attribute må være en matrise.',
    'before' => ':attribute må være en dato før :date.',
    'before_or_equal' => ':attribute må være en dato før eller lik :date.',
    'between' => [
        'array' => ':attribute må ha mellom :min og :max elementer.',
        'file' => ':attribute må være mellom :min og :max kilobytes.',
        'numeric' => ':attribute må være mellom :min og :max.',
        'string' => ':attribute må være mellom :min og :max tegn.',
    ],
    'boolean' => ':attribute feltet må være sant eller usant.',
    'confirmed' => ':attribute bekreftelsen stemmer ikke overens.',
    'current_password' => 'Passordet er feil.',
    'date' => ':attribute er ikke en gyldig dato.',
    'date_equals' => ':attribute må være en dato lik :date.',
    'date_format' => ':attribute stemmer ikke overens med formatet :format.',
    'declined' => ':attribute må avslås.',
    'declined_if' => ':attribute må avslås når :other er :value.',
    'different' => ':attribute og :other må være forskjellige.',
    'digits' => ':attribute må være :digits siffer.',
    'digits_between' => ':attribute må være mellom :min og :max siffer.',
    'dimensions' => ':attribute har ugyldige bildedimensjoner.',
    'distinct' => ':attribute feltet har en duplikatverdi.',
    'email' => ':attribute må være en gyldig e-postadresse.',
    'ends_with' => ':attribute må slutte med en av følgende: :values.',
    'enum' => 'Den valgte :attribute er ugyldig.',
    'exists' => 'Den valgte :attribute er ugyldig.',
    'file' => ':attribute må være en fil.',
    'filled' => ':attribute feltet må ha en verdi.',
    'gt' => [
        'array' => ':attribute må ha mer enn :value elementer.',
        'file' => ':attribute må være større enn :value kilobytes.',
        'numeric' => ':attribute må være større enn :value.',
        'string' => ':attribute må være mer enn :value tegn.',
    ],
    'gte' => [
        'array' => ':attribute må ha :value elementer eller mer.',
        'file' => ':attribute må være større enn eller lik :value kilobytes.',
        'numeric' => ':attribute må være større enn eller lik :value.',
        'string' => ':attribute må være :value tegn eller mer.',
    ],
    'image' => ':attribute må være et bilde.',
    'in' => 'Den valgte :attribute er ugyldig.',
    'in_array' => ':attribute feltet eksisterer ikke i :other.',
    'integer' => ':attribute må være et heltall.',
    'ip' => ':attribute må være en gyldig IP-adresse.',
    'ipv4' => ':attribute må være en gyldig IPv4-adresse.',
    'ipv6' => ':attribute må være en gyldig IPv6-adresse.',
    'json' => ':attribute må være en gyldig JSON-streng.',
    'lt' => [
        'array' => ':attribute må ha mindre enn :value elementer.',
        'file' => ':attribute må være mindre enn :value kilobytes.',
        'numeric' => ':attribute må være mindre enn :value.',
        'string' => ':attribute må være mindre enn :value tegn.',
    ],
    'lte' => [
        'array' => ':attribute må ikke ha mer enn :value elementer.',
        'file' => ':attribute må være mindre enn eller lik :value kilobytes.',
        'numeric' => ':attribute må være mindre enn eller lik :value.',
        'string' => ':attribute må være :value tegn eller mindre.',
    ],
    'mac_address' => ':attribute må være en gyldig MAC-adresse.',
    'max' => [
        'array' => ':attribute må ikke ha mer enn :max elementer.',
        'file' => ':attribute må ikke være større enn :max kilobytes.',
        'numeric' => ':attribute må ikke være større enn :max.',
        'string' => ':attribute må ikke være mer enn :max tegn.',
    ],
    'mimes' => ':attribute må være en fil av type: :values.',
    'mimetypes' => ':attribute må være en fil av type: :values.',
    'min' => [
        'array' => ':attribute må ha minst :min elementer.',
        'file' => ':attribute må være minst :min kilobytes.',
        'numeric' => ':attribute må være minst :min.',
        'string' => ':attribute må være minst :min tegn.',
    ],
    'multiple_of' => ':attribute må være et multiplum av :value.',
    'not_in' => 'Den valgte :attribute er ugyldig.',
    'not_regex' => ':attribute formatet er ugyldig.',
    'numeric' => ':attribute må være et tall.',
    'password' => 'Passordet er feil.',
    'present' => ':attribute feltet må være til stede.',
    'prohibited' => ':attribute feltet er forbudt.',
    'prohibited_if' => ':attribute feltet er forbudt når :other er :value.',
    'prohibited_unless' => ':attribute feltet er forbudt med mindre :other er i :values.',
    'prohibits' => ':attribute feltet forbyr :other fra å være til stede.',
    'regex' => ':attribute formatet er ugyldig.',
    'required' => ':attribute feltet er påkrevd.',
    'required_array_keys' => ':attribute feltet må inneholde oppføringer for: :values.',
    'required_if' => ':attribute feltet er påkrevd når :other er :value.',
    'required_unless' => ':attribute feltet er påkrevd med mindre :other er i :values.',
    'required_with' => ':attribute feltet er påkrevd når :values er til stede.',
    'required_with_all' => ':attribute feltet er påkrevd når :values er til stede.',
    'required_without' => ':attribute feltet er påkrevd når :values ikke er til stede.',
    'required_without_all' => ':attribute feltet er påkrevd når ingen av :values er til stede.',
    'same' => ':attribute og :other må være like.',
    'size' => [
        'array' => ':attribute må inneholde :size elementer.',
        'file' => ':attribute må være :size kilobytes.',
        'numeric' => ':attribute må være :size.',
        'string' => ':attribute må være :size tegn.',
    ],
    'starts_with' => ':attribute må starte med en av følgende: :values.',
    'string' => ':attribute må være en streng.',
    'timezone' => ':attribute må være en gyldig tidssone.',
    'unique' => ':attribute er allerede tatt.',
    'uploaded' => ':attribute klarte ikke å laste opp.',
    'url' => ':attribute må være en gyldig URL.',
    'uuid' => ':attribute må være en gyldig UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'tilpasset melding',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'navn',
        'username' => 'brukernavn',
        'email' => 'e-postadresse',
        'first_name' => 'fornavn',
        'last_name' => 'etternavn',
        'password' => 'passord',
        'password_confirmation' => 'passordbekreftelse',
        'city' => 'by',
        'country' => 'land',
        'address' => 'adresse',
        'phone' => 'telefon',
        'mobile' => 'mobil',
        'age' => 'alder',
        'sex' => 'kjønn',
        'gender' => 'kjønn',
        'day' => 'dag',
        'month' => 'måned',
        'year' => 'år',
        'hour' => 'time',
        'minute' => 'minutt',
        'second' => 'sekund',
        'title' => 'tittel',
        'content' => 'innhold',
        'description' => 'beskrivelse',
        'excerpt' => 'utdrag',
        'date' => 'dato',
        'time' => 'tid',
        'available' => 'tilgjengelig',
        'size' => 'størrelse',
        'locale' => 'språk',
    ],
];
