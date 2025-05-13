<?php

namespace App\Livewire\Profile;

use App\Models\Member;
use App\Models\MemberChangeRequest;
use App\Models\Qualification;
use App\Models\Testimonial;
use App\Models\User;
use App\Notifications\MemberChangeRequested;
use App\Notifications\MemberProfileApprovalNotification;
use App\Notifications\ProfileSubmittedForApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use App\Models\MemberProfileApproval;

use Livewire\WithFileUploads;
use App\Notifications\MemberChangeRequestResponse;

class MemberDetailsForm extends Component
{
    use WithFileUploads;
    public $member_Id;
    public $title = '';
    public $dob = '';
    public $phone_number = '';
    public $cnic_copy;
    public $pmdc_licence_copy;
    public $fcps_degree_copy;
    public $qualifications = []; // Qualifications
    public $certifications = []; // Each item: ['name' => '', 'image' => null]
    public $newCertification = ['name' => '', 'image' => null];
    public $tempImage;
    public $experience = [];
    public $specialities = [];
    public $testimonials = [];
    public $bio = '';
    public $location = '';
    public $socialLinks = [];
    public $availability = []; // No default week

    
    public $existing_cnic_copy;
    public $existing_pmdc_licence_copy;
    public $existing_fcps_degree_copy;
    public $requestApproval = false;
    public bool $haveRequests = false;
    public $profile_submitted = false;
    public $showRequestModal = false;
    public $changeReason = '';
    public $approvalModalOpen = false;
    public $isApproved;
    public $approvalMessage;
    public $profileApproved = false;

