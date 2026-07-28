<?php

namespace App\Orchid\Screens\Portals;

use App\Models\PortalsCategory;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoriesListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => PortalsCategory::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список категорий порталов';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавление категории')
                ->modal('addOrEditCategoryModal')
                ->method('addOrEditCategory')
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
            Layout::modal('addOrEditCategoryModal', [
                Layout::rows([
                    Input::make('category.id')
                        ->type('hidden'),
                    Input::make('category.title')
                        ->title('Название')
                        ->placeholder('Название категории')
                        ->required(),
                ]),
            ])
                ->title('Добавление категории')
                ->async('asyncGetCategory'),

            Layout::table('categories', [
                TD::make('id', 'ID')
                    ->width('100px'),
                TD::make('title', 'Название'),
                TD::make('Действия')
                    ->render(function (PortalsCategory $category) {
                        return DropDown::make('')
                            ->icon('bs.three-dots-vertical')
                            ->list([
                                ModalToggle::make('Редактировать')
                                    ->modal('addOrEditCategoryModal')
                                    ->method('addOrEditCategory')
                                    ->modalTitle('Редактирование категории')
                                    ->asyncParameters([
                                        'category' => $category->id,
                                    ])
                                    ->icon('bs.pencil'),
                                Button::make('Удалить')
                                    ->method('deleteCategory')
                                    ->confirm('Вы уверены, что хотите удалить эту категорию?')
                                    ->parameters([
                                        'id' => $category->id,
                                    ])
                                    ->icon('bs.trash'),
                            ]);
                    }),

            ]),

        ];
    }

    public function asyncGetCategory(PortalsCategory $category): array {

        return [
            'category' => $category,
        ];
    }

    public function addOrEditCategory(Request $request) {

        $categoryId = $request->input('category.id');

        $validated = $request->validate([
            'category.id' => 'nullable|integer',
            'category.title' => 'required|string|max:255',
        ]);

        PortalsCategory::updateOrCreate(
            ['id' => $validated['category']['id']],
            ['title' => $validated['category']['title']]
        );

        Toast::info('Категория сохранена');

    }

    public function deleteCategory(Request $request)
    {
        $category = PortalsCategory::findOrFail($request->input('id'));
        $category->delete();

        Toast::warning('Категория успешно удалена');
    }
}
