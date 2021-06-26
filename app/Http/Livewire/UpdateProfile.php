<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Dislike;
use App\Models\Hobby;
use App\Models\School;
use App\Models\Town;
use App\Rules\ModelsExist;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithImageManipulation;

class UpdateProfile extends Component
{
    use WithImageManipulation;

    public $avatar;
    public $cover_photo;
    public string $bio;
    public array $hobbies;
    public array $dislikes;
    public Collection $schools;
    public Collection $courses;
    public $towns;
    public array $course_levels = [];
    public array $selectedHobbies;
    public array $selectedDislikes;
    public $selectedSchool = NULL;
    public $selectedCourseLevel = NULL;
    public array $selectedTowns = [];
    public $selectedCourse = NULL;
    public $minAllowedBudget = 40000;
    public $maxAllowedBudget = 300000;
    public $budgetRange = [];
    public $max_budget = NULL;
    public $min_budget = NULL;
    public $rooms = '';

    protected $listeners = ['avatarUpload' => 'handleAvatarUpload', 'coverUpload' => 'handleCoverUpload'];

    public function mount()
    {
        $this->avatar = auth()->user()->avatar ?? '';
        $this->cover_photo = auth()->user()->cover_photo ?? '';

        $this->rooms = auth()->user()->rooms ?? '';

        $this->bio = auth()->user()->bio ?? '';

        $this->budgetRange = range($this->minAllowedBudget, $this->maxAllowedBudget, 20000);
        $this->max_budget = auth()->user()->max_budget ?? '';
        $this->min_budget = auth()->user()->min_budget ?? '';

        $this->hobbies = $this->getHobbies();
        $this->selectedHobbies =  $this->getUserData('hobbies');

        $this->dislikes = $this->getDislikes();
        $this->selectedDislikes = $this->getUserData('dislikes');

        $this->towns = auth()->user()->school ? auth()->user()->school->towns : [];
        $this->selectedTowns =  $this->getUserData('towns');

        $this->schools = $this->getSchools();
        $this->selectedSchool = auth()->user()->school;

        $this->courses = $this->getCourses();
        $this->selectedCourse = auth()->user()->course;

        $this->course_levels = $this->getCourseLevels(auth()->user()->course);
        $this->selectedCourseLevel = auth()->user()->course_level;
    }

    public function handleAvatarUpload($avatarImage)
    {
        $this->avatar = $avatarImage;
        $this->validateOnly('avatar');
    }

    public function handleCoverUpload($coverImage)
    {
        $this->cover_photo = $coverImage;
        $this->validateOnly('cover_photo');
    }

    public function getCourses(): Collection
    {
        $school = auth()->user()->school;
        if ($school) {
            return $school->courses;
        }

        return collect([]);
    }

    public function getDislikes(): array
    {
        return Dislike::orderBy('name')->get()->toArray();
    }

    public function getHobbies(): array
    {
        return Hobby::orderBy('name')->get()->toArray();
    }

    public function getSchools(): Collection
    {
        return School::orderBy('name')->get();
    }

    public function getUserData(string $columnName): array
    {
        if (auth()->user()->{$columnName}) {
            $options = auth()->user()->{$columnName}()->pluck('id')->toArray();
            $options = array_map(function ($item) {
                return strval($item);
            }, $options);
            return $options;
        }

        return [];
    }

    public function getCourseLevels(Course $course = NULL): array
    {
        if ($course) {
            return range(100, $course->max_level + 100, 100);
        }

        return [];
    }

    public function selectedSchoolChange(School $school): void
    {
        $this->selectedSchool = $school;
        $this->selectedCourse = NULL;
        $this->selectedCourseLevel = NULL;
        $this->selectedTowns = [];
        $this->course_levels = [];
        $this->courses = $school->courses;
        $this->towns = $school->towns;
    }

    public function selectedCourseChange(Course $course): void
    {
        $this->selectedCourse = $course;
        $this->selectedCourseLevel = NULL;
        $this->course_levels = $this->getCourseLevels($course);
    }

