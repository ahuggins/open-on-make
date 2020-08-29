<?php

namespace OpenOnMake\Openers;

use OpenOnMake\OpenFile;
use Illuminate\Support\Facades\Storage;

class MigrationFile
{
    public function getLatestMigration()
    {
        $this->createDiskForAppRoot();

        $newestMigration = collect(
            Storage::disk('easyOpen')->files('database/migrations')
        )->pop();

        $this->unsetDiskForAppRoot();

        return base_path($newestMigration);
    }

    private function unsetDiskForAppRoot()
    {
        unset(app()->config['filesystems.disks.easyOpen']);
    }

    private function createDiskForAppRoot()
    {
        app()->config["filesystems.disks.easyOpen"] = [
            'driver' => 'local',
            'root' => base_path(),
        ];
    }
}
