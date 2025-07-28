<?php

namespace domain\Services\DatabaseService;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * DatabaseService
 * php version 8
 *
 * @category Service
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class DatabaseService
{

    /**
     * backupExport
     *
     * @return void
     */
    public function backupExport()
    {
        $dbName = env('DB_DATABASE');

        // Define the backup path
        $backupPath = storage_path( 'app/SparkPos');

        $firstWord = Str::before($dbName, '_');

        // Create the backup directory if it doesn't exist
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Create the backup file name with the current timestamp
        $backupFile = $backupPath . '/' . $dbName . '_backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Generate the command to dump the database
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            $dbName,
            $backupFile
        );

        // Execute the command
        $output = null;
        $result = null;
        exec($command, $output, $result);

        $disk_path = config('filesystems.disks.do.backup_path'). '/' . date('Y-m-d') . '/' . $firstWord . '/' . $dbName;

        if ($result === 0) {

            $directory = 'SparkPos';
            $files = Storage::files($directory);

            if (!empty($files)) {
                $temporaryFile = $files[0]; // Assuming you want the first file
                $fileName = basename($temporaryFile);
                $filePath = storage_path('app/' . $temporaryFile);
                Log::info($filePath);
                Log::info($fileName);
                // Storage::disk('do')->putFile($disk_path, $filePath, 'public');
                Storage::disk('do')->putFileAs($disk_path, new \Illuminate\Http\File($filePath), $fileName, 'public');

                Storage::delete($temporaryFile);
            }

        } else {
            Log::error('Database backup failed.');
        }

        return $result;
    }

}
