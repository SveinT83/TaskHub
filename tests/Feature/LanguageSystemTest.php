<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\App;

class LanguageSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_set_locale()
    {
        $user = User::factory()->create(['locale' => 'no']);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'locale' => 'no'
        ]);
    }

    public function test_middleware_sets_user_locale()
    {
        $user = User::factory()->create(['locale' => 'no']);
        
        $response = $this->actingAs($user)->get('/admin');
        
        $this->assertEquals('no', App::getLocale());
    }

    public function test_fallback_to_default_locale_for_unsupported_locale()
    {
        $user = User::factory()->create(['locale' => 'unsupported']);
        
        $response = $this->actingAs($user)->get('/admin');
        
        $this->assertEquals('en', App::getLocale());
    }

    public function test_guest_users_use_session_locale()
    {
        $response = $this->withSession(['locale' => 'no'])->get('/');
        
        $this->assertEquals('no', App::getLocale());
    }

    public function test_translation_helper_functions()
    {
        $locales = \App\Helpers\LanguageHelper::getAvailableLocales();
        $this->assertIsArray($locales);
        $this->assertArrayHasKey('en', $locales);
        $this->assertArrayHasKey('no', $locales);
        
        $this->assertTrue(\App\Helpers\LanguageHelper::isLocaleSupported('en'));
        $this->assertTrue(\App\Helpers\LanguageHelper::isLocaleSupported('no'));
        $this->assertFalse(\App\Helpers\LanguageHelper::isLocaleSupported('unsupported'));
    }
}
