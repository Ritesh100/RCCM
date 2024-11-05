<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'name',
        'email',
        'path',
        'reportingTo',
    ];

   public function deleteFile()
   {
       if (Storage::exists($this->path)) {
           return Storage::delete($this->path);
       }
       return false;
   }

}
