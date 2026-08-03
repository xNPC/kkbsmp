<?php

namespace App\Livewire\Portals;
use Livewire\Attributes\Layout;
use App\Models\PortalsCategory;
use Livewire\Component;

#[Layout('layouts.app.main')]
class Portals extends Component
{
    // 1. Привязываем свойство поиска к инпуту (wire:model="search")
    public $search = '';

    public function render()
    {
        // 2. Загружаем категории вместе со ссылками и фильтруем по поиску
        $categories = PortalsCategory::with(['links' => function ($query) {
            $query->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"));
        }])->get();

        // 3. Передаем переменную $categories в ваш Blade-файл
        return view('theme.portals.portals', [
            'categories' => $categories,
        ]);
            //->layout('layouts.app.main');
    }
}
