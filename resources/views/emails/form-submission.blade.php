<h1>Ny skjema innsending</h1>

<p><strong>Kontaktperson:</strong> {{ $data['kontaktNavn'] }}</p>
<p><strong>E-post:</strong> {{ $data['kontaktEpost'] }}</p>
<p><strong>Telefon:</strong> {{ $data['kontaktTlf'] }}</p>

<h2>Adresse:</h2>
<p>{{ $data['adrAdresse'] }}, {{ $data['adrPostnr'] }} {{ $data['adrSted'] }}</p>

<h2>Fakturaadresse:</h2>
<p>{{ $data['faktAdresse'] }}, {{ $data['faktPostnr'] }} {{ $data['faktSted'] }}</p>

<h2>Organisasjonsdetaljer:</h2>
@if (!empty($data['orgNr']))
    <p><strong>Organisasjonsnummer:</strong> {{ $data['orgNr'] }}</p>
@endif
@if (!empty($data['orgName']))
    <p><strong>Organisasjonsnavn:</strong> {{ $data['orgName'] }}</p>
@endif
