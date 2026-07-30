<?php

namespace App\Orchid\Screens\Portals;

use App\Models\PortalsCategory;
use App\Models\PortalsLink;
use App\Orchid\Filters\Portals\PortalsCategoryFilter;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Toast;

class PortalsListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'links' => PortalsLink::query()
                ->with('category')
                ->filters([PortalsCategoryFilter::class])
                //->filters()
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ссылки на порталы';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавление ссылки')
                ->modal('addOrEditLinkModal')
                ->method('addOrEditLink')
                ->icon('bs.plus')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::selection([
                PortalsCategoryFilter::class,
            ]),

            Layout::modal('addOrEditLinkModal', [
                Layout::rows([
                    Input::make('link.id')
                        ->type('hidden'),
                    Input::make('link.title')
                        ->title('Название')
                        ->placeholder('Мой сайт')
                        ->required(),
                    Input::make('link.url')
                        ->title('Ссылка')
                        ->placeholder('https://site.ru')
                        ->help('http(s):// вводить обязательно!')
                        ->required(),
                    Relation::make('link.portals_category_id')
                        ->fromModel(PortalsCategory::class, 'title')
                        ->title('Выбор категории')
                        ->help('Категория обязательна!')
                        ->required(),
                    TextArea::make('link.description')
                        ->title('Слова для поиска')
                        ->rows(3)
                        ->placeholder('Ключевые слова для лучшего поиска (через пробел)')
                ]),
            ])
                ->title('Добавляем ссылку')
                ->async('asyncGetLinks'),

            Layout::table('links', [
                TD::make('id', 'ID')
                   ->width('100px'),
                TD::make('title', 'Название'),
                TD::make('url', 'Ссылка'),
                TD::make('category.title', 'Категория')
                    //->filter(Select::make()->fromModel(PortalsCategory::class, 'title', 'title'))
                    ->render(function (PortalsLink $link) {
                        return $link->category->title ?? '<span class="text-muted">Без категории</span>';
                    }),
                TD::make('Действия')
                   ->render(function(PortalsLink $link) {
                       return DropDown::make('')
                           ->icon('bs.three-dots-vertical')
                           ->list([
                               ModalToggle::make('Редактировать')
                                   ->modal('addOrEditLinkModal')
                                   ->method('addOrEditLink')
                                   ->modalTitle('Редактируем ссылки')
                                   ->asyncParameters([
                                       'link' => $link->id,
                                   ])
                                   ->icon('bs.pencil'),
                               Button::make('Удалить')
                                   ->method('deleteLink')
                                   ->confirm('Вы уверены, что хотите удалить эту ссылку?')
                                   ->parameters([
                                       'id' => $link->id
                                   ])
                                   ->icon('bs.trash'),
                           ]);
                }),

            ]),

        ];
    }

    public function asyncGetLinks(PortalsLink $link): array {

        return [
            'link' => $link,
        ];
    }

    public function addOrEditLink(Request $request) {

        $validated = request()->validate([
            'link.id' => 'nullable|exists:portals_links,id',
            'link.title' => 'required|string|max:255',
            'link.url' => 'required|url|max:255',
            'link.portals_category_id' => 'required|integer|exists:portals_categories,id',
            'link.description' => 'nullable|string|max:255',
        ]);

        PortalsLink::updateOrCreate(
            ['id' => $validated['link']['id']],
            [
                'title' => $validated['link']['title'],
                'url' => $validated['link']['url'],
                'portals_category_id' => $validated['link']['portals_category_id'],
                'description' => $validated['link']['description'],
            ]
        );

        Toast::info('Ссылка сохранена');
    }

    public function deleteLink(Request $request) {

        $link = PortalsLink::findOrFail($request->input('id'));
        $link->delete();

        Toast::warning('Ссылка успешно удалена');
    }
}
