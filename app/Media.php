<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    
    public function getFileAttribute()
    {
        $path   = $this->getPath();
        $type   = pathinfo($path, PATHINFO_EXTENSION);
        $data   = file_get_contents($path);
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
