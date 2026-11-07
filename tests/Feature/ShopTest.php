<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Models\Variant;
use Database\Seeders\ArticleSeeder;
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
        $this->seed(ArticleSeeder::class);
    }

    public function test_public_pages_render(): void
    {
        foreach (['/', '/shop', '/shop/men', '/shop/women/caftan', '/accessories', '/sale', '/occasion/wedding', '/search?q=x', '/about', '/contact', '/delivery', '/culture', '/size-guide', '/cart', '/sitemap.xml', '/ph/0F7B5F/caftan'] as $u) {
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

    public function test_culture_pages(): void
    {
        $a = Article::first();
        $this->get('/culture')->assertOk()->assertSee($a->title_ar);
        $this->get('/culture/'.$a->slug)->assertOk()->assertSee($a->title_ar);
        $this->get('/culture?topic=henna')->assertOk();
    }

    public function test_cart_and_checkout_flow_decrements_stock(): void
    {
        Setting::put(['shipping_fee' => '30', 'free_shipping_min' => '2000']);
        $v = Variant::where('stock', '>=', 3)->whereHas('product', fn ($q) => $q->active())->firstOrFail();
        $p = $v->product;
        $before = $v->stock;

        $this->post('/cart/add', ['product_id' => $p->id, 'color_id' => $v->color_id, 'size' => $v->size, 'qty' => 2])->assertRedirect('/cart');
        $this->get('/cart')->assertOk()->assertSee($p->name_ar);
        $this->get('/checkout')->assertOk();

        $this->post('/checkout', ['name' => 'Test Client', 'phone' => '0612345678', 'city' => 'Tetouan'])->assertRedirect();
        $o = Order::with('items')->firstOrFail();
        $this->assertSame(2, $o->items->first()->qty);
        $this->assertSame($p->price * 2 + 30, $o->total);
        $this->assertSame($before - 2, $v->fresh()->stock);
        $this->get('/order/'.$o->ref)->assertOk()->assertSee($o->number);

        // annulation → stock remis
        $o->update(['status' => 'cancelled']);
        $this->assertSame($before, $v->fresh()->stock);
    }

    public function test_checkout_validates_phone_and_overselling(): void
    {
        $v = Variant::where('stock', '>=', 1)->firstOrFail();
        $this->post('/cart/add', ['product_id' => $v->product_id, 'color_id' => $v->color_id, 'size' => $v->size, 'qty' => 1]);
        $this->post('/checkout', ['name' => 'Test', 'phone' => '123', 'city' => 'X'])->assertSessionHasErrors('phone');
        $this->post('/cart/add', ['product_id' => $v->product_id, 'color_id' => $v->color_id, 'size' => $v->size, 'qty' => 10])->assertSessionHas('error');
        $this->assertSame(0, Order::count());
    }

    public function test_admin_order_and_article_pages(): void
    {
        $u = User::factory()->create();
        foreach (['/admin', '/admin/orders', '/admin/articles', '/admin/articles/create', '/admin/products/create', '/admin/categories', '/admin/terms'] as $url) {
            $this->actingAs($u)->get($url)->assertOk();
        }
    }
}
