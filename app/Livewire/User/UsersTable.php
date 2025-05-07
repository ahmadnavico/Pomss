<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UsersTable extends PowerGridComponent
{
    public string $tableName = 'users-table-g4nzmq-table';
    public bool $deferLoading = true;
    public bool $showFilters = true;

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
        return User::query()
        ->where('id', '!=', auth()->id());
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('id')
            ->add('full_name')
            ->add('email')
            ->add('created_at')
            ->add('updated_at_formatted', function ($user) {
                return $user->updated_at->diffForHumans();
            });
    }

    public function columns(): array
    {
        return [
            // Column::make('Id', 'id'),
            Column::make('Name', 'full_name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::add()
                ->title('Last Updated')
                ->field(field: 'updated_at_formatted', dataField: 'updated_at')
                ->sortable()
                ->searchable(),
            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('email', 'email'),
            Filter::inputText('full_name', 'full_name'),
            Filter::datetimepicker('updated_at_formatted', 'updated_at'),
            Filter::datetimepicker('created_at', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        // $this->js('alert(' . $rowId . ')');
        //Move to edit page
        $this->redirect(route('member-management.edit', ['user' => $rowId]));
    }
    #[\Livewire\Attributes\On('gift_credits')]
    public function gift_credits($rowId): void
    {
        // Redirect to the gift credits page
        $this->redirect(route('gift-credits', ['user' => $rowId]));
    }
    #[\Livewire\Attributes\On('view_user')]
    public function view_user($rowId): void
    {
        // Redirect to the gift credits page
        $this->redirect(route('member-management.view', ['user' => $rowId]));
    }

    public function actions(User $row): array
    {
        return array_filter([
            Button::add('view_user')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600 hover:text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.274 1.02-.722 1.973-1.314 2.818m-7.126 3.182A11.955 11.955 0 0 1 12 21c-2.67 0-5.138-.94-7.126-2.818m7.126-3.182a4 4 0 1 1 5.656 5.656" />
                </svg>')
            ->id()
            ->class('inline-flex items-center justify-center mr-3')
            ->dispatch('view_user', ['rowId' => $row->id]),
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600 hover:text-yellow-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 0 1 3.182 3.182L6.75 19.964l-4.5 1 1-4.5L16.862 3.487z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9l-6-6" />
                </svg>')
                ->id()
                ->class('inline-flex items-center justify-center mr-3')
                ->dispatch('edit', ['rowId' => $row->id]),
        
            auth()->user()->can('gift credits') ? Button::add('gift_credits')
                ->slot('<svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="28"
                    height="28"
                    class="text-yellow-600 hover:text-yellow-700"
                    fill="currentColor"
                >
                    <path d="M20 7h-2.18a3 3 0 0 0 .18-1 3 3 0 0 0-6-1.8A3 3 0 0 0 6 6c0 .35.06.69.18 1H4a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-6a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2zM9 4a1 1 0 0 1 1 1v2H8a1 1 0 0 1-1-1 1 1 0 0 1 2-2zm6 0a1 1 0 0 1 1 1 1 1 0 0 1-1 1h-2V5a1 1 0 0 1 1-1zm-6 8v8H6v-8zm2 8v-8h2v8zm4 0v-8h3v8zm5-10H4v-2h16z" />
                </svg>')
                ->id()
                ->class('inline-flex items-center justify-center mr-3')
                ->dispatch('gift_credits', ['rowId' => $row->id]) : null
        ]);
        
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
