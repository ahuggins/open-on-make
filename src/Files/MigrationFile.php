<?php

namespace OpenOnMake\Files;

class MigrationFile
{
    public static function getLatestMigrationFile()
    {
        return (new static)->getLatestMigration();
    }

    public function getLatestMigration()
    {
        $this->createDiskForAppRoot();

        $newestMigration = collect(
            \Storage::disk('easyOpen')->files('database/migrations')
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
