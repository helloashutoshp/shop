<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
    use HasFactory;
    protected $table = 'cat';
    public function subCategory(){
        return $this->hasMany(Subcategory::class)->where('status',1)->where('showHome', 'Yes');
    }
    public function subCategoryStatus(){
        return $this->hasMany(Subcategory::class)->where('status',1);
    }
}
