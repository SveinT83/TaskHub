<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - EMAILACCOUNTCONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling profile related actions such as updating the user's profile information and deleting the user's account.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailAccount;

class EmailAccountController extends Controller
{
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with all email accounts.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Retrieve all email accounts from the database.
        // -------------------------------------------------
        $emailAccounts = EmailAccount::all();

        // -------------------------------------------------
        // Return the view with all email accounts.
        // -------------------------------------------------
        return view('admin/configurations/email/email_accounts.index', compact('emailAccounts'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CREATE
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with the form to create a new email account.
    // --------------------------------------------------------------------------------------------------
    public function create()
    {
        // -------------------------------------------------
        // Return the view with the form to create a new email account.
        // -------------------------------------------------
        return view('admin/configurations/email/email_accounts.create');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - STORE
    // --------------------------------------------------------------------------------------------------
    // This function stores a new email account in the database.
    // --------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
        // -------------------------------------------------
        // Validate the form data.
        // -------------------------------------------------
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

        // -------------------------------------------------
        // If 'is_default' is set to true, make sure only one account is default
        // -------------------------------------------------
        if ($request->input('is_default')) {
            EmailAccount::where('is_default', true)->update(['is_default' => false]);
        }

        // -------------------------------------------------
        // Create a new email account in the database.
        // -------------------------------------------------
        EmailAccount::create($request->all());

        // -------------------------------------------------
        // Redirect the user back to the email accounts index page with a success message.
        // -------------------------------------------------
        return redirect()->route('admin/configurations/email/email_accounts.index')->with('success', 'E-postkonto opprettet.');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SHOW
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with a single email account.
    // --------------------------------------------------------------------------------------------------
    public function show(EmailAccount $emailAccount)
    {
        // -------------------------------------------------
        // Return the view with the email account.
        // -------------------------------------------------
        return view('admin/configurations/email/email_accounts.show', compact('emailAccount'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - EDIT
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with the form to edit an email account.
    // --------------------------------------------------------------------------------------------------
    public function edit(EmailAccount $emailAccount)
    {
        // -------------------------------------------------
        // Return the view with the form to edit an email account.
        // -------------------------------------------------
        return view('admin/configurations/email/email_accounts.edit', compact('emailAccount'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE
    // --------------------------------------------------------------------------------------------------
    // This function updates an email account in the database.
    // --------------------------------------------------------------------------------------------------
    public function update(Request $request, EmailAccount $emailAccount)
    {
        // -------------------------------------------------
        // Validate the form data.
        // -------------------------------------------------
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

        // -------------------------------------------------
        // If 'is_default' is set to true, make sure only one account is default
        // -------------------------------------------------
        if ($request->input('is_default')) {
            EmailAccount::where('is_default', true)->where('id', '!=', $emailAccount->id)->update(['is_default' => false]);
        }

        // -------------------------------------------------
        // Retrieve the form data.
        // -------------------------------------------------
        $data = $request->all();

        // -------------------------------------------------
        // If the password fields are empty, remove them from the data array
        // -------------------------------------------------
        if (empty($data['smtp_password'])) {
            unset($data['smtp_password']);
        }

        if (empty($data['imap_password'])) {
            unset($data['imap_password']);
        }

        // -------------------------------------------------
        // Update the email account in the database.
        // -------------------------------------------------
        $emailAccount->update($data);

        // -------------------------------------------------
        // Redirect the user back to the email accounts index page with a success message.
        // -------------------------------------------------
        return redirect()->route('email_accounts.index')->with('success', 'E-postkonto oppdatert.');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DESTROY
    // --------------------------------------------------------------------------------------------------
    // This function deletes an email account from the database.
    // --------------------------------------------------------------------------------------------------
    public function destroy(EmailAccount $emailAccount)
    {
        // -------------------------------------------------
        // Delete the email account from the database.
        // -------------------------------------------------
        $emailAccount->delete();

        // -------------------------------------------------
        // Redirect the user back to the email accounts index page with a success message.
        // -------------------------------------------------
        return redirect()->route('email_accounts.index')->with('success', 'E-postkonto slettet.');
    }
}
