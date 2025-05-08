<?php

namespace App\Livewire\Profile;

use App\Models\Member;
use App\Models\Qualification;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

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
    public $selectedQualifications = [];
    public $allQualifications = [];    
    public $certifications = []; // Each item: ['name' => '', 'image' => null]
    public $newCertification = ['name' => '', 'image' => null];
    public $tempImage;
    public $experience = [];
    public $specialities = [];
    public $testimonials = [];
    public $bio = '';
    public $location = '';
    public $socialLinks = [];
    public $availability = [
        'monday' => ['open' => '', 'close' => ''],
        'tuesday' => ['open' => '', 'close' => ''],
        'wednesday' => ['open' => '', 'close' => ''],
        'thursday' => ['open' => '', 'close' => ''],
        'friday' => ['open' => '', 'close' => ''],
        'saturday' => ['open' => '', 'close' => ''],
        'sunday' => ['open' => '', 'close' => ''],
    ];
    
    public $existing_cnic_copy;
    public $existing_pmdc_licence_copy;
    public $existing_fcps_degree_copy;
    
    protected $listeners = ['updateSelectedQualifications'];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone_number' => 'required|string|max:20',
            'cnic_copy' => [$this->existing_cnic_copy ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'pmdc_licence_copy' => [$this->existing_pmdc_licence_copy ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'fcps_degree_copy' => [$this->existing_fcps_degree_copy ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            // 'qualifications' => 'required|string',
            // 'certifications' => 'required|string',
            // 'experience' => 'required|string',
            // 'specialities' => 'required|string',
            'bio' => 'required|string',
            'location' => 'required|string|max:255',
            // 'social_links' => 'required|string',
            // 'availability' => 'required|string',
        ];
    }


    public function updateSelectedQualifications($values)
    {
        $this->selectedQualifications = $values;
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
                $propertyName => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
        $this->experience[] = ['hospital' => '', 'from' => '', 'to' => ''];
    }


    public function removeExperience($index)
    {
        unset($this->experience[$index]);
        $this->experience = array_values($this->experience); // Reindex
    }

    public function mount($member_Id = null)
    {
        $this->member_Id = $member_Id; 
        if (!auth()->user()->hasRole('Admin')) {
            $member = Auth::user()->member;
        } else {
            // Admin is editing someone else’s profile — assume member_id is passed to the component
            $member = Member::findOrFail($this->member_Id);
            $user = $member->user;
            // Regular user editing their own profile
        }
        

        $this->title = $member->title ?? '';
        $this->dob = optional($member->dob)->format('Y-m-d') ?? '';
        $this->phone_number = $member->phone_number ?? '';

        $this->selectedQualifications = $member->qualifications()->pluck('name')->toArray();
        $this->allQualifications = Qualification::all();

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
        ];

        $user->member()->updateOrCreate(['user_id' => $user->id], $data);
        $member->updateOrCreate(['user_id' => $user->id], $data);

        // Convert qualification names to IDs, creating any that don't exist
        $qualificationIds = collect($this->selectedQualifications)->map(function ($name) {
            return Qualification::firstOrCreate(['name' => $name])->id;
        });

        // Sync qualifications
        $member->qualifications()->sync($qualificationIds);
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
    }



    public function render()
    {
        return view('livewire.profile.member-details-form');
    }
}
