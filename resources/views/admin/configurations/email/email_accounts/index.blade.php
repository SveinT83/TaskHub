<!-- -------------------------------------------------------------------------------------------------- -->
<!-- CONTROLLER -->
<!-- app/Http/Controllers/Admin/Configurations/EmailAccountController.php -->
<!-- -------------------------------------------------------------------------------------------------- -->

@extends('layouts.app')

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Page Header -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('pageHeader')
    <x-page-header pageHeaderTitle="E-Mail accounts">

        <!-- ------------------------------------------------- -->
        <!-- Add account button if user has permission -->
        <!-- ------------------------------------------------- -->
        @can('superadmin.create')
            <div class="col-md-2 mt-1">
                <a href="{{ route('email_accounts.create') }}" class="btn btn-primary bi bi-plus mb-3"> Add account</a>
            </div>
        @endcan

    </x-page-header>
@endsection

<!-- -------------------------------------------------------------------------------------------------- -->
<!-- Content -->
<!-- -------------------------------------------------------------------------------------------------- -->
@section('content')
<div class="container mt-3">

    <!-- -------------------------------------------------  -->
    <!-- EMAIL ACCOUNT TABLE                                -->
    <!-- Show the email table, only if there are any        -->
    <!-- email accounts                                     -->
    <!-- -------------------------------------------------  -->
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

                <!-- ------------------------------------------------- -->
                <!-- Foreach loop to show all email accounts -->
                <!-- ------------------------------------------------- -->
                @foreach($emailAccounts as $account)
                    <tr>
                        <td scope="row">{{ $account->name }}</td>
                        <td>{{ $account->description }}</td>
                        <td>{{ $account->smtp_host }}</td>
                        <td>{{ $account->imap_host }}</td>
                        <td>{{ $account->is_default ? 'Ja' : 'Nei' }}</td>
                        <td>

                            <!-- ------------------------------------------------- -->
                            <!-- Edit button if user has permission -->
                            <!-- ------------------------------------------------- -->
                            @can('superadmin.edit')
                                <a href="{{ route('email_accounts.edit', $account->id) }}" class="btn btn-sm btn-warning">Rediger</a>
                            @endcan

                            <!-- ------------------------------------------------- -->
                            <!-- Delete Form and button button if user has permission -->
                            <!-- ------------------------------------------------- -->
                            @can('superadmin.delete')
                                <form action="{{ route('email_accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Er du sikker på at du vil slette denne e-postkontoen?')">Slett</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    <!-- ------------------------------------------------- -->
    <!-- If there are no email accounts -->
    <!-- ------------------------------------------------- -->
    @else
        <p>No email accounts</p>
    @endif

</div>
@endsection
