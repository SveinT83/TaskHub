<?php

namespace App\Livewire\Admin\Configurations\Email;

use Livewire\Component;
use App\Models\EmailAccount;

class EmailForm extends Component
{
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

    public function mount($emailAccount = null)
    {
        if ($emailAccount) {
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

    public function save()
    {
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

        if ($this->isEditMode) {
            $this->emailAccount->update($data);
            session()->flash('success', 'Email account updated!');
        } else {
            EmailAccount::create($data);
            session()->flash('success', 'Email account created!');
        }

        return redirect()->route('email_accounts.index');
    }

    public function render()
    {
        return view('livewire.admin.configurations.email.email-form');
    }
}