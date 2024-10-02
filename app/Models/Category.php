<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    //define the relation with the product and category //// HasMany mean 1 to many...
    public function products()
    {
        return $this->hasMany(Product::class);

    }


}
