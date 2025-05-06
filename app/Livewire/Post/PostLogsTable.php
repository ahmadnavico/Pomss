<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class PostLogsTable extends PowerGridComponent
{
    public string $tableName = 'posts-logs-table-utbdf8-table';
    public string $sortField = 'timestamp';
    public string $sortDirection = 'desc';

    public $logs; // Public property to hold the logs data

    // Method to set up the table (initial configurations)
    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    // Convert logs array to a Collection in datasource()
    public function datasource(): Collection
    {
        return $this->logs ? collect($this->logs)->map(function ($log) {
            return (array) $log; // Convert log object to array
        }) : collect();
    }

    
    // Define the fields for the table
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')  // Keep the 'id' field if necessary (you can remove this if you don't want to show the 'id' in the table)
            ->add('action')
            ->add('user_name')
            ->add('timestamp', function ($log) {
                // Ensure the timestamp is converted to a Carbon instance for diffForHumans()
                return Carbon::parse($log->timestamp)->diffForHumans();
            });
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'action')
                ->sortable()
                ->searchable(),
            Column::make('Created by', 'user_name'),
            Column::make('Created At', 'timestamp')
                ->sortable()
                ->searchable(),
        ];
    }
}
