<!DOCTYPE html>
<html>
<head>
    <title>Svar på din sak #{{ $ticket->id }}</title>
</head>
    <body>
        <p>Hei {{ $ticket->client->name }},</p>

        <p>{{ $ticketReply->message }}</p>

        <p>Vennlig hilsen,<br/>
        {{ $ticketReply->user->name }}</p>

        <hr>

        <p>For å svare på denne e-posten, vennligst send din melding til denne e-postadressen. Sørg for at saks-ID #{{ $ticket->id }} er inkludert i emnefeltet.</p>
    </body>
</html>
