<?php

namespace Modules\User\Services;

use App\Service\HelperResponse;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\Jobs\BulkUsersChunkJob;


class BulkUserOnboardingService
{
    public function startBulk(array $users, int $chunkSize = 500): array
    {
        $importId = (string) Str::uuid();

        DB::table('bulk_imports')->insert([
            'id' => $importId,
            'status' => 'RUNNING',
            'total_rows' => count($users),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $this->assertNoExistingEmails($users, $chunkSize);

        $jobs = [];
        foreach (array_chunk($users, $chunkSize) as $chunk) {
            $jobs[] = new BulkUsersChunkJob($importId, $chunk);
        }

        $batch = Bus::batch($jobs)
            ->name("Bulk Users Import {$importId}")
            ->onQueue('bulk')
            ->then(fn () => $this->markCompleted($importId))
            ->catch(fn ($batch, $e) => $this->markFailed($importId, $e))
            ->dispatch();

        return ['import_id' => $importId, 'batch_id' => $batch->id];
    }


    public function status(string $importId): array
    {
        $row = DB::table('bulk_imports')->where('id', $importId)->first();
        abort_if(!$row, 404, 'Import not found');
        return (array) $row;
    }


    private function markCompleted(string $importId): void
    {
        DB::table('bulk_imports')->where('id', $importId)->update([
            'status' => 'COMPLETED',
            'updated_at' => now(),
        ]);
    }


    private function markFailed(string $importId, \Throwable $e): void
    {
        DB::table('bulk_imports')->where('id', $importId)->update([
            'status' => 'FAILED',
            'error' => $e->getMessage(),
            'updated_at' => now(),
        ]);
    }


    private function assertNoExistingEmails(array $users, int $chunkSize): void
    {
        $emails = array_map(fn ($u) => $u['email'], $users);

        $existing = [];
        foreach (array_chunk($emails, $chunkSize) as $chunk) {
            $found = DB::table('users')->whereIn('email', $chunk)->pluck('email')->all();
            foreach ($found as $email) {
                $existing[$email] = true;
            }
        }

        if (!empty($existing)) {
            $errors = ['users' => array_map(fn ($e) => "Email already exists: {$e}", array_keys($existing))];
            HelperResponse::error($errors  ,  'Validation failed' ,  422 , false);
        }
    }
}