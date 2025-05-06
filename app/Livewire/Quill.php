<?php

namespace App\Livewire;

use Livewire\Component;

class Quill extends Component
{
    // Define the event constant
    const EVENT_VALUE_UPDATED = 'quill_value_updated';
    public $value;

    public $quillId;

    // Initialize component with default values
    public function mount($value = '')
    {
        $this->value = $value;
        $this->quillId = 'quill-' . uniqid();
    }

    // Render the component view
    public function render()
    {
        return view('livewire.quill');
    }

    // Handle value updates and emit event
    // public function updatedValue($value)
    // {
    //     // Emit the event to the parent component
    //     $this->dispatch(self::EVENT_VALUE_UPDATED, $this->value);
    // }

    // Handle blur event and emit value updated event
    public function handleBlur()
    {
        // Update the value from the hidden textarea
        $this->value = request()->input('value');
        $this->dispatch(self::EVENT_VALUE_UPDATED, $this->value);
    }
}
