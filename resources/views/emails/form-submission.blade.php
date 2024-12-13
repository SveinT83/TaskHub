<h1>Ny skjema innsending</h1>

<!-- ------------------------------------------------- -->
<!-- Kontaktperson detaljer -->
<!-- ------------------------------------------------- -->
<p><strong>Kontaktperson:</strong> {{ $data['kontaktNavn'] }}</p>
<p><strong>E-post:</strong> {{ $data['kontaktEpost'] }}</p>
<p><strong>Telefon:</strong> {{ $data['kontaktTlf'] }}</p>

<!-- ------------------------------------------------- -->
<!-- Adresse detaljer -->
<!-- ------------------------------------------------- -->
<h2>Adresse:</h2>
<p>{{ $data['adrAdresse'] }}, {{ $data['adrPostnr'] }} {{ $data['adrSted'] }}</p>


<!-- ------------------------------------------------- -->
<!-- Fakturaadresse detaljer -->
<!-- ------------------------------------------------- -->
<h2>Fakturaadresse:</h2>
<p>{{ $data['faktAdresse'] }}, {{ $data['faktPostnr'] }} {{ $data['faktSted'] }}</p>


<!-- ------------------------------------------------- -->
<!-- Bedrift detaljer -->
<!-- ------------------------------------------------- -->
@if (!empty($data['orgNr']))
    <h2>Organisasjonsdetaljer:</h2>
    <p><strong>Organisasjonsnummer:</strong> {{ $data['orgNr'] }}</p>
@endif

@if (!empty($data['orgName']))
    <p><strong>Organisasjonsnavn:</strong> {{ $data['orgName'] }}</p>
@endif

@if (!empty($data['timebank']))
    <p><strong>Timebank:</strong> {{ $data['timebank'] }}</p>
@endif

@if (!empty($data['amountUsers']))
    <p><strong>Antall brukere:</strong> {{ $data['amountUsers'] }}</p>
@endif

@if (!empty($data['amountDatamaskiner']))
    <p><strong>Antall datamaskiner:</strong> {{ $data['amountDatamaskiner'] }}</p>
@endif

@if (!empty($data['selectedService']))
    <p><strong>Valgt serviceavtale:</strong> {{ $data['selectedService'] }}</p>
@endif