<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Http\Livewire\Components\Filament\Forms\Multiselect as FormsMultiselect;
use App\Http\Livewire\Traits\WithImageManipulation;
use App\Models\Course;
use App\Models\Dislike;
use App\Models\Hobby;
use App\Models\School;
use App\Models\Town;
use App\Rules\ModelsExist;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class UpdateProfile extends Component implements HasForms
{
    use WithImageManipulation, WithFileUploads, InteractsWithForms;

    public $avatar;
    public $cover_photo;
    public $bio;
    public $hobbies;
    public $dislikes;
    public $school;
    public $course;
    public $towns;
    public $course_level;
    public $minAllowedBudget = 40000;
    public $maxAllowedBudget = 300000;
    public $max_budget = NULL;
    public $min_budget = NULL;
    public $rooms = '';

    protected $listeners = ['avatarUpload' => 'handleAvatarUpload', 'coverUpload' => 'handleCoverUpload'];

    protected function getFormModel()
    {
        return Auth::user();
    }

    public function mount()
    {
        $this->form->fill([
            'avatar' => auth()->user()->avatar ?? '',
            'cover_photo' => auth()->user()->cover_photo ?? '',
            'rooms' => auth()->user()->rooms ?? '',
            'bio' => auth()->user()->bio ?? '',
            'max_budget' => auth()->user()->max_budget ?? '',
            'min_budget' => auth()->user()->min_budget ?? '',
            'hobbies' => auth()->user()->hobbies->pluck('id')->toArray(),
            'dislikes' => auth()->user()->dislikes->pluck('id')->toArray(),
            'towns' =>  auth()->user()->towns->pluck('id')->toArray(),
            'school' => auth()->user()->school->id,
            'course' => auth()->user()->course->id,
            'course_level' =>  auth()->user()->course_level,
        ]);

        // \dd($this->hobbies, $this->dislikes);
        // \dd(
        //     Hobby::all()->pluck('name', 'id')->toArray(), 
        //     Dislike::all()->pluck('name', 'id')->toArray(),
        //     auth()->user()->hobbies->pluck('name', 'id')->toArray(),
        //     auth()->user()->dislikes->pluck('name', 'id')->toArray(),
        //     Hobby::where('name', 'like', "%g%")->limit(50)->pluck('name', 'id')->toArray(),
        //     Dislike::where('name', 'like', "%i%")->limit(50)->pluck('name', 'id')->toArray()
        // );
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

    public function getCourseLevels(Course $course = NULL): array
    {
        if ($course) {
            $result = [];

            foreach ($levels = collect(range(100, $course->max_level + 100, 100)) as $value) {
                if ($levels->first() == $value) {
                    $result[$value] = 'Pre-Degree and Fresher';
                } elseif ($levels->last() == $value) {
                    $result[$value] = 'Post Graduate';
                } else {
                    $result[$value] = $value . ' Level';
                }
            }

            return $result;
        }

        return [];
    }

    public function rules()
    {
        //upgrade validation rules for schools, course etc
        $validationRules = [
            'selectedSchool' => ['required', 'exists:schools,id'],
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
            'bio' => ['required', 'string', 'max:255', 'min:15'],
            'rooms' => ['required', Rule::in(['1', '2', '3', '4', '5'])],
        ];

        $cover_photo_validation_rules = [
            'cover_photo' => [
                'required', 'image', 'mimes:jpeg,png,jpg', 'between:50,5098',
                'dimensions:min_height=100,max_height=450,ratio=1.5'
            ],
        ];

        $avatar_validation_rules = [
            'avatar' => [
                'required', 'image', 'mimes:jpeg,png,jpg', 'between:10,5098',
                'dimensions:min_height=100,max_height=450'
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
        'bio.required' => 'Your Bio is required',
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
        'bio' => 'Bio',
    ];

    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    protected function getFormSchema(): array
    {
        return [
            ViewField::make('avatar')->view('livewire.components.filament.forms.avatar-upload')->disableLabel(true),

            ViewField::make('cover_photo')->view('livewire.components.filament.forms.cover-photo-upload'),

            Section::make('Personal Information')
                ->description('These are Information that describe who you are.')
                ->columns(2)
                ->schema([
                    Textarea::make('bio')->label('About')->columnSpan(2)->required(),

                    FormsMultiselect::make('hobbies')
                        ->label('Hobbies')
                        ->options(Hobby::select('id', 'name')->orderBy('name')->get()->toArray())
                        ->required(),

                    FormsMultiselect::make('dislikes')
                        ->label('Dislikes')
                        ->options(Dislike::select('id', 'name')->orderBy('name')->get()->toArray())
                        ->required()
                ])->collapsible(),

            Section::make('Educational Information')
                ->description('These are Information about your current Educational arrangement.')
                ->columns(2)
                ->schema([
                    Select::make('school')
                        ->label('Institute of Study')
                        ->reactive()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $searchQuery) => School::where('name', 'like', "%{$searchQuery}%")->limit(50)->pluck('name', 'id')->toArray())
                        ->getOptionLabelUsing(fn ($value): ?string => School::find($value)?->name)
                        ->options(School::all()->pluck('name', 'id')->toArray())
                        ->afterStateUpdated(function (callable $set)
                        {
                            $set('course', null);
                            $set('course_level', null);
                            $set('towns', []);
                        })
                        ->columnSpan(2)
                        ->rules([
                            Rule::in(School::all('id')->pluck('id')->toArray()),
                            'required'
                        ]),

                    Select::make('course')
                        ->label('Course of Study')
                        ->reactive()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $searchQuery, callable $get) => School::find($get('school'))->courses()->where('name', 'like', "%{$searchQuery}%")->limit(50)->pluck('courses.name', 'courses.id')->toArray())
                        ->getOptionLabelUsing(fn ($value): ?string => Course::find($value)?->name)
                        ->options(function (callable $get) {
                            $school =  School::find($get('school'));

                            if (!$school) {
                                return [];
                            }
                            
                            return $school->courses->pluck('name', 'id')->toArray();
                        })
                        ->afterStateUpdated(function (callable $set)
                        {
                            $set('course_level', null);
                        })
                        ->required(),

                    Select::make('course_level')
                        ->label('Course Level')
                        ->options(function (callable $get) {
                            $course = Course::find($get('course'));

                            if (!$course) {
                                return [];
                            }

                            return $this->getCourseLevels($course);
                        })
                        ->required(),
                ])->collapsible(),

            Section::make('Apartment Information')
                ->description('These are Information that describe your preferred apartment type and location.')
                ->columns(2)
                ->schema([
                    FormsMultiselect::make('towns')
                        ->label('Preferred property locations')
                        ->options(function (callable $get) {
                            $school =  School::find($get('school'));

                            if (!$school) {
                                return [];
                            }

                            return $school->towns()->select('id', 'name')->orderBy('name')->get()->toArray();
                        })
                        ->required(),

                    // MultiSelect::make('towns')
                    // ->label('Preferred property locations')
                    // ->options(function (callable $get) {
                    //     $school =  School::find($get('school'));

                    //     if (!$school) {
                    //         return [];
                    //     }

                    //     return $school->towns->pluck('name', 'id')->toArray();
                    // })
                    // ->required(),

                    Select::make('rooms')
                        ->label('Number of Rooms')
                        ->options([
                            '1' => 'Self-contain',
                            '2' => 'One-bedroom Self-contain',
                            '3' => 'Two-bedroom self-contain',
                            '4' => 'Three-bedroom self-contain',
                            '5' => 'others',
                        ])->required(),

                    Select::make('min_budget')
                        ->label('Minimum Budget')
                        ->options(range($this->minAllowedBudget, $this->maxAllowedBudget, env('BUDGET_PRICE_STEP', 20000)))
                        ->required(),

                    Select::make('max_budget')
                        ->label('Maximum Budget')
                        ->options(range($this->minAllowedBudget, $this->maxAllowedBudget, env('BUDGET_PRICE_STEP', 20000)))
                        ->required(),
                ])->collapsible(),
        ];
    }

    public function save()
    {
        if (!$this->schools->contains($this->selectedSchool)) {
            return;
        }

        $this->validate();

        $user = auth()->user();

        $userCover = $user->cover_photo;

        $userAvatar = $user->avatar;

        // handle image conversion, naming and storage
        if ($userAvatar !== $this->avatar) {
            $userAvatar = $this->storeImage($this->avatar, 'avatars\\' . $user->id);
        }
        if ($userCover !== $this->cover_photo) {
            $userCover = $this->storeImage($this->cover_photo, 'cover_photos\\' . $user->id);
        }

        //store data using db transactions
        //toggle profile_updated field in the users table
        if ($userAvatar && $userCover) {
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
                $user->avatar = $userAvatar;
                $user->cover_photo = $userCover;
                $user->profile_updated = true;

                $user->save();

                DB::commit();


                //redirect to dashboard
                $this->redirect(route('profile.view', ['user' => auth()->user()]));
            } catch (\Throwable $th) {
                DB::rollBack();

                $this->addError('profileUpdate', 'An error occurred while updating your profile please try again');

                return;
            }
        } else {
            $this->addError('profileUpdate', 'An error occurred while updating your profile please try again');
        }
    }

    public function render()
    {
        return view('livewire.pages.profile.update-profile')->layout('layouts.guest');
    }
}
