<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class PortalsLink extends Model
{
    use AsSource, Filterable;

    protected $table = 'portals_links';

    protected $fillable = [
        'title',
        'url',
        'portals_category_id',
        'description',
        'id',
    ];

    protected $allowedFilters = [
        'portals_category_id' => Where::class,
    ];

    public function category() {
        return $this->belongsTo(PortalsCategory::class, 'portals_category_id');
    }
}
