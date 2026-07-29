<?php

namespace App\Orchid\Filters\Portals;

use App\Models\PortalsCategory;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;

class PortalsCategoryFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Категория';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return [
            'portals_category_id',
        ];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return
            $builder->whereHas('category', function (Builder $query) {
                $query->where('title', $this->request->get('portals_category_id'));
            });
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Relation::make('portals_category_id')
                ->fromModel(PortalsCategory::class, 'title', 'title')
                ->title('Выберите категорию')
        ];
    }
}