    public function rules()
    {
        $validationRules = [
            'selectedSchool' => ['required',],
            'selectedCourse' => ['required',],
            'selectedCourseLevel' => ['required', 'in_array:course_levels.*'],
            'selectedHobbies' => ['required', 'array', 'min:1', new ModelsExist(Hobby::class)],
            'selectedDislikes' => ['required', 'array', 'min:1', new ModelsExist(Dislike::class)],
            'selectedTowns' => ['required', 'array', 'min:1', new ModelsExist(Town::class)],
            'selectedTowns.*' => ['required', 'numeric', 'distinct'],
            'selectedHobbies.*' => ['required', 'numeric', 'distinct'],
            'selectedDislikes.*' => ['required', 'numeric', 'distinct'],
            'min_budget' => ['required', 'integer', 'numeric', 'gte:minAllowedBudget', 'in_array:budgetRange.*'],
            'max_budget' => ['required', 'gt:min_budget', 'integer', 'numeric', 'lte:maxAllowedBudget', 'in_array:budgetRange.*'],
            'bio' => ['required', 'string', 'max:255', 'min:25'],
            'rooms' => ['required', Rule::in(['1', '2', '3', '4'])],
        ];

        $cover_photo_validation_rules = [
            'cover_photo' => [
                'required', 'base64image', 'base64mimes:jpeg,png,jpg', 'base64between:100,5098',
                'base64dimensions:min_height=100,max_height=450,ratio=1.5'
            ],
        ];

        $avatar_validation_rules = [
            'avatar' => [
                'required', 'base64image', 'base64mimes:jpeg,png,jpg', 'base64between:20,4098',
                'base64dimensions:min_height=100,max_height=450,ratio=1'
            ],
        ];

        $user = auth()->user();

        if ($user->avatar === $this->avatar && $user->cover_photo === $this->cover_photo) {
            return $validationRules;
        } else if ($user->avatar !== $this->avatar && $user->cover_photo !== $this->cover_photo) {
            return array_merge($validationRules, $avatar_validation_rules, $cover_photo_validation_rules);
        } else if ($user->avatar === $this->avatar && $user->cover_photo !== $this->cover_photo) {
            return array_merge($validationRules, $cover_photo_validation_rules);
        } else {
            return array_merge($validationRules, $avatar_validation_rules);
        }
    }

    protected array $messages = [
        'max_budget.gt' => 'The :attribute must be greater than the minimum budget',
        'bio.max' => 'Your Bio must be at most 255 characters long',
        'bio.min' => 'Your Bio must be at least 25 characters long',
        'selectedSchool.required' => 'Choose your institute of study',
        'selectedDislikes.required' => 'Choose at least one dislike',
        'selectedHobbies.required' => 'Choose at least one hobby',
        'selectedTowns.required' => 'Choose at least one town (property location)',
        'cover_photo.required' => 'Please upload a cover photo',
        'avatar.required' => 'Please upload an avatar photo',
    ];

    protected array $validationAttributes = [
        'selectedSchool' => 'School',
        'selectedDislikes' => 'Dislikes',
        'selectedHobbies' => 'Hobbies',
        'selectedTowns' => 'towns',
        'cover_photo' => 'Cover Photo',
        'min_budget' => 'minimum budget',
        'max_budget' => 'maximum budget',
        'rooms' => 'Number of rooms',
        'bio' => 'Your Bio',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if (!$this->schools->contains($this->selectedSchool)) {
            return;
        }
        $user = auth()->user();
        $userCover = $user->cover_photo;
        $userAvatar = $user->avatar;
        $coverName = "";
        $avatarName = "";

        //validate inputs
        $this->validate();

        // handle image conversion, naming and storage
        if ($userAvatar !== $this->avatar) {
            $avatarName = $this->storeImage($this->avatar, 'avatars');
        }
        if ($userCover !== $this->cover_photo) {
            $coverName = $this->storeImage($this->cover_photo, 'cover_photos');
        }

        //store data using db transactions
        //toggle profile_updated field in the users table
        DB::beginTransaction();
        try {
            $user->bio = $this->bio;
            $user->school()->associate($this->selectedSchool);
            $user->course()->associate($this->selectedCourse);
            $user->hobbies()->sync($this->selectedHobbies);
            $user->dislikes()->sync($this->selectedDislikes);
            $user->towns()->sync($this->selectedTowns);
            $user->course_level = intval($this->selectedCourseLevel);
            $user->rooms = $this->rooms;
            $user->min_budget = intval($this->min_budget);
            $user->max_budget = intval($this->max_budget);

            if (($user->avatar !== $this->avatar) && $avatarName !== "") {
                $user->avatar = $avatarName;
            }
            if (($user->cover_photo !== $this->cover_photo) && $coverName !== "") {
                $user->cover_photo = $coverName;
            }

            $user->profile_updated = true;

            $user->save();
            DB::commit();

            if ($userAvatar !== $user->avatar) {
                Storage::disk('avatars')->delete($userAvatar);
            }
            if ($userCover !== $user->cover_photo) {
                Storage::disk('cover_photos')->delete($userCover);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Storage::disk('avatars')->delete($avatarName);
            Storage::disk('cover_photos')->delete($coverName);
            $this->addError('profileUpdate', 'An error occurred while updating your profile please try again');
            return;
        }

        //redirect to dashboard
        $this->redirect(route('profile.view', ['user' => auth()->user() ]));
    }

    public function render()
    {
        return view('livewire.update-profile')->layout('layouts.guest');
    }
}
