<?php

namespace OpenOnMake;

class OpenFile
{
    public static function open($path)
    {
        exec(
            config('open-on-make.editor') . ' ' .
            config('open-on-make.flags') . ' ' .
            escapeshellarg($path)
        );

        return true;
    }
}
