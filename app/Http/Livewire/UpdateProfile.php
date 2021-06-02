<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Dislike;
use App\Models\Hobby;
use App\Models\School;
use App\Models\Town;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfile extends Component
{
    use WithFileUploads;


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
    public $selectedTowns = NULL;
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

        $this->budgetRange = range($this->minAllowedBudget, $this->maxAllowedBudget, 20000);
        $this->hobbies = $this->getHobbies();
        $this->dislikes = $this->getDislikes();
        $this->schools = $this->getSchools();
        $this->courses = $this->getCourses();
        $this->towns = auth()->user()->school ? auth()->user()->school->towns : collect([]);
        $this->bio = auth()->user()->bio ?? '';
        $this->course_levels = $this->getCourseLevels(auth()->user()->course);
        $this->selectedDislikes = $this->getUserDislikes();
        $this->selectedHobbies = $this->getUserHobbies();
        $this->selectedCourse = auth()->user()->course;
        $this->selectedCourseLevel = auth()->user()->course_level;
        $this->selectedSchool = auth()->user()->school;
        $this->selectedTowns = auth()->user()->towns ?? collect([]);
        $this->max_budget = auth()->user()->max_budget ?? '';
        $this->min_budget = auth()->user()->min_budget ?? '';

        // $this->min_budget = old('min_budget', 200000);
    }

    public function handleAvatarUpload($avatarImage)
    {
        $this->avatar = $avatarImage;
    }

    public function handleCoverUpload($coverImage)
    {
        $this->cover_photo = $coverImage;
    }

    public function pop_dislike($id)
    {
        if (in_array($id, $this->selectedDislikes)) {
            $this->selectedDislikes = array_filter($this->selectedDislikes, function ($i) use ($id) {
                return $i != $id;
            });
        }
    }

    public function pop_hobby($id)
    {
        if (in_array($id, $this->selectedHobbies)) {
            $this->selectedHobbies = array_filter($this->selectedHobbies, function ($i) use ($id) {
                return $i != $id;
            });
        }
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

    public function getUserHobbies(): array
    {
        if (auth()->user()->hobbies) {
            $hobbies = auth()->user()->hobbies()->pluck('id')->toArray();
            $hobbies = array_map(function ($item) {
                return strval($item);
            }, $hobbies);
            return $hobbies;
        }

        return [];
    }

    public function getUserDislikes(): array
    {
        if (auth()->user()->dislikes) {
            $dislikes = auth()->user()->dislikes()->pluck('id')->toArray();
            $dislikes = array_map(function ($item) {
                return strval($item);
            }, $dislikes);
            return $dislikes;
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

        // if (!$this->schools->contains($school)) {
        //    return;
        // }

        $this->selectedSchool = $school;
        $this->selectedCourse = NULL;
        $this->selectedCourseLevel = NULL;
        $this->selectedTowns = collect([]);
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

    public function selectedTownsChange(Town $town): void
    {
        if ($this->towns->contains($town->id) && !$this->selectedTowns->contains($town->id)) {
            $this->selectedTowns->push($town);
            return;
        }
    }

    public function minBudgetChange($value): void
    {
        $this->min_budget = intval($value);
    }

    public function maxBudgetChange($value): void
    {
        $this->max_budget = intval($value);
    }

    protected function rules()
    {
        return [
            'selectedSchool' => ['required'],
            'selectedTowns' => ['required'],
            'min_budget' => ['required', 'integer', 'numeric', 'gte:minAllowedBudget', 'in_array:budgetRange.*'],
            'max_budget' => ['required', 'gt:min_budget', 'integer', 'numeric', 'lte:maxAllowedBudget', 'in_array:budgetRange.*'],
            'bio' => ['required', 'string', 'max:255', 'min:25'],
            'rooms' => ['required', Rule::in(['1', '2', '3', '4'])],
            'avatar' => [
                'required', 'base64image', 'base64mimes:jpeg,png,jpg', 'base64between:100,4098',
                'base64dimensions:min_height=100,max_height=450,ratio=1'
            ],
            'cover_photo' => [
                'required', 'base64image', 'base64mimes:jpeg,png,jpg', 'base64between:100,4098',
                'base64dimensions:min_height=100,max_height=450,ratio=1.5'
            ],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        if (!$this->schools->contains($this->selectedSchool)) {
            return;
        }        

        $this->validate();

        dd($this->avatar);
    }

    public function render()
    {
        return view('livewire.update-profile')->layout('layouts.guest');
    }
}
