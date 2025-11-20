<?php

    use App\Enums\StatusOrder;
    use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->string('address');
            $table->string('phone');
            $table->decimal('total', 10, 2);
            $table->timestamps();
            $table->string('status')->default(StatusOrder::PENDING->value);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
