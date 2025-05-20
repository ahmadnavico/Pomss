<?php

namespace App\Livewire\Members;

use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class MyEventsTable extends PowerGridComponent
{
    public string $tableName = 'my-events-table-utbdfo-table';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Post::query()
        ->where('user_id', Auth::id())
        ->with('user');  
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        
        return PowerGrid::fields()
            ->add('title')
            ->add('post_status', function ($post) {
                $color = $post->status->color();
                $badge = '<span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-lg text-xs font-medium  ' . $post->status->color() . '">' . $post->status->value . '</span>';
                return $badge;
            })
            ->add('username', function ($post) {
                $link = route('member-management.edit', $post->user->id);
                return '<a href=' . $link . ' class="text-blue-500 hover:underline">' . $post->user->full_name . '</a>';
            })
            ->add('created_at')
            ->add('updated_at_formatted', function ($user) {
                return $user->updated_at->diffForHumans();
            });
    }
    public function columns(): array
    {
        return [
            Column::make('Title', 'title')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'post_status')
                ->sortable(),
            Column::make('User', 'username')
                ->sortable(),
            Column::add()
                ->title('Last Updated')
                ->field(field: 'updated_at_formatted', dataField: 'updated_at')
                ->sortable(),
            Column::make('Created at', 'created_at')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter by Title (Text input filter)
            Filter::inputText('title', 'title')
                ->placeholder('Search by Title'),
            Filter::inputText('post_status', 'status')
            ->placeholder('Status'),
            Filter::inputText('username', 'username')
                ->placeholder('Search by User')
                ->builder(function (Builder $query, $searchTerm) {
                    if (is_array($searchTerm) && isset($searchTerm['value'])) {
                        $searchTerm = $searchTerm['value']; // Extract the 'value' part, which is the username
                    }if (!is_string($searchTerm) || trim($searchTerm) === '') {
                        return;
                    }
                    $query->whereHas('user', function ($q) use ($searchTerm) {
                        $q->where('users.full_name', 'like', "%{$searchTerm}%");
                    });
                }),
            Filter::datetimepicker('updated_at_formatted', 'updated_at'),
            Filter::datetimepicker('created_at', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        // $this->js('alert(' . $rowId . ')');
        $this->redirect(route('post.create', ['post' => $rowId]));
    }

    public function actions(Post $row): array
    {
        return [
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600 hover:text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 0 1 3.182 3.182L6.75 19.964l-4.5 1 1-4.5L16.862 3.487z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9l-6-6" />
                </svg>')
                ->id()
                ->class('inline-flex items-center justify-center')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
