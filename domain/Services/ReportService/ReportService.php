<?php

namespace domain\Services\ReportService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * ReportService
 * php version 8
 *
 * @category Service
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class ReportService
{
    /**
     * deleteOldReports
     *
     * @return void
     */
    public function deleteOldReports()
    {
        $directory = 'public/exports/Reports';
        $files = Storage::files($directory);
        $daysOld = 7;

        foreach ($files as $file) {
            $lastModified = Storage::lastModified($file);
            $fileAgeInDays = Carbon::now()->diffInDays(Carbon::createFromTimestamp($lastModified));

            if ($fileAgeInDays >= $daysOld) {
                Storage::delete($file);
            }
        }

        return 0;
    }
}
