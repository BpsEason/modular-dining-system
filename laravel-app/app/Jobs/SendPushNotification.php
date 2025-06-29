<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $message,
        protected int $userId,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $endpoint = env('PUSH_SERVICE_ENDPOINT');
            $apiKey = env('PUSH_SERVICE_API_KEY');

            if (! $endpoint || ! $apiKey) {
                throw new \Exception('推播服務配置不完整');
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Accept' => 'application/json',
            ])->timeout(10)->post($endpoint, [
                'user_id' => $this->userId,
                'message' => $this->message,
            ]);

            if ($response->successful()) {
                Log::info("已成功向用戶 {$this->userId} 發送推播通知: '{$this->message}'");
            } else {
                throw new \Exception("推播通知失敗，狀態碼: {$response->status()}");
            }
        } catch (Throwable $e) {
            Log::error("發送推播通知失敗 (用戶: {$this->userId}): " . $e->getMessage());

            // Fallback: Log failed notification to the database for later analysis.
            NotificationLog::create([
                'user_id' => $this->userId,
                'message' => $this->message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // If max attempts are reached, log a critical message.
            if ($this->attempts() >= $this->tries) {
                Log::critical("推播通知已達最大重試次數，無法發送給用戶 {$this->userId}");
                return;
            }

            // Re-throw the exception to trigger a retry.
            throw $e;
        }
    }
}
