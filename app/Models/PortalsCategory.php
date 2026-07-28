<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class PortalsCategory extends Model
{
    use AsSource;

    protected $table = 'portals_categories';

    protected $fillable = [
        'title',
        'id',
    ];

    public function links() {

        return $this->hasMany(PortalsLink::class, 'portals_category_id');
    }
}
