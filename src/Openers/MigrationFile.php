<?php

namespace OpenOnMake\Openers;

use OpenOnMake\OpenFile;
use Illuminate\Support\Facades\Storage;

class MigrationFile
{
    public static function open()
    {
        OpenFile::open(self::getLatestMigration());
    }

    public static function getLatestMigration()
    {
        self::createDiskForAppRoot();

        $newestMigration = collect(
            Storage::disk('easyOpen')->files('database/migrations')
        )->pop();

        self::unsetDiskForAppRoot();

        return base_path($newestMigration);
    }

    private static function unsetDiskForAppRoot()
    {
        unset(app()->config['filesystems.disks.easyOpen']);
    }

    private static function createDiskForAppRoot()
    {
        app()->config["filesystems.disks.easyOpen"] = [
            'driver' => 'local',
            'root' => base_path(),
        ];
    }
}
