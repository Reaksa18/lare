<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Slide.php
class Slide extends Model
{
    protected $fillable = ['title', 'description', 'image_path', 'enabled'];

    protected $casts = [
        'enabled' => 'boolean',
    ];
    
}
