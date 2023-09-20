<?php

use App\Models\Todo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mapping = [
            'pending' => 1,
            'done' => 2,
            'archived' => 4,
        ];

        Todo::all()->each(function ($todo) use ($mapping) {
            $todo->update([
                'state' => $mapping[$todo->getRawOriginal('state')],
            ]);
        });

        Schema::table('todos', function (Blueprint $table) {
            $table->string('state')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mapping = [
            1 => 'pending',
            2 => 'done',
            4 => 'archived',
        ];

        Todo::all()->each(function ($todo) use ($mapping) {
            $todo->update([
                'state' => $mapping[$todo->getRawOriginal('state')],
            ]);
        });

        Schema::table('todos', function (Blueprint $table) {
            $table->string('state')->default('pending')->change();
        });
    }
};
