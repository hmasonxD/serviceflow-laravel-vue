<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('company')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();

            $table->string('city')->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postal_code', 20)->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index('name');
            $table->index('email');
            $table->index('company');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
