<?php
/**
 * Part of the CsCms package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2022, Coder Studios Ltd
 *
 * @see       https://www.coderstudios.com
 */

namespace CoderStudios\CsCms\Library;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;

class Utils
{
    public function convertBytes($number)
    {
        $len = strlen($number);

        if ($len < 4) {
            return sprintf('%d b', $number);
        }
        if ($len >= 4 && $len <= 6) {
            return sprintf('%0.2f Kb', $number / 1024);
        }
        if ($len >= 7 && $len <= 9) {
            return sprintf('%0.2f Mb', $number / 1024 / 1024);
        }

        return sprintf('%0.2f Gb', $number / 1024 / 1024 / 1024);
    }

    public function getDirectorySize($directory)
    {
        $filesys = new Filesystem();
        $size = 0;
        if (is_dir($directory)) {
            foreach ($filesys->allFiles($directory) as $file) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    public function getBackUps()
    {
        $backups = [];
        $files = new Filesystem();
        if (is_dir(config('cscms.coderstudios.backup_dir'))) {
            foreach ($files->allFiles(config('cscms.coderstudios.backup_dir')) as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->convertBytes($file->getSize()),
                    'location' => $file->getRealPath(),
                    'id' => md5($file->getFilename()),
                    'modified_at' => Carbon::createFromTimestamp($file->getMTime())->format('d-m-Y H:i'),
                ];
            }
        }

        return $backups;
    }
}
