<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailAccount;

class EmailAccountController extends Controller
{
    // Vis en liste over e-postkontoer
    public function index()
    {
        $emailAccounts = EmailAccount::all();
        return view('admin/configurations/email/email_accounts.index', compact('emailAccounts'));
    }

    // Vis skjemaet for å opprette en ny e-postkonto
    public function create()
    {
        return view('admin/configurations/email/email_accounts.create');
    }

    // Lagre en ny e-postkonto i databasen
    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'smtp_host'        => 'required|string',
            'smtp_port'        => 'required|integer',
            'smtp_encryption'  => 'nullable|string',
            'smtp_username'    => 'required|string',
            'smtp_password'    => 'required|string',
            'imap_host'        => 'required|string',
            'imap_port'        => 'required|integer',
            'imap_encryption'  => 'nullable|string',
            'imap_username'    => 'required|string',
            'imap_password'    => 'required|string',
            'is_default'       => 'nullable|boolean',
        ]);

        // Hvis 'is_default' er satt til true, sørg for at kun én konto er standard
        if ($request->input('is_default')) {
            EmailAccount::where('is_default', true)->update(['is_default' => false]);
        }

        EmailAccount::create($request->all());

        return redirect()->route('admin/configurations/email/email_accounts.index')->with('success', 'E-postkonto opprettet.');
    }

    // Vis en spesifikk e-postkonto
    public function show(EmailAccount $emailAccount)
    {
        return view('admin/configurations/email/email_accounts.show', compact('emailAccount'));
    }

    // Vis skjemaet for å redigere en e-postkonto
    public function edit(EmailAccount $emailAccount)
    {
        return view('admin/configurations/email/email_accounts.edit', compact('emailAccount'));
    }

    // Oppdater en e-postkonto i databasen
    public function update(Request $request, EmailAccount $emailAccount)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'smtp_host'        => 'required|string',
            'smtp_port'        => 'required|integer',
            'smtp_encryption'  => 'nullable|string',
            'smtp_username'    => 'required|string',
            'smtp_password'    => 'nullable|string', // Gjør det valgfritt ved oppdatering
            'imap_host'        => 'required|string',
            'imap_port'        => 'required|integer',
            'imap_encryption'  => 'nullable|string',
            'imap_username'    => 'required|string',
            'imap_password'    => 'nullable|string', // Gjør det valgfritt ved oppdatering
            'is_default'       => 'nullable|boolean',
        ]);

        if ($request->input('is_default')) {
            EmailAccount::where('is_default', true)->where('id', '!=', $emailAccount->id)->update(['is_default' => false]);
        }

        $data = $request->all();

        // Hvis passordene er tomme, ikke oppdater dem
        if (empty($data['smtp_password'])) {
            unset($data['smtp_password']);
        }

        if (empty($data['imap_password'])) {
            unset($data['imap_password']);
        }

        $emailAccount->update($data);

        return redirect()->route('email_accounts.index')->with('success', 'E-postkonto oppdatert.');
    }

    // Slett en e-postkonto fra databasen
    public function destroy(EmailAccount $emailAccount)
    {
        $emailAccount->delete();
        return redirect()->route('email_accounts.index')->with('success', 'E-postkonto slettet.');
    }
}
