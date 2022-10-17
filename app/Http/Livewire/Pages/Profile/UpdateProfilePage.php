<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Models\User;
use App\Models\Hobby;
use App\Models\School;
use App\Models\Course;
use App\Models\Dislike;
use Livewire\Component;
use App\Enums\BudgetLimit;
use App\Enums\ApartmentRooms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Validation\Rules\Exists;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use MartinRo\FilamentCharcountField\Components\CharcountedTextarea;
use MartinRo\FilamentCharcountField\Components\CharcountedTextInput;
use App\Http\Livewire\Components\Filament\Forms\PhotoUpload as PhotoUpload;
use Illuminate\Support\Facades\Storage;

class UpdateProfilePage extends Component implements HasForms
{
    use InteractsWithForms;

    public $cover_image;
    public $avatar_image;
    public $bio;
    public $hobbies;
    public $dislikes;
    public $school;
    public $course;
    public $course_level;
    public $towns;
    public $rooms = '';
    public $max_budget = NULL;
    public $min_budget = NULL;

    public function mount()
    {
        $user = $this->getFormModel();

        $this->form->fill([
            'avatar_image' => $user->avatar,
            'cover_image' => $user->cover_photo,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'rooms' => $user->rooms ?? '',
            'bio' => $user->bio ?? '',
            'max_budget' => $user->max_budget ?? '',
            'min_budget' => $user->min_budget ?? '',
            'hobbies' => $user->hobbies->pluck('id')->toArray(),
            'dislikes' => $user->dislikes->pluck('id')->toArray(),
            'towns' =>  $user->towns->pluck('id')->toArray(),
            'school' => $user->school_id,
            'course' => $user->course_id,
            'course_level' =>  $user->course_level,
        ]);
    }

