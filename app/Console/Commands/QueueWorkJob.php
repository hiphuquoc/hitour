<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\Jobs\DatabaseJob;

class QueueWorkJob extends Command {
    protected $signature = 'queue:work-job {jobId} {--timeout=300}'; // Mặc định 300s = 5p
    protected $description = 'Xử lý một job cụ thể (theo id) từ database queue';

    public function handle() {
        $jobId = $this->argument('jobId');
    
        // Sử dụng transaction để tránh tranh chấp
        DB::beginTransaction();
        try {
            $jobRecord = DB::table('jobs')
                ->where('id', $jobId)
                ->lockForUpdate()
                ->first();
    
            if (!$jobRecord) {
                DB::rollBack();
                $this->error("Job không tồn tại");
                return 1;
            }
    
            DB::table('jobs')
                ->where('id', $jobId)
                ->increment('attempts');
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Lỗi database: " . $e->getMessage());
            return 1;
        }
    
        // Đóng kết nối trước khi chạy job
        DB::disconnect();
    
        try {
            $this->info("Đang xử lý job id: {$jobId}");
            $this->fireJob($jobId);
    
            // Mở lại kết nối để xóa job
            DB::reconnect();
            DB::table('jobs')->where('id', $jobId)->delete();
            DB::disconnect();
    
            $this->info("Job {$jobId} thành công");
        } catch (\Exception $e) {
            $this->error("Lỗi xử lý job: " . $e->getMessage());
        }
    
        return 0;
    }
    

    private function fireJob($jobId) {
        // Sử dụng lại logic từ InternalFireJob
        $jobRecord = DB::table('jobs')->find($jobId);
        
        $connection = config('queue.default');
        $queueName = $jobRecord->queue ?? 'default';
        
        $databaseQueue = app('queue')->connection($connection);
        
        $databaseJob = new DatabaseJob(
            app(),
            $databaseQueue,
            (object) $jobRecord,
            $connection,
            $queueName
        );
        
        $databaseJob->fire();
    }
}