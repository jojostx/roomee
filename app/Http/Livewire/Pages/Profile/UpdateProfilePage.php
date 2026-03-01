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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Validation\Rules\Exists;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UpdateProfilePage extends Component implements HasForms
{
    use InteractsWithForms;

    public $avatar_image;
    public $first_name;
    public $last_name;
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
        $avatarPath = $this->resolveAvatarPathForForm($user->avatar);

        if (filled($avatarPath) && $avatarPath !== $user->avatar) {
            $user->forceFill([
                'avatar' => $avatarPath,
            ])->saveQuietly();
        }

        $this->form->fill([
            'avatar_image' => $avatarPath,
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

    protected function filledArray(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        return count(array_filter($value, static fn ($item): bool => filled($item))) > 0;
    }

    protected function isGeneralInformationComplete(Get $get): bool
    {
        return filled($get('avatar_image'))
            && filled($get('first_name'))
            && filled($get('last_name'));
    }

    protected function isPersonalInformationComplete(Get $get): bool
    {
        return filled($get('bio'))
            && $this->filledArray($get('hobbies'))
            && $this->filledArray($get('dislikes'));
    }

    protected function isEducationalInformationComplete(Get $get): bool
    {
        return filled($get('school'))
            && filled($get('course'))
            && filled($get('course_level'));
    }

    /**
     * @return \Filament\Forms\Components\Component[]
     */
    protected function getFormSchema(): array
    {
        return [
            Section::make('General Information')
                ->collapsible()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 4,
                        'lg' => 8,
                    ])
                        ->schema([
                            FileUpload::make('avatar_image')
                                ->label('Avatar Photo')
                                ->live()
                                ->avatar()
                                ->imageEditor()
                                ->imageEditorAspectRatios(['1:1'])
                                ->circleCropper()
                                ->imageResizeTargetWidth('320')
                                ->imageResizeTargetHeight('320')
                                ->disk('public')
                                ->directory(fn (): string => 'avatars/' . (string) auth()->id())
                                ->visibility('public')
                                ->image()
                                ->minSize(10)
                                ->maxSize(5098)
                                ->required()
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 2,
                                ]),

                            Grid::make([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                            ])
                                ->schema([
                                    Placeholder::make('Email')->extraAttributes(['class' => 'text-lg font-semibold capitalize'])
                                        ->content(auth()->user()->email),

                                    Placeholder::make('Gender')->extraAttributes(['class' => 'text-lg font-semibold capitalize'])
                                        ->content(auth()->user()->gender),

                                    TextInput::make('first_name')
                                        ->label('First Name')
                                        ->live()
                                        ->minLength(2)
                                        ->maxLength(160)
                                        ->rules(['string', 'max:160', 'min:2'])
                                        ->required(),

                                    TextInput::make('last_name')
                                        ->label('Last Name')
                                        ->live()
                                        ->minLength(2)
                                        ->maxLength(160)
                                        ->rules(['string', 'max:160', 'min:2'])
                                        ->required(),
                                ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 3,
                                    'lg' => 6,
                                ]),
                        ]),
                ]),

            Section::make('Personal Information')
                ->description(fn (Get $get): string => $this->isGeneralInformationComplete($get)
                    ? 'These are Information that describe who you are.'
                    : 'These are Information that describe who you are. Complete General Information to continue.')
                ->disabled(fn (Get $get): bool => !$this->isGeneralInformationComplete($get))
                ->columns(2)
                ->schema([
                    Textarea::make('bio')
                        ->label('About')
                        ->live()
                        ->minLength(15)
                        ->maxLength(255)
                        ->rules(['string', 'max:255', 'min:15'])
                        ->required()
                        ->columnSpan(2)
                        ->rows(4),

                    Select::make('hobbies')
                        ->multiple()
                        ->live()
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
                        ->live()
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
                ->description(fn (Get $get): string => $this->isGeneralInformationComplete($get) && $this->isPersonalInformationComplete($get)
                    ? 'These are Information about your current Educational arrangement.'
                    : 'These are Information about your current Educational arrangement. Complete Personal Information to continue.')
                ->disabled(fn (Get $get): bool => !($this->isGeneralInformationComplete($get) && $this->isPersonalInformationComplete($get)))
                ->columns(2)
                ->schema([
                    Select::make('school')
                        ->label('Institute of Study')
                        ->live()
                        ->searchable()
                        ->getSearchResultsUsing(fn(string $searchQuery) => School::where('name', 'like', "%{$searchQuery}%")->limit(50)->pluck('name', 'id')->toArray())
                        ->getOptionLabelUsing(fn($value): ?string => School::find($value)?->name)
                        ->options(School::all()->pluck('name', 'id')->toArray())
                        ->afterStateUpdated(function (Set $set) {
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
                        ->live()
                        ->searchable()
                        ->getSearchResultsUsing(fn(string $searchQuery, Get $get) => School::find($get('school'))->courses()->where('name', 'like', "%{$searchQuery}%")->limit(50)->pluck('courses.name', 'courses.id')->toArray())
                        ->getOptionLabelUsing(fn($value): ?string => Course::find($value)?->name)
                        ->options(fn(Get $get) => School::find($get('school'))?->courses->pluck('name', 'id')->toArray() ?? [])
                        ->afterStateUpdated(fn(Set $set) => $set('course_level', null))
                        ->required()
                        ->exists('course_school', 'course_id', fn(Exists $rule, Get $get) => $rule->where('school_id', $get('school')))
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 1,
                            'md' => 1,
                        ]),

                    Select::make('course_level')
                        ->label('Course Level')
                        ->live()
                        ->options(fn(Get $get) => Course::getCourseLevels(Course::find($get('course'))))
                        ->in(fn(Get $get) => Course::find($get('course'))?->levels ?? [])
                        ->required()
                        ->columnSpan([
                            'default' => 2,
                            'sm' => 1,
                            'md' => 1,
                        ]),
                ])->collapsible(),

            Section::make('Apartment Information')
                ->description(fn (Get $get): string => $this->isGeneralInformationComplete($get) && $this->isPersonalInformationComplete($get) && $this->isEducationalInformationComplete($get)
                    ? 'These are Information that describe your preferred apartment type and location.'
                    : 'These are Information that describe your preferred apartment type and location. Complete Educational Information to continue.')
                ->disabled(fn (Get $get): bool => !($this->isGeneralInformationComplete($get) && $this->isPersonalInformationComplete($get) && $this->isEducationalInformationComplete($get)))
                ->columns(2)
                ->schema([
                    Select::make('towns')
                        ->multiple()
                        ->label('Preferred property locations')
                        ->options(fn(Get $get) => School::find($get('school'))?->towns->pluck('name', 'id')->toArray() ?? [])
                        ->exists('school_town', 'town_id', fn(Exists $rule, Get $get) => $rule->where('school_id', $get('school')))
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
        $this->dispatch(
            'open-alert',
            alert_type: 'danger',
            message: 'An error occurred while updating your profile please try again',
            closeAfterTimeout: false
        );
    }

    protected function showAlert(string $type, string $message, bool $closeAfterTimeout = false): void
    {
        $this->dispatch(
            'open-alert',
            alert_type: $type,
            message: $message,
            closeAfterTimeout: $closeAfterTimeout,
        );
    }

    protected function onValidationError(ValidationException $exception): void
    {
        $firstError = $exception->validator?->errors()?->first()
            ?? 'Please review the highlighted fields and try again.';

        $this->showAlert('danger', $firstError, false);
    }

    public function save()
    {
        $data = $this->form->getState();

        $user = $this->getFormModel();
        if (!$user) {
            $this->showAlertOnSaveError();
            return;
        }

        $userAvatar = (filled($data['avatar_image']) && $user->avatar !== $data['avatar_image']) ? $data['avatar_image'] : $user->avatar;
        $userAvatar = $this->resolveAvatarPathForForm($userAvatar);

        if (blank($userAvatar) || !Storage::disk('public')->exists($userAvatar)) {
            $this->addError('avatar_image', 'Please upload a valid avatar image before saving your profile.');
            $this->showAlert('danger', 'Please upload a valid avatar image before saving your profile.', false);
            return;
        }

        DB::beginTransaction();

        try {
            $user->bio = $data['bio'];
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->avatar = $userAvatar;
            $user->hobbies()->sync($data['hobbies']);
            $user->dislikes()->sync($data['dislikes']);
            $user->school()->associate($data['school']);
            $user->course()->associate($data['course']);
            $user->course_level = intval($data['course_level']);
            $user->towns()->sync($data['towns']);
            $user->rooms = $data['rooms'];
            $user->min_budget = intval($data['min_budget']);
            $user->max_budget = intval($data['max_budget']);
            $user->profile_updated = true;

            $user->save();

            DB::commit();

            $this->showAlert('success', 'Profile updated successfully.', true);

            return $this->redirectRoute('dashboard');
        } catch (\Throwable $th) {
            DB::rollBack();

            report($th);

            $this->showAlertOnSaveError();
            return;
        }
    }

    protected function resolveAvatarPathForForm(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $rawPath = ltrim($path, '/');
        $normalizedPath = str_starts_with($rawPath, 'avatars/') ? $rawPath : 'avatars/' . $rawPath;
        $publicDisk = Storage::disk('public');

        foreach ([$normalizedPath, $rawPath] as $candidatePath) {
            if ($publicDisk->exists($candidatePath)) {
                return $candidatePath;
            }
        }

        if (!array_key_exists('avatars', config('filesystems.disks', []))) {
            return null;
        }

        $legacyRelativePath = str_starts_with($normalizedPath, 'avatars/')
            ? substr($normalizedPath, strlen('avatars/'))
            : $normalizedPath;

        try {
            $legacyDisk = Storage::disk('avatars');

            foreach (array_unique([$rawPath, $legacyRelativePath, $normalizedPath]) as $legacyPath) {
                if (blank($legacyPath) || !$legacyDisk->exists($legacyPath)) {
                    continue;
                }

                $stream = $legacyDisk->readStream($legacyPath);

                if ($stream === false) {
                    continue;
                }

                $publicDisk->writeStream($normalizedPath, $stream);

                if (is_resource($stream)) {
                    fclose($stream);
                }

                return $normalizedPath;
            }
        } catch (\Throwable $th) {
            return null;
        }

        return null;
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.update-profile-page');

        return $view->layout('layouts.guest');
    }
}
