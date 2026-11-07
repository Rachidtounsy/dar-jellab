<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('ref', 12)->unique();           // référence publique (non devinable)
            $t->string('status', 20)->default('new');   // new | confirmed | shipped | delivered | cancelled | returned
            $t->string('name', 120);
            $t->string('phone', 30);
            $t->string('city', 80);
            $t->text('address')->nullable();
            $t->text('notes')->nullable();              // note du client
            $t->text('admin_notes')->nullable();        // note interne
            $t->unsignedInteger('subtotal');
            $t->unsignedInteger('shipping')->default(0);
            $t->unsignedInteger('total');
            $t->string('locale', 2)->default('ar');
            $t->timestamps();
            $t->index(['status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('variant_id')->nullable()->constrained('variants')->nullOnDelete();
            $t->string('name', 190);                    // instantané (le produit peut changer ensuite)
            $t->string('color', 80)->nullable();
            $t->string('size', 12)->nullable();
            $t->boolean('made_to_measure')->default(false);
            $t->unsignedInteger('price');
            $t->unsignedInteger('qty')->default(1);
        });

        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 160)->unique();
            $t->string('topic', 20)->default('other');  // wedding | henna | eid | ramadan | craft | north | other
            $t->string('title_ar', 190);
            $t->string('title_fr', 190)->default('');
            $t->text('excerpt_ar')->nullable();
            $t->text('excerpt_fr')->nullable();
            $t->longText('body_ar')->nullable();
            $t->longText('body_fr')->nullable();
            $t->string('cover')->nullable();
            $t->boolean('published')->default(true);
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['articles', 'order_items', 'orders'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
