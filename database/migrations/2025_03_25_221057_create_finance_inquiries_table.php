<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('finance_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('listing_id');
            $table->enum('term', ['1 Year', '2 Years', '3 Years']);
            $table->decimal('down_payment', 10, 2)->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['Pending', 'Reviewed'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_inquiries');
    }
};
