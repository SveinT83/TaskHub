<!DOCTYPE html>
<html>
<head>
    <title>Ordre</title>
</head>
<body>
    <h1>Ny ordre</h1>
    <p><strong>Tjeneste:</strong> {{ $orderData['serviceName'] }}</p>
    <p><strong>Antall brukere:</strong> {{ $orderData['amountUsers'] }}</p>
    <p><strong>Sum brukere:</strong> {{ $orderData['sumTottUsers'] }},-</p>
    <p><strong>Antall datamaskiner:</strong> {{ $orderData['amountDatamaskiner'] }}</p>
    <p><strong>Sum datamaskiner:</strong> {{ $orderData['sumTottComputers'] }},-</p>
    <p><strong>Timebank:</strong> {{ $orderData['timebank'] }}</p>
    <p><strong>Timebankpris:</strong> {{ $orderData['timebankPrice'] }},-</p>
    <p><strong>Totalpris:</strong> {{ $orderData['sumTott'] }},-</p>
    <h3>Estimert ressursbruk:</h3>
    <p><strong>vCPU:</strong> {{ $orderData['cpu'] }}</p>
    <p><strong>RAM:</strong> {{ $orderData['ram'] }} GB</p>
    <p><strong>Lagring:</strong> {{ $orderData['storage'] }} GB</p>
</body>
</html>
