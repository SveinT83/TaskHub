<!-- ------------------------------------------------- -->
<!-- Header -->
<!-- ------------------------------------------------- -->
<h1>Ny skjema innsending</h1>

@if (!empty($data['serviceName']))
    <h2><strong>Serviceavtale:</strong> {{ $data['serviceName'] }}</h2>
@endif

<!-- ------------------------------------------------- -->
<!-- Kontaktperson detaljer -->
<!-- ------------------------------------------------- -->
<br /><br />
<h2>Kontaktperson:</h2>
<table width="600" style="border:1px solid #333">
    <tr>
        <td><b>Navn:</b></td>
        <td><p>{{ $data['kontaktNavn'] }}</p></td>
    </tr>
    <tr>
        <td><b>E-post:</b></td>
        <td><p>{{ $data['kontaktEpost'] }}</p></td>
    </tr>
    <tr>
        <td><b>Telefon:</b></td>
        <td><p>{{ $data['kontaktTlf'] }}</p></td>
    </tr>
</table>

<!-- ------------------------------------------------- -->
<!-- Adresse detaljer -->
<!-- ------------------------------------------------- -->
<br /><br />
<h2>Adresse:</h2>
<table width="600" style="border:1px solid #333">
    <tr>
        <td><b>Adresse:</b></td>
        <td><p>{{ $data['adrAdresse'] }}</p></td>
    </tr>
    <tr>
        <td><b>Postnummer:</b></td>
        <td><p>{{ $data['adrPostnr'] }}</p></td>
    </tr>
    <tr>
        <td><b>Sted:</b></td>
        <td><p>{{ $data['adrSted'] }}</p></td>
    </tr>
</table>

<!-- ------------------------------------------------- -->
<!-- Fakturaadresse detaljer -->
<!-- ------------------------------------------------- -->
<br /><br />
<h2>Fakturaadresse:</h2>
<table width="600" style="border:1px solid #333">
    <tr>
        <td><b>Adresse:</b></td>
        <td><p>{{ $data['faktAdresse'] }}</p></td>
    </tr>
    <tr>
        <td><b>Postnummer:</b></td>
        <td><p>{{ $data['faktPostnr'] }}</p></td>
    </tr>
    <tr>
        <td><b>Sted:</b></td>
        <td><p>{{ $data['faktSted'] }}</p></td>
    </tr>
</table>


<!-- ------------------------------------------------- -->
<!-- Bedrift detaljer -->
<!-- ------------------------------------------------- -->
@if (!empty($data['orgNr']) & !empty($data['orgName']))
    <br /><br />
    <h2>Organisasjonsdetaljer:</h2>

    <table width="600" style="border:1px solid #333">
        <tr>
            <td><b>Organisasjonsnummer:</b></td>
            <td><p>{{ $data['orgNr'] }}</p></td>
        </tr>
        <tr>
            <td><b>Organisasjonsnavn:</b></td>
            <td><p>{{ $data['orgName'] }}</p></td>
        </tr>
    </table>
@endif

<!-- ------------------------------------------------- -->
<!-- Ordre details -->
<!-- ------------------------------------------------- -->
<br /><br />
<h2>Ordre detaljer:</h2>
<table width="600" style="border:1px solid #333">
    @if (!empty($data['timebank']))
        <tr>
            <td><b>Timebank:</b></td>
            <td><p>{{ $data['timebank'] }}</p> <i>- {{ $data['timebankPrice'] }},-</i></td>
        </tr>
    @endif

    @if (!empty($data['antallBrukere']))
        <tr>
            <td><b>Antall brukere:</b></td>
            <td><p>{{ $data['antallBrukere'] }}</p> <i>- {{ $data['antallBrukerePrice'] }},-</i></td>
        </tr>
    @endif

    @if (!empty($data['antallDatamaskiner']))
        <tr>
            <td><b>Antall datamaskiner:</b></td>
            <td><p>{{ $data['antallDatamaskiner'] }}</p> <i>- {{ $data['antallDatamaskinerPrice'] }},-</i></td>
        </tr>
    @endif
</table>

<!-- ------------------------------------------------- -->
<!-- VM details -->
<!-- ------------------------------------------------- -->
@if (!empty($data['cpu']) & !empty($data['ram']) & !empty($data['storage']))
    <br /><br />
    <h2>VM detaljer:</h2>

    <table width="600" style="border:1px solid #333">
        <tr>
            <td><b>CPU:</b></td>
            <td><p>{{ $data['cpu'] }} v core</p></td>
        </tr>
        <tr>
            <td><b>RAM:</b></td>
            <td><p>{{ $data['ram'] }} GB</p></td>
        </tr>
        <tr>
            <td><b>Lagringsplass:</b></td>
            <td><p>{{ $data['storage'] }} GB</p></td>
        </tr>
    </table>
@endif


<!-- ------------------------------------------------- -->
<!-- Pris -->
<!-- ------------------------------------------------- -->
@if (!empty($data['sumTott']))
    <br /><br />
    <table width="600" style="border:1px solid #333">
        <tr>
            <td><b>Tottal:</b></td>
            <td><p>{{ $data['sumTott'] }},- eks. MVA</p></td>
        </tr>
    </table>
@endif