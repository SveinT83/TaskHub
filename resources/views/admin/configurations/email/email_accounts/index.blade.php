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
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">SMTP Host</th>
                    <th scope="col">IMAP Host</th>
                    <th scope="col">Standard</th>
                    
                    <!-- Only show the TH if user has permission -->
                    @if(auth()->user()->can('superadmin.edit') || auth()->user()->can('superadmin.delete'))
                        <th scope="col">Action</th>
                    @endif
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

                        <!-- Only show the TD if user has permission -->
                        @if(auth()->user()->can('superadmin.edit') || auth()->user()->can('superadmin.delete'))
                            <td>

                                <!-- ------------------------------------------------- -->
                                <!-- Edit button if user has permission -->
                                <!-- ------------------------------------------------- -->
                                @can('superadmin.edit')
                                    <!-- view/compoments/new-url.blade.php -->
                                    <x-edit-url href="{{ route('email_accounts.edit', $account->id) }}"></x-edit-url>
                                @endcan

                                <!-- ------------------------------------------------- -->
                                <!-- Delete Form and button button if user has permission -->
                                <!-- ------------------------------------------------- -->
                                @can('superadmin.delete')
                                    <!-- view/compoments/delete-form.blade.php -->
                                    <x-delete-form route="{{ route('email_accounts.destroy', $account->id) }}" warning="Are you sure you want to delete this email account?"></x-delete-form>
                                @endcan
                            </td>
                        @endif

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
