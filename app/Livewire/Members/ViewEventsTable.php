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

final class ViewEventsTable extends PowerGridComponent
{
    public string $tableName = 'view-events-table-utbdfo-table';
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
        return Post::query()->with('user');
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
            ->add('event_type')
            ->add('event_for')
            ->add('event_cost')
            ->add('event_details', function ($post) {
                $details = '';

                // Always show these
                $details .= "<div><strong>Type:</strong> {$post->event_type}</div>";
                $details .= "<div><strong>For:</strong> {$post->event_for}</div>";
                $details .= "<div><strong>Cost:</strong> {$post->event_cost}</div>";

                // Show meeting link if virtual + members
                if ($post->event_type === 'virtual') {
                    if ($post->event_for === 'members') {
                        $details .= "<div><strong>Meeting Link:</strong> <a href='{$post->meeting_link}' class='text-blue-600 underline' target='_blank'>{$post->meeting_link}</a></div>";
                    }

                    if ($post->event_for === 'public') {
                        if (strtolower($post->event_cost) === 'free') {
                            $details .= "<div><strong>Meeting Link:</strong> <a href='{$post->meeting_link}' class='text-blue-600 underline' target='_blank'>{$post->meeting_link}</a></div>";
                        } elseif (strtolower($post->event_cost) === 'paid') {
                            $details .= "<div class='text-red-600 font-semibold'>Payment Required to Access Meeting Link. Link will be emailed upon successful payment.</div>";
                            $details .= "<div><a href='" . route('events.payment', ['post' => $post->id]) . "' class='mt-1 inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs'>Pay Now</a></div>";
                        }
                    }
                }

                // Show venue and entry code if physical
                if ($post->event_type === 'physical') {
                    $details .= "<div><strong>Venue:</strong> {$post->venue}</div>";
                    $details .= "<div><strong>Entry Code:</strong> {$post->entry_code}</div>";
                }

                return $details;
            })
            ->add('created_at')
            ->add('updated_at_formatted', function ($post) {
                return $post->updated_at->diffForHumans();
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
            ->title('Event Details')
            ->field('event_details')
            ->searchable(false)
            ->sortable(false),

            Column::add()
                ->title('Last Updated')
                ->field(field: 'updated_at_formatted', dataField: 'updated_at')
                ->sortable(),
            Column::make('Created at', 'created_at')
                ->sortable(),

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

    
}
