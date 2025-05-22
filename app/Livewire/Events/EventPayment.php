<?php

namespace App\Livewire\Events;

use App\Models\Post;
use App\Models\Payment;
use App\Models\SignupMember;
use App\Notifications\SendMeetingLink;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class EventPayment extends Component
{
    public $post;
    public $name;
    public $email;

    // Payment fields
    public $card_number;
    public $cvc;
    public $address_line1;
    public $address_line2;
    public $city;
    public $state;
    public $postal_code;
    public $country;

    public function mount($post)
    {
        $this->post = $post;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('signup_members')->where(function ($query) {
                    return $query->where('post_id', $this->post->id);
                }),
            ],
            'card_number' => 'required|string',
            'cvc' => 'required|string',
            'address_line1' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);

        $signup = SignupMember::create([
            'post_id' => $this->post->id,
            'name' => $this->name,
            'email' => $this->email,
            'payment_method' => 'card',
        ]);

        $payment = Payment::create([
            'signup_member_id' => $signup->id,
            'identifier' => Str::uuid(), // unique identifier
            'card_number' => $this->card_number,
            'cvc' => $this->cvc,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'status' => 'paid',
            'amount' => '100',
        ]);

        if($payment){
            $this->dispatch('notify', title: 'Payment successful!', message: 'Meeting link will be emailed to you..', type: 'success');            
            Notification::route('mail', $signup->email)
            ->notify(new SendMeetingLink($this->post, $signup->name));

            return redirect()->route('view.events');
        }else{
            $this->dispatch('notify', title: 'Payment Failed!', message: 'Something Went wrong..', type: 'error');           
        }
    

    }

    public function render()
    {
        return view('livewire.events.event-payment');
    }
}
