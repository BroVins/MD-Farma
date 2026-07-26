<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'consultations',
            function (Blueprint $table): void {
                $table->string(
                    'last_message_sender',
                    20
                )
                    ->nullable()
                    ->index()
                    ->after('last_message_at');
            }
        );

        DB::table('consultations')
            ->select('id')
            ->orderBy('id')
            ->chunkById(
                100,
                function ($consultations): void {
                    foreach ($consultations as $item) {
                        $lastMessage = DB::table(
                            'messages'
                        )
                            ->where(
                                'consultation_id',
                                $item->id
                            )
                            ->orderByDesc('id')
                            ->first([
                                'sender',
                                'created_at',
                            ]);

                        if (! $lastMessage) {
                            continue;
                        }

                        DB::table('consultations')
                            ->where('id', $item->id)
                            ->update([
                                'last_message_sender' =>
                                    $lastMessage->sender,
                                'last_message_at' =>
                                    $lastMessage->created_at,
                            ]);
                    }
                }
            );
    }

    public function down(): void
    {
        Schema::table(
            'consultations',
            function (Blueprint $table): void {
                $table->dropIndex([
                    'last_message_sender',
                ]);

                $table->dropColumn(
                    'last_message_sender'
                );
            }
        );
    }
};
