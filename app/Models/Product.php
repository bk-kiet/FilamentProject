<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'status', 'category_id', 'is_active','company_id','tag_id' ];



    //Define the relationship with category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Define the relationship with Company
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

}