    protected function getFormModel(): ?User
    {
        return Auth::user();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('General Information')
                ->collapsible()
                ->schema([
                    PhotoUpload::make('avatar_image')
                        ->label('Avatar Photo')
                        ->avatar()
                        ->disk('avatars')
                        ->imageResizeTargetWidth(320)
                        ->getPreviewImageUrlUsing($this->getFormModel()->avatar_path)
                        ->directory(fn () => (string) auth()->id())
                        ->getUploadedFileNameForStorageUsing(function (): string {
                            return (string) str()->uuid()->prepend('avatar-photo-', md5(strval(auth()->user()->id)), '-');
                        })
                        ->required()
                        ->rules(['between:10,5098', 'dimensions:max_height=322'])
                        ->columnSpan([
                            'default' => 'full',
                            'sm' => 1,
                            'md' => 1,
                            'lg' => 2,
                        ]),

                    PhotoUpload::make('cover_image')
                        ->label('Cover Photo')
                        ->image()
                        ->disk('cover_photos')
                        ->directory(fn () => (string) auth()->id())
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth(640)
                        ->getPreviewImageUrlUsing($this->getFormModel()->cover_photo_path)
                        ->getUploadedFileNameForStorageUsing(function (): string {
                            return (string) str()->uuid()->prepend('cover-photo-', md5(strval(auth()->user()->id)), '-');
                        })
                        ->required()
                        ->rules(['between:10,6098'])
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 2,
                            'md' => 3,
                            'lg' => 6,
                        ]),

                    CharcountedTextInput::make('first_name')
                        ->label('First Name')
                        ->minCharacters(2)
                        ->maxCharacters(160)
                        ->rules(['string', 'max:160', 'min:2'])
                        ->required()
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 4,
                        ]),

                    CharcountedTextInput::make('last_name')
                        ->label('Last Name')
                        ->minCharacters(2)
                        ->maxCharacters(160)
                        ->rules(['string', 'max:160', 'min:2'])
                        ->required()
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 4,
                        ]),

                    Placeholder::make('Email')->extraAttributes(['class' => 'text-lg font-semibold capitalize'])
                        ->content(auth()->user()->email)
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 4,
                        ]),

                    Placeholder::make('Gender')->extraAttributes(['class' => 'text-lg font-semibold capitalize'])
                        ->content(auth()->user()->gender)
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 4,
                        ]),
                ])
                ->columns([
                    'default' => 2,
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 8,
                ]),

            Section::make('Personal Information')
                ->description('These are Information that describe who you are.')
                ->columns(2)
                ->schema([
                    CharcountedTextarea::make('bio')
                        ->label('About')
                        ->minCharacters(15)
                        ->maxCharacters(255)
                        ->rules(['string', 'max:255', 'min:15'])
                        ->required()
                        ->columnSpan(2)
                        ->rows(4),

                    Select::make('hobbies')
                        ->multiple()
                        ->label('Hobbies')
                        ->placeholder('Please select your hobbies')
                        ->options(Hobby::all('id', 'name')->pluck('name', 'id')->toArray())
                        ->required()
                        ->exists('hobbies', 'id')
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 1,
                            'md' => 1,
                        ]),

                    Select::make('dislikes')
                        ->multiple()
                        ->label('Dislikes')
                        ->placeholder('Please select your dislikes')
                        ->options(Dislike::all('id', 'name')->pluck('name', 'id')->toArray())
                        ->required()
                        ->exists('dislikes', 'id')
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 1,
                            'md' => 1,
                        ])
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
                        ->afterStateUpdated(function (callable $set) {
                            $set('course', null);
                            $set('course_level', null);
                            $set('towns', null);
                        })
                        ->exists('schools', 'id')
                        ->required()
                        ->columnSpan(2),

                    Select::make('course')
                        ->label('Course of Study')
                        ->placeholder('Please select your course of study')
                        ->reactive()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $searchQuery, callable $get) => School::find($get('school'))->courses()->where('name', 'like', "%{$searchQuery}%")->limit(50)->pluck('courses.name', 'courses.id')->toArray())
                        ->getOptionLabelUsing(fn ($value): ?string => Course::find($value)?->name)
                        ->options(fn (callable $get) => School::find($get('school'))?->courses->pluck('name', 'id')->toArray() ?? [])
                        ->afterStateUpdated(fn (callable $set) => $set('course_level', null))
                        ->required()
                        ->exists('course_school', 'course_id', fn (Exists $rule, callable $get) => $rule->where('school_id', $get('school')))
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 1,
                            'md' => 1,
                        ]),

                    Select::make('course_level')
                        ->label('Course Level')
                        ->reactive()
                        ->options(fn (callable $get) => Course::getCourseLevels(Course::find($get('course'))))
                        ->in(fn (callable $get) => Course::find($get('course'))?->levels ?? [])
                        ->required()
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 1,
                            'md' => 1,
                        ]),
                ])->collapsible(),

            Section::make('Apartment Information')
                ->description('These are Information that describe your preferred apartment type and location.')
                ->columns(2)
                ->schema([
                    Select::make('towns')
                        ->multiple()
                        ->label('Preferred property locations')
                        ->options(fn (callable $get) => School::find($get('school'))?->towns->pluck('name', 'id')->toArray() ?? [])
                        ->exists('school_town', 'town_id', fn (Exists $rule, callable $get) => $rule->where('school_id', $get('school')))
                        ->required(),

                    Select::make('rooms')
                        ->label('Number of Rooms')
                        ->options(ApartmentRooms::toAssocArray())
                        ->in(ApartmentRooms::toArray())
                        ->required(),

                    Select::make('min_budget')
                        ->label('Minimum Budget')
                        ->prefix('₦ ')
                        ->options(BudgetLimit::budgetRangeAssoc())
                        ->in(BudgetLimit::budgetRange())
                        ->required(),

                    Select::make('max_budget')
                        ->label('Maximum Budget')
                        ->prefix('₦ ')
                        ->options(BudgetLimit::budgetRangeAssoc())
                        ->in(BudgetLimit::budgetRange())
                        ->gte('min_budget')
                        ->required(),
                ])->collapsible(),
        ];
    }

    protected function showAlertOnSaveError()
    {
        $this->dispatchBrowserEvent(
            "open-alert",
            [
                "alert_type" => "danger",
                "message" => 'An error occurred while updating your profile please try again',
                "closeAfterTimeout" => false,
            ]
        );
    }

    public function save()
    {
        $data = $this->form->getState();

        $user = $this->getFormModel();

        $userAvatar = (filled($data['avatar_image']) && $user->avatar !== $data['avatar_image']) ? $data['avatar_image'] : $user->avatar;
        $userCover = (filled($data['cover_image']) && $user->cover_photo !== $data['cover_image']) ? $data['cover_image'] : $user->cover_photo;

        try {
            $canProceed = Storage::disk('avatars')->exists($userAvatar ?? '') &&
                Storage::disk('cover_photos')->exists($userCover ?? '');
        } catch (\Throwable $th) {
            $canProceed = false;
        }

        //store data using db transactions & set profile_updated field to true
        if ($canProceed) {
            DB::beginTransaction();

            try {
                $user->bio = $this->bio;
                $user->avatar = $userAvatar;
                $user->cover_photo = $userCover;
                $user->hobbies()->sync($this->hobbies);
                $user->dislikes()->sync($this->dislikes);
                $user->school()->associate($this->school);
                $user->course()->associate($this->course);
                $user->course_level = intval($this->course_level);
                $user->towns()->sync($this->towns);
                $user->rooms = $this->rooms;
                $user->min_budget = intval($this->min_budget);
                $user->max_budget = intval($this->max_budget);
                $user->profile_updated = true;

                $user->save();

                DB::commit();

                //redirect to dashboard
                return $this->redirect(route('profile.view', compact('user')));
            } catch (\Exception $th) {
                DB::rollBack();

                return $this->showAlertOnSaveError();
            }
        } else {
            return $this->showAlertOnSaveError();
        }
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.update-profile-page');

        return $view->layout('layouts.guest');
    }
}
