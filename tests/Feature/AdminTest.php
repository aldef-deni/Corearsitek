<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);
    }

    public function test_homepage_displays_seeded_content(): void
    {
        SiteContent::create(['key' => 'hero_title', 'label' => 'Judul Hero', 'type' => 'text', 'value' => 'JASA DESAIN RUMAH MEWAH']);
        Feature::create(['label' => 'Simbol & Status Elit', 'icon' => 'fa-crown', 'sort_order' => 1]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('JASA DESAIN RUMAH MEWAH');
        $response->assertSee('Simbol & Status Elit');
    }

    public function test_pages_render(): void
    {
        SiteContent::create(['key' => 'about_title', 'label' => 'Judul Tentang', 'type' => 'text', 'value' => 'TENTANG COREARSITEK']);
        SiteContent::create(['key' => 'about_text', 'label' => 'Teks Tentang', 'type' => 'textarea', 'value' => 'CoreArsitek adalah biro jasa desain arsitektur.']);
        Service::create(['title' => 'JASA DESAIN RUMAH', 'icon' => 'fa-house-chimney', 'sort_order' => 1]);
        Gallery::create(['title' => 'Rumah Klasik', 'image' => 'uploads/seed/gallery-1.svg', 'sort_order' => 1]);

        $this->get(route('portfolio'))->assertStatus(200)->assertSee('Rumah Klasik');
        $this->get(route('services'))->assertStatus(200)->assertSee('JASA DESAIN RUMAH');
        $this->get(route('about'))->assertStatus(200)->assertSee('TENTANG COREARSITEK');
    }

    public function test_removed_menu_items_are_gone(): void
    {
        $this->get('/')
            ->assertDontSee('360 VR')
            ->assertDontSee('Informasi')
            ->assertDontSee('Harga &amp; Layanan')
            ->assertSee('Tentang CoreArsitek');
    }

    public function test_non_admin_cannot_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Member',
            'email' => 'member@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $this->adminUser();

        $this->post(route('admin.login.post'), [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.contents.edit'))->assertStatus(200);
        $this->get(route('admin.services.index'))->assertStatus(200);
        $this->get(route('admin.features.index'))->assertStatus(200);
        $this->get(route('admin.galleries.index'))->assertStatus(200);
    }

    public function test_admin_login_rejects_wrong_password(): void
    {
        $this->adminUser();

        $this->from(route('admin.login'))->post(route('admin.login.post'), [
            'email' => 'admin@example.com',
            'password' => 'salah',
        ])->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}