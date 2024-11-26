@extends('layouts.app')

@section('pageHeader')
    <div class="row align-items-center justify-content-between">
        <div class="col-md-2 mt-1">
            <h1>E-Mail accounts</h1>
        </div>
        <div class="col-md-2 mt-1">
            <a href="{{ route('email_accounts.create') }}" class="btn btn-primary bi bi-plus mb-3"> Add account</a>
        </div>
    </div>
@endsection

@section('content')
<div class="container mt-3">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($emailAccounts->count())
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Navn</th>
                    <th scope="col">Beskrivelse</th>
                    <th scope="col">SMTP Host</th>
                    <th scope="col">IMAP Host</th>
                    <th scope="col">Standard</th>
                    <th scope="col">Handlinger</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emailAccounts as $account)
                    <tr>
                        <td scope="row">{{ $account->name }}</td>
                        <td>{{ $account->description }}</td>
                        <td>{{ $account->smtp_host }}</td>
                        <td>{{ $account->imap_host }}</td>
                        <td>{{ $account->is_default ? 'Ja' : 'Nei' }}</td>
                        <td>
                            <a href="{{ route('email_accounts.edit', $account->id) }}" class="btn btn-sm btn-warning">Rediger</a>
                            <form action="{{ route('email_accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Er du sikker på at du vil slette denne e-postkontoen?')">Slett</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Ingen e-postkontoer funnet.</p>
    @endif
</div>
@endsection
