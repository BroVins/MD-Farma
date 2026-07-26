<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table): void {
            $table->timestamp('first_admin_reply_at')->nullable()->index()->after('status');
            $table->timestamp('last_message_at')->nullable()->index()->after('first_admin_reply_at');
            $table->timestamp('closed_at')->nullable()->index()->after('last_message_at');
        });

        DB::table('consultations')
            ->select(['id', 'status', 'updated_at'])
            ->orderBy('id')
            ->chunkById(100, function ($consultations): void {
                foreach ($consultations as $consultation) {
                    $firstReply = DB::table('messages')
                        ->where('consultation_id', $consultation->id)
                        ->where('sender', 'admin')
                        ->min('created_at');

                    $lastMessage = DB::table('messages')
                        ->where('consultation_id', $consultation->id)
                        ->max('created_at');

                    DB::table('consultations')
                        ->where('id', $consultation->id)
                        ->update([
                            'first_admin_reply_at' => $firstReply,
                            'last_message_at' => $lastMessage,
                            'closed_at' => $consultation->status === 'selesai'
                                ? $consultation->updated_at
                                : null,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table): void {
            $table->dropIndex(['first_admin_reply_at']);
            $table->dropIndex(['last_message_at']);
            $table->dropIndex(['closed_at']);
            $table->dropColumn([
                'first_admin_reply_at',
                'last_message_at',
                'closed_at',
            ]);
        });
    }
};
