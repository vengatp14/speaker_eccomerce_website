<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebImage extends Model
{
    use HasFactory;
     protected $fillable = [
        'image_1_path', 'image_1_width', 'image_1_height',
        'image_2_path', 'image_2_width', 'image_2_height',
        'image_3_path', 'image_3_width', 'image_3_height',
        'image_4_path', 'image_4_width', 'image_4_height',
        'image_5_path', 'image_5_width', 'image_5_height',
    ];
}
