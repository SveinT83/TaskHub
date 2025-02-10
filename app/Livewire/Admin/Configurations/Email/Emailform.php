<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// COMPOMENT - EMAIL FORM
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This component is responsible for handling the email account form. It allows the user to create or update an email account.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Livewire\Admin\Configurations\Email;

use Livewire\Component;
use App\Models\EmailAccount;

class Emailform extends Component
{

    // -------------------------------------------------
    // PROPERTIES
    // -------------------------------------------------
    public $emailAccount;
    public $isEditMode = false;
    public $name;
    public $description;
    public $smtp_host;
    public $smtp_port;
    public $smtp_encryption;
    public $smtp_username;
    public $smtp_password;
    public $imap_host;
    public $imap_port;
    public $imap_encryption;
    public $imap_username;
    public $imap_password;
    public $is_default;


    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MOUNT
    // --------------------------------------------------------------------------------------------------
    // This function is called when the component is initialized.
    // --------------------------------------------------------------------------------------------------
    public function mount($emailAccount = null)
    {

        // -------------------------------------------------
        // If an email account is provided,
        // set the edit mode and populate the fields with the existing data.
        // -------------------------------------------------
        if ($emailAccount) {

            // -------------------------------------------------
            // Populate fields with existing data
            // -------------------------------------------------

            // Set edit mode
            $this->isEditMode = true;
            $this->emailAccount = $emailAccount;

            // Populate fields with existing data
            $this->name = $emailAccount->name;
            $this->description = $emailAccount->description;
            $this->smtp_host = $emailAccount->smtp_host;
            $this->smtp_port = $emailAccount->smtp_port;
            $this->smtp_encryption = $emailAccount->smtp_encryption;
            $this->smtp_username = $emailAccount->smtp_username;
            $this->imap_host = $emailAccount->imap_host;
            $this->imap_port = $emailAccount->imap_port;
            $this->imap_encryption = $emailAccount->imap_encryption;
            $this->imap_username = $emailAccount->imap_username;
            $this->is_default = $emailAccount->is_default;
        }
    }


    // --------------------------------------------------------------------------------------------------
    // FUNCTION - SAVE
    // --------------------------------------------------------------------------------------------------
    // This function is called when the user submits the form. It validates the form data and saves the email account.
    // --------------------------------------------------------------------------------------------------
    public function save()
    {

        // -------------------------------------------------
        // Validate the form data
        // -------------------------------------------------
        $data = $this->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_encryption' => 'required|string',
            'smtp_username' => 'required|string',
            'smtp_password' => 'nullable|string',
            'imap_host' => 'required|string',
            'imap_port' => 'required|integer',
            'imap_encryption' => 'required|string',
            'imap_username' => 'required|string',
            'imap_password' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        // -------------------------------------------------
        // If the email account is in edit mode, update the existing email account.
        // -------------------------------------------------
        if ($this->isEditMode) {

            // -------------------------------------------------
            // Update the email account
            // -------------------------------------------------
            $this->emailAccount->update($data);

            // -------------------------------------------------
            // Show success message
            // -------------------------------------------------
            session()->flash('success', 'Email account updated!');

        // -------------------------------------------------
        // Else, create a new email account.
        // -------------------------------------------------
        } else {

            // -------------------------------------------------
            // Create the email account
            // -------------------------------------------------
            EmailAccount::create($data);

            // -------------------------------------------------
            // Show success message
            // -------------------------------------------------
            session()->flash('success', 'Email account created!');
        }

        // -------------------------------------------------
        // Redirect the user to the email accounts page.
        // -------------------------------------------------
        return redirect()->route('email_accounts.index');
    }


    // --------------------------------------------------------------------------------------------------
    // FUNCTION - RENDER
    // --------------------------------------------------------------------------------------------------
    // This function renders the component.
    // --------------------------------------------------------------------------------------------------
    public function render()
    {

        // -------------------------------------------------
        // Return the view
        // -------------------------------------------------
        return view('livewire.admin.configurations.email.emailform');
    }
}