<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'admin_consultation_reads',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('admin_id')
                    ->constrained('admins')
                    ->cascadeOnDelete();

                $table->foreignId('consultation_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId(
                    'last_read_message_id'
                )
                    ->nullable()
                    ->constrained('messages')
                    ->nullOnDelete();

                $table->timestamp('read_at')
                    ->nullable()
                    ->index();

                $table->timestamps();

                $table->unique([
                    'admin_id',
                    'consultation_id',
                ]);
            }
        );

        /*
         * Jadikan pesan historis sebagai baseline sudah dibaca.
         * Setelah migration ini, hanya pesan pasien yang baru
         * masuk yang menambah badge unread admin.
         */
        $adminIds = DB::table('admins')->pluck('id');

        $latestPatientMessages = DB::table('messages')
            ->where('sender', 'user')
            ->selectRaw(
                'consultation_id, MAX(id) AS last_message_id'
            )
            ->groupBy('consultation_id')
            ->get();

        $now = now();
        $rows = [];

        foreach ($adminIds as $adminId) {
            foreach ($latestPatientMessages as $message) {
                $rows[] = [
                    'admin_id' => $adminId,
                    'consultation_id' =>
                        $message->consultation_id,
                    'last_read_message_id' =>
                        $message->last_message_id,
                    'read_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('admin_consultation_reads')
                ->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'admin_consultation_reads'
        );
    }
};
