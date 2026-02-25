<?php

namespace Modules\User\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Batchable;

class BulkUsersChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels , Batchable;

    /**
     * @param array<int, array{name:string,email:string}> $users
     */
    public function __construct(
        public string $importId,
        public array $users
    ) {}

    public function handle(): void
    {
        $now  = now();
        $date = $now->toDateString();

        DB::transaction(function () use ($now, $date) {

            // Progress: processed
            DB::table('bulk_imports')
                ->where('id', $this->importId)
                ->increment('processed_rows', count($this->users), ['updated_at' => $now]);

            // Prepare rows
            $rows = [];
            $emails = [];

            foreach ($this->users as $u) {
                $emails[] = $u['email'];
                $rows[] = [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'import_id' => $this->importId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert users in bulk (DB unique index on email is required)
            // insertOrIgnore avoids job failure if concurrency creates duplicates
            DB::table('users')->insertOrIgnore($rows);

            // Find which users were actually inserted for THIS import
            $inserted = DB::table('users')
                ->select('id')
                ->where('import_id', $this->importId)
                ->whereIn('email', $emails)
                ->get();

            $userIds = $inserted->pluck('id')->map(fn ($id) => (int) $id)->all();
            $insertedCount = count($userIds);

            // Update inserted/skipped counts (skipped here is "not inserted" at DB level)
            $skipped = count($this->users) - $insertedCount;

            if ($insertedCount > 0) {
                DB::table('bulk_imports')
                    ->where('id', $this->importId)
                    ->increment('inserted_rows', $insertedCount, ['updated_at' => $now]);
            }

            if ($skipped > 0) {
                DB::table('bulk_imports')
                    ->where('id', $this->importId)
                    ->increment('skipped_rows', $skipped, ['updated_at' => $now]);
            }

            if ($insertedCount === 0) {
                return;
            }

            // Activities bulk insert
            $activities = [];
            foreach ($userIds as $userId) {
                $activities[] = [
                    'action_type'  => 'USER_CREATED',
                    'reference_id' => $userId,
                    'metadata'     => json_encode(['source' => 'bulk', 'import_id' => $this->importId]),
                    'created_at'   => $now,
                ];
            }
            DB::table('activities')->insert($activities);

            // Welcome messages bulk insert
            $messages = [];
            foreach ($userIds as $userId) {
                $messages[] = [
                    'user_id'      => $userId,
                    'message_type' => 'EMAIL',
                    'message_content' => 'Welcome to our platform!',
                    'status'       => 'SENT',
                    'created_at'   => $now,
                ];
            }
            DB::table('messages')->insert($messages);

            // Daily stats (requires UNIQUE/PK on date!)
            DB::table('statistics')->insertOrIgnore([
                'date' => $date,
            ]);

            DB::table('statistics')
                ->where('date', $date)
                ->increment('total_users_created', $insertedCount, ['updated_at' => $now]);
        });
    }
}