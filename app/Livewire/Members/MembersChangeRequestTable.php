<?php

namespace App\Livewire\Members;

use App\Models\MemberChangeRequest;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

final class MembersChangeRequestTable extends PowerGridComponent
{
    public string $tableName = 'members-change-request-table';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return MemberChangeRequest::query()->with('member');
    }

    public function relationSearch(): array
    {
        return [
            'member' => ['full_name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('message')
            ->add('status_by_admin', function ($row) {
                return $row->status_by_admin ?? 'Pending';
            })            
            ->add('member_name', fn ($row) => $row->member?->user?->full_name ?? '—')
            ->add('created_at')
            ->add('updated_at_formatted', fn ($row) => $row->updated_at?->diffForHumans());
    }

    public function columns(): array
    {
        return [            Column::make('Member', 'member_name')->sortable()->searchable(),
            Column::make('Message', 'message')->sortable()->searchable(),
            Column::make('Admin Status', 'status_by_admin')->sortable()->searchable(),
            Column::add()
                ->title('Last Updated')
                ->field(field: 'updated_at_formatted', dataField: 'updated_at')
                ->sortable(),
            Column::make('Created At', 'created_at')->sortable(),

            Column::action('Actions'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('message', 'message')->placeholder('Search message'),
            Filter::inputText('status_by_admin', 'status_by_admin')->placeholder('Admin status'),
            Filter::inputText('member_name', 'member_name')
                ->placeholder('Search by Member')
                ->builder(function (Builder $query, $searchTerm) {
                    $query->whereHas('member', function ($q) use ($searchTerm) {
                        $q->where('full_name', 'like', '%' . $searchTerm . '%');
                    });
                }),
                
            Filter::datetimepicker('updated_at_formatted', 'updated_at'),
            Filter::datetimepicker('created_at', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->redirect(route('member-change-request.edit', ['id' => $rowId]));
    }

    public function actions(MemberChangeRequest $row): array
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
}
