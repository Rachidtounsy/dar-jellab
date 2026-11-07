<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\BaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BaseSeeder::class);
        $this->seed(DemoSeeder::class);
    }

    public function test_public_pages_render(): void
    {
        foreach (['/', '/shop', '/shop/men', '/shop/women/caftan', '/accessories', '/sale', '/occasion/wedding', '/search?q=x', '/about', '/contact', '/delivery', '/sitemap.xml', '/ph/0F7B5F/caftan'] as $u) {
            $this->get($u)->assertOk();
        }
        $this->get('/shop/unknown')->assertNotFound();
        $this->get('/p/nope')->assertNotFound();
    }

    public function test_product_page_and_filters(): void
    {
        $p = Product::first();
        $this->get('/p/'.$p->slug)->assertOk()->assertSee($p->name_ar);
        $this->get('/shop?color=emerald')->assertOk();
        $this->get('/shop/men?fabric=crepe&min=100&max=900&sort=price_asc')->assertOk();
        $this->assertGreaterThan(0, Product::filter(['segment' => 'women', 'cat' => 'caftan'])->count());
        $this->assertSame(0, Product::filter(['segment' => 'men', 'cat' => 'takchita'])->count());
    }

    public function test_settings_drive_the_storefront(): void
    {
        Setting::put(['whatsapp1' => '212600112233', 'facebook' => 'https://facebook.com/darjellaba']);
        $this->get('/contact')->assertSee('wa.me/212600112233', false)->assertSee('facebook.com/darjellaba', false);
    }

    public function test_language_switch_sets_locale(): void
    {
        $this->withCookie('lang', 'fr')->get('/')->assertSee('lang="fr"', false)->assertSee('Hommes');
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin/products')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create())->get('/admin/products')->assertOk();
        $this->actingAs(User::factory()->create())->get('/admin/settings')->assertOk();
    }
}