    //maanage requests 
    public $manageRequestModalOpen = false;
    public $requestApproved = null;
    public $requestRejectionReason = '';
    public $showRejectionReasonField = false;
    public $changeRequest; // Add this

    
    protected function rules()
{
    return [
        // Basic Fields
        'title' => 'required|string|max:255',
        'dob' => 'required|date',
        'phone_number' => 'required|string|max:20',
        'bio' => 'required|string',
        'location' => 'required|string|max:255',

        // Social Links
        'socialLinks' => 'required|array',
        'socialLinks.*.platform' => 'required|string|max:255',
        'socialLinks.*.url' => 'required|url|max:255',

        // Conditionally required file uploads
        'cnic_copy' => [$this->existing_cnic_copy ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        'pmdc_licence_copy' => [$this->existing_pmdc_licence_copy ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        'fcps_degree_copy' => [$this->existing_fcps_degree_copy ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],

        // // Qualifications
        // 'qualifications' => 'required|array|min:1',
        // 'qualifications.*' => 'required|string|max:255',

        // // Certifications
        // 'certifications' => 'required|array',
        // 'certifications.*.name' => 'required|string|max:255',
        // 'certifications.*.image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        // // Experience
        // 'experience' => 'required|array',
        // 'experience.*.hospital' => 'required|string|max:255',
        // 'experience.*.from' => 'required|string|size:4|regex:/^\d{4}$/',
        // 'experience.*.to' => 'required|string|size:4|regex:/^\d{4}$/',

        // // Specialities
        // 'specialities' => 'required|array',
        // 'specialities.*' => 'required|string|max:255',

        // // Testimonials
        // 'testimonials' => 'nullable|array',
        // 'testimonials.*.patient_name' => 'required_with:testimonials|string|max:255',
        // 'testimonials.*.feedback' => 'required_with:testimonials|string',
        // 'testimonials.*.patient_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];
}


    public function addSocialLink()
    {
        $this->socialLinks[] = ['platform' => '', 'url' => ''];
    }
    public function removeSocialLink($index)
    {
        unset($this->socialLinks[$index]);
        $this->socialLinks = array_values($this->socialLinks); // Reindex
    }
    public function addCertification()
    {
        $this->certifications[] = ['name' => '', 'image' => null];
    }

    public function removeCertification($index)
    {
        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications); // Re-index
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'certifications.') && str_ends_with($propertyName, '.image')) {
            $this->validateOnly($propertyName, [
                $propertyName => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);
        }
    }
    public function addSpeciality()
    {
        $this->specialities[] = '';
    }

    public function removeSpeciality($index)
    {
        unset($this->specialities[$index]);
        $this->specialities = array_values($this->specialities); // Reindex
    }
    public function deleteFile($type)
    {
        $filePath = null;

        switch ($type) {
            case 'cnic_copy':
                $filePath = $this->existing_cnic_copy;
                $this->existing_cnic_copy = null;
                $this->cnic_copy = null;
                break;

            case 'pmdc_licence_copy':
                $filePath = $this->existing_pmdc_licence_copy;
                $this->existing_pmdc_licence_copy = null;
                $this->pmdc_licence_copy = null;
                break;

            case 'fcps_degree_copy':
                $filePath = $this->existing_fcps_degree_copy;
                $this->existing_fcps_degree_copy = null;
                $this->fcps_degree_copy = null;
                break;
        }

        if ($filePath && Storage::disk('member')->exists($filePath)) {
            Storage::disk('member')->delete($filePath);
        }
    }


    public function deleteCertificationFile($index)
    {
        $cert = $this->certifications[$index];

        if (isset($cert['image']) && is_string($cert['image'])) {
            // Delete from member disk
            Storage::disk('member')->delete($cert['image']);
        }

        // Reset image to null so input field reappears
        $this->certifications[$index]['image'] = null;
    }

    public function addTestimonial()
    {
        $this->testimonials[] = [
            'patient_name' => '',
            'patient_image' => null,
            'feedback' => '',
        ];
    }

    public function removeTestimonial($index)
    {
        if (isset($this->testimonials[$index]['id'])) {
            $testimonial = Testimonial::find($this->testimonials[$index]['id']);

            if ($testimonial) {
                // Delete the image from storage if it exists
                if ($testimonial->patient_image && Storage::disk('member')->exists($testimonial->patient_image)) {
                    Storage::disk('member')->delete($testimonial->patient_image);
                }

                // Delete the testimonial record
                $testimonial->delete();
            }
        }

        // Remove from local state
        unset($this->testimonials[$index]);
        $this->testimonials = array_values($this->testimonials); // reindex array
    }

    public function addExperience()
    {
        $this->validate([
            'experience.*.hospital' => 'required|string|max:255',
            'experience.*.from' => 'required|string|size:4|regex:/^\d{4}$/', // Validate it is a 4-digit year
            'experience.*.to' => 'required|string|size:4|regex:/^\d{4}$/'
        ]);
        
        $this->experience[] = ['hospital' => '', 'from' => '', 'to' => ''];
    }


    public function removeExperience($index)
    {
        unset($this->experience[$index]);
        $this->experience = array_values($this->experience); // Reindex
    }


    public function addAvailabilitySlot()
    {
        $this->availability[] = ['day' => '', 'open' => '', 'close' => ''];
    }

    public function removeAvailabilitySlot($index)
    {
        unset($this->availability[$index]);
        $this->availability = array_values($this->availability); // Reindex
    }

    public function addQualification()
    {
        $this->qualifications[] = ''; // Add an empty value for a new qualification
    }

    public function removeQualification($index)
    {
        unset($this->qualifications[$index]);
        $this->qualifications = array_values($this->qualifications); // Re-index
    }


    public function openRequestModal()
    {
        $this->resetErrorBag();
        $this->showRequestModal = true;
    }

    public function closeRequestModal()
    {
        $this->showRequestModal = false;
    }

    public function submitChangeRequest()
    {
        $this->validate([
            'changeReason' => 'required|string|min:5',
        ]);
       
        
        $changeRequest = MemberChangeRequest::create([
            'member_id' => $this->member_Id,
            'message' => $this->changeReason,
        ]);
        $admins = User::role('Admin')->get();
         // using Spatie roles
        $this->dispatch('changerequest');
        foreach ($admins as $admin) {
            $admin->notify(new MemberChangeRequested($changeRequest));
        }
        $this->reset(['showRequestModal', 'changeReason']);

        $this->dispatch('notify', title: 'Request Send', message: 'Your Request for Changes Submitted.', type: 'success'); 
            
    }


    public function openApprovalModal()
    {
        $this->approvalModalOpen = true;
    }


    public function submitApproval()
    {
        $this->validate([
            'isApproved' => 'required|boolean',
            'approvalMessage' => 'nullable|string|max:1000',
        ]);

        // Save decision
        MemberProfileApproval::updateOrCreate(
            ['member_id' => $this->member_Id],
            [
                'is_approved' => $this->isApproved,
                'message' => $this->approvalMessage,
            ]
        );
        $member = Member::findOrFail($this->member_Id);

        // Notify the member
        $member->user->notify(new MemberProfileApprovalNotification(
            $member,
            (bool)$this->isApproved,
            $this->approvalMessage
        ));

        $this->dispatch('notify', title: 'Success', message: 'Decision saved and member notified.', type: 'success');
        $this->dispatch('reviewed');
        $this->approvalModalOpen = false;
        $this->reset(['isApproved', 'approvalMessage']);
    }

    //request management
    public function handleApprovalSelection($value)
    {
        // dd($value);
        $this->requestApproved = $value;
        $this->showRejectionReasonField = $value === '0';
    }

    public function openManageRequestModal()
    {
        $this->resetErrorBag();
        $this->reset(['requestApproved', 'requestRejectionReason']);
        $this->manageRequestModalOpen = true;
    }

    public function closeManageRequestModal()
    {
        $this->manageRequestModalOpen = false;
    }
    public function submitRequestDecision()
    {
        $this->validate([
            'requestApproved' => 'required|in:1,0',
            'requestRejectionReason' => 'required_if:requestApproved,0|string|max:1000',
        ]);

        $member = Member::findOrFail($this->member_Id);
        // dd($member);
        // Update request status (if you track it in DB — optional)
        $changeRequest = $member->changeRequest;
        if ($changeRequest) {
            $changeRequest->update([
                'request_approved' => $this->requestApproved,
            ]);
        }

        // Notify the member
        $member->user->notify(new MemberChangeRequestResponse(
            $member,
            (bool) $this->requestApproved,
            $this->requestRejectionReason
        ));

        $this->dispatch('notify', title: 'Success', message: 'Request decision submitted.', type: 'success');
        $this->dispatch('managerequest');

        $this->reset(['manageRequestModalOpen', 'requestApproved', 'requestRejectionReason']);
    }
    //request management


    public function mount($member_Id = null)
    {
        $this->member_Id = $member_Id;
        if (!auth()->user()->hasRole('Admin')) {
            $member = Auth::user()->member;
        } else {
            // Admin is editing someone else’s profile — assume member_id is passed to the component
            $member = Member::findOrFail($this->member_Id);
        }

        // (bool)$this->profile_approved = $member->user->profile_approved ?? false;
        $this->profileApproved = optional($member->profileApproval)->is_approved ?? false;
        $this->requestApproval = optional($member->changeRequest)->request_approved ?? true;
        $this->haveRequests = $member->changeRequest()->exists();
        $this->changeRequest = $member->changeRequest->message ?? null;

        $this->profile_submitted = optional($member)->profile_submitted ?? false;
        
        $this->title = $member->title ?? '';
        $this->dob = optional($member->dob)->format('Y-m-d') ?? '';
        $this->phone_number = $member->phone_number ?? '';
        $this->qualifications = $member->qualifications ?? [];
        $this->certifications = json_encode($member->certifications ?? []);
        $this->experience = json_decode($member->experience ?? '[]', true);
        $this->specialities = $member->specialities ?? [];
        $this->bio = $member->bio ?? '';
        $this->location = $member->location ?? '';
        $this->socialLinks = $member->social_links
        ? collect(json_decode($member->social_links, true))
            ->map(function ($url, $platform) {
                return ['platform' => $platform, 'url' => $url];
            })->values()->toArray()
        : [];
        $this->certifications = $member->certifications
            ? collect(json_decode($member->certifications, true))->toArray()
            : [];

        $this->availability = $member->availability ?? $this->availability;


        // Store existing file paths separately
        $this->existing_cnic_copy = $member->cnic_copy ?? null;
        $this->existing_pmdc_licence_copy = $member->pmdc_licence_copy ?? null;
        $this->existing_fcps_degree_copy = $member->fcps_degree_copy ?? null;
        $this->testimonials = $member->testimonials()->get()
        ->map(function ($testimonial) {
            return [
                'id' => $testimonial->id,
                'patient_name' => $testimonial->patient_name,
                'patient_image' => $testimonial->patient_image,
                'feedback' => $testimonial->feedback,
            ];
        })->toArray();
    }

    

    public function save()
    {
        if (!auth()->user()->hasRole('Admin') && $this->profileApproved || $this->haveRequests) {
            return;
        }
        $this->validate();
        if (!auth()->user()->hasRole('Admin')) {
            $user = auth()->user();
            $member = $user->member;
        } else {
            // For Admin, assume you already have $this->member set in mount() or passed from route
            $member = Member::findOrFail($this->member_Id); // Ensure you pass or set $this->member_id properly
            $user = $member->user; // Get related user
        }

        $memberId = $member->id;

        $formattedLinks = collect($this->socialLinks)
        ->pluck('url', 'platform') // platform => url
        ->toJson();


        $folder = "member/{$memberId}";
        Storage::disk('member')->makeDirectory($folder);

        $certificationfolder = "member/{$memberId}/certifications";
        Storage::disk('member')->makeDirectory($folder);

        $testimonialImageFolder = "member/{$memberId}/testimonial_images";
        Storage::disk('member')->makeDirectory($testimonialImageFolder);
    
        $certificationsToStore = [];
    
        foreach ($this->certifications as $cert) {
            // Check if new image is uploaded
            if (isset($cert['image']) && is_object($cert['image'])) {
                // Save image to the member disk in certification folder
                $imagePath = $cert['image']->store($certificationfolder, 'member');
            } else {
                // Use existing image path
                $imagePath = $cert['image'] ?? null;
            }
    
            $certificationsToStore[] = [
                'name' => $cert['name'],
                'image' => $imagePath,
            ];
        }

        
        $data = [
            'title' => $this->title,
            'dob' => $this->dob,
            'phone_number' => $this->phone_number,
            'certifications' => json_encode($certificationsToStore),
            'experience' => json_encode($this->experience),
            'qualifications' => $this->qualifications,
            'specialities' => $this->specialities,
            'bio' => $this->bio,
            'location' => $this->location,
            'social_links' => $formattedLinks,
            'availability' => $this->availability,
            'cnic_copy' => $this->cnic_copy
                ? $this->cnic_copy->store($folder, 'member')
                : $this->existing_cnic_copy,
            'pmdc_licence_copy' => $this->pmdc_licence_copy
                ? $this->pmdc_licence_copy->store($folder, 'member')
                : $this->existing_pmdc_licence_copy,
            'fcps_degree_copy' => $this->fcps_degree_copy
                ? $this->fcps_degree_copy->store($folder, 'member')
                : $this->existing_fcps_degree_copy,
            'profile_submitted' => true,
        ];

        $member = $user->member()->updateOrCreate(['user_id' => $user->id], $data);

        //testimonials
        foreach ($this->testimonials as $testimonialData) {
            $imagePath = null;
        
            if (isset($testimonialData['patient_image']) && is_object($testimonialData['patient_image'])) {
                $imagePath = $testimonialData['patient_image']->store($testimonialImageFolder, 'member');
            } elseif (!empty($testimonialData['patient_image'])) {
                $imagePath = $testimonialData['patient_image'];
            }
        
            Testimonial::updateOrCreate(
                [
                    'id' => $testimonialData['id'] ?? null,
                ],
                [
                    'member_id' => $memberId,
                    'patient_name' => $testimonialData['patient_name'],
                    'patient_image' => $imagePath,
                    'feedback' => $testimonialData['feedback'],
                ]
            );
        }
        $this->dispatch('saved');
        if(!auth()->user()->hasRole('Admin')){
            $admins = User::role('Admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new ProfileSubmittedForApproval($member));
            }
        }
    }



    public function render()
    {
        return view('livewire.profile.member-details-form');
    }
}
