<?php

use App\Models\Cate;

function getCategory()
{
    $category = Cate::orderby('name', 'ASC')->with('subCategory')->where('status',1)->where('showHome', 'Yes')->get();
    return $category;
}
