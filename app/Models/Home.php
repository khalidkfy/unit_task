<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Home extends Model
{
  use HasFactory, SoftDeletes;

  protected $guarded = [];

  public function images(): HasMany
  {
      return $this->hasMany(Image::class, 'home_id');
  }
  public function facilities(): HasMany
  {
      return $this->hasMany(Facility::class, 'home_id');
  }
}
