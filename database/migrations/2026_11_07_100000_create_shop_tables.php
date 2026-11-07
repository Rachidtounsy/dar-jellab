<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->string('key', 80)->primary();
            $t->text('value')->nullable();
        });

        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('segment', 12);            // men | women | boys | girls
            $t->string('slug', 60);
            $t->string('name_ar', 120);
            $t->string('name_fr', 120)->default('');
            $t->boolean('accessory')->default(false);
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('active')->default(true);
            $t->unique(['segment', 'slug']);
        });

        Schema::create('terms', function (Blueprint $t) {
            $t->id();
            $t->string('type', 20);                // color | fabric | stitch | occasion
            $t->string('slug', 60);
            $t->string('name_ar', 120);
            $t->string('name_fr', 120)->default('');
            $t->string('hex', 9)->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('active')->default(true);
            $t->unique(['type', 'slug']);
        });

        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 160)->unique();
            $t->string('segment', 12);
            $t->foreignId('category_id')->constrained('categories');
            $t->string('name_ar', 190);
            $t->string('name_fr', 190)->default('');
            $t->text('desc_ar')->nullable();
            $t->text('desc_fr')->nullable();
            $t->unsignedInteger('price');
            $t->unsignedInteger('old_price')->nullable();
            $t->foreignId('fabric_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->foreignId('stitch_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->boolean('made_to_measure')->default(false);
            $t->string('badge', 20)->nullable();
            $t->boolean('featured')->default(false);
            $t->boolean('active')->default(true);
            $t->unsignedInteger('views')->default(0);
            $t->timestamps();
            $t->index(['active', 'segment', 'category_id']);
        });

        Schema::create('product_term', function (Blueprint $t) {
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('term_id')->constrained('terms')->cascadeOnDelete();
            $t->primary(['product_id', 'term_id']);
        });

        Schema::create('variants', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('color_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->string('size', 12)->default('');
            $t->unsignedInteger('stock')->default(0);
        });

        Schema::create('product_images', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('path', 190);
            $t->unsignedInteger('sort')->default(0);
        });
    }

    public function down(): void
    {
        foreach (['product_images', 'variants', 'product_term', 'products', 'terms', 'categories', 'settings'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
