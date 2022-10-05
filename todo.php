        
// $user_1_dislikes = $user_1->dislikes()->select('name')->get()->pluck('name')->toArray();
// $user_2_dislikes = $user_2->dislikes()->select('name')->get()->pluck('name')->toArray();

// \dd($this->validate());
// \dd($this->form->getState());
// [
// "avatar_image" => "2/avatar-photo-c81e728d9d4c2f636f067f89cc14862c-91bda30d-1b83-41a8-a63b-a197110266f3.png",
// "cover_image" => "2/cover-photo-c81e728d9d4c2f636f067f89cc14862c-2062cd3b-7e31-44d2-8b06-fcc879c03f2c.png",
// "firstname" => "Mark",
// "lastname" => "Ivan",
// "bio" => "I am very devoted christian and therefore I want a roommate who is preferably a christian",
// "hobbies" => [
// 0 => "1",
// 1 => "3",
// ],
// "dislikes" => [
// 0 => "1",
// 1 => "2",
// ],
// "school" => "6",
// "course" => "5",
// "course_level" => "300",
// "towns" => [
// 0 => "1",
// 1 => "2",
// ],
// "rooms" => "3",
// "min_budget" => "60000",
// "max_budget" => "120000",
// ];

public function upload()
{
$this->validate([
'image' => 'image|max:1024', // 1MB Max
]);

$res = $this->image->store('test', 'avatars');

\dd($this->image, $res);
}

{
// $this->saveUploadedFileUsing(static function (PhotoUpload $component, TemporaryUploadedFile $file): string {
// $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

// \dd(
// $file->{$storeMethod}(
// $component->getDirectory(),
// $component->getUploadedFileNameForStorage($file),
// $component->getDiskName(),
// ),
// $storeMethod,
// $file
// );

// return $file->{$storeMethod}(
// $component->getDirectory(),
// $component->getUploadedFileNameForStorage($file),
// $component->getDiskName(),
// );

// try {
// $directoryCreated = Storage::disk($component->getDiskName())->makeDirectory($component->getDirectory());
// if ($directoryCreated) {
// return $file->move($component->getDirectory(), $component->getUploadedFileNameForStorage($file));
// } else {
// return $file->{$storeMethod}(
// $component->getDirectory(),
// $component->getUploadedFileNameForStorage($file),
// $component->getDiskName(),
// );
// }
// } catch (\Exception $th) {
// return false;
// }
// });
}


// \dd(@chmod($state->getRealPath(), 0777), $state);
// $state->move($state->getPath(), 'sss' . $state->getExtension());
// $state->isReadable(), $state->getPath();
// \dd($this->readStream(), $this->get());
// $moded = @chmod($state->getRealPath(), 0777 & ~umask());
// new UploadedFile();
// ->opacity(20)->save(public_path('images/test.png'), 10)
// $image = Image::make($state->getRealPath());

// FileUpload::make('cover_image')
// ->label('Cover Photo')
// ->image()
// ->multiple()
// ->disk('cover_photos')
// ->panelLayout('compact')
// ->panelAspectRatio('2:1')
// ->imageCropAspectRatio('2:1')
// ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
// return (string) str($file->getClientOriginalName())->prepend('cover-photo-', md5(strval(auth()->user()->id)), now() . '-');
// })
// ->extraAttributes(['class' => 'bg-gray-100'])
// ->columnSpan([
// 'default' => 2,
// 'sm' => 2,
// 'md' => 3,
// 'lg' => 6,
// ]),

// Fileupload::make('avatar')
// ->disableLabel()
// ->avatar()
// ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
// return (string) str($file->getClientOriginalName())->prepend('avatar-photo-', md5(strval(auth()->user()->id)), now() . '-');
// })
// ->columnSpan([
// 'default' => 1,
// 'sm' => 1,
// 'md' => 1,
// 'lg' => 2,
// ]),


1. implement gates and policies\//
2. when a user blocks another user send an event to the blockee's app to redisplay the user/to hide
the blocker's profile card
3. show toast notification upon successfully reporting a user
4. extract the toast notification into blade component
5. refactor toast notification to recieve html fragment from the event emitter



// if the arrays intersect,
// find the max and min values of all the values
$max = max($min_1, $min_2, $max_1, $max_2);
$min = min($min_1, $min_2, $max_1, $max_2);

// find the intermediate values excluding the min and max values
$inter_val_arr = array_values(array_diff(array_merge($arr_1, $arr_2), [$max, $min]));

// find the intersection between the two set/arrays by finding the difference between the intermediate values
// if there is only one intermediate value, subtract the min value from the intermediate value
// to get the intersection
$intersection = (count($inter_val_arr) > 1) ? abs($inter_val_arr[0] - $inter_val_arr[1]) : $inter_val_arr[0] - $min;

if (count($inter_val_arr) > 1) {
$intersection = abs($inter_val_arr[0] - $inter_val_arr[1]);
}else {
if ($max_2 === $max_1) {
$intersection = $max - $inter_val_arr[0];
}
else if ($min_1 == $min_2) {
$intersection = $inter_val_arr[0] - $min;
}
}

return [$intersection, $inter_val_arr];


<div>
    <button class="px-2 py-1 text-white bg-blue-600 shadow-md hover:shadow-lg hover:bg-blue-700">
        Refresh children
    </button>
</div>


<div class="py-1.5 border-t border-b">
    <!-- <p class="mb-1 text-sm font-semibold text-gray-500">Hobbies & Interests</p> -->
    <x-livewire.label>
        <x-slot name='svgicon'>
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
        </x-slot>
        Hobbies and Interests
    </x-livewire.label>
    <div class="flex flex-wrap items-end overflow-hidden">
        @foreach ($user->hobbies as $hobby)
        @if ($loop->iteration <= 3 ) <div class="inline-flex items-center justify-center px-2 py-1 mb-2 mr-2 text-sm text-gray-800 bg-gray-200 rounded-md">{{ $hobby['name'] }}</div>
    @endif
    @endforeach
    <span class="text-lg font-extrabold text-gray-400 mb-0.5 tracking-widest">...</span>
</div>
</div>

<div class="py-1.5 border-b">
    <!-- <p class="mb-1 text-sm font-semibold text-gray-500">Dislikes</p> -->
    <x-livewire.label>
        <x-slot name='svgicon'>
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
        </x-slot>
        Dislikes
    </x-livewire.label>
    <div class="flex flex-wrap items-end overflow-hidden">
        @foreach ($user->dislikes as $dislike)
        @if ($loop->iteration <= 3 ) <div class="inline-flex items-center justify-center px-2 py-1 mb-2 mr-2 text-sm text-gray-800 bg-gray-200 rounded-md">{{ $dislike['name'] }}</div>
    @endif
    @endforeach
    <span class="text-lg font-extrabold text-gray-400 mb-0.5 tracking-widest">...</span>
</div>
</div>

// deployment commands
php artisan queue:table
php artisan migrate
php artisan queue:work
php artisan websockets:serve

//build commands
composer install
npm install
npm run build


//consideraton
utilizing redis
utilizing amazon s3 for image upload
utilizing livewire file upload

//tasks
(development)
collect and organize CSV files for regenerating courses, universities and location data.
generate similarity matrix for courses and cache using redis (in user similarity service class).
implement retrieval of similarity score from redis db.
implement account verification using email.
implement Oauth signup/login using socialite.
implement notifications functionality.
implement the recieving of websocket event.
implement accepting and declining of roommate request.
implement guards on which users can recieve/send roommate request from each other.
implement sending of email upon sending/accepting a request.
implement the user account and notification settings.
implement showing of contact info (facebook, phone number and email) upon accepting a request.
add contact field to sign up or update profile form.
implement loading states for all pages
implement infinite scrolling for dashboard page
implement pagination for all other pages

(testing)
write tests for all the above functionalities


$requestTypes = collect(['sent' => 'sender_id', 'recieved' => 'recipient_id']);

$columnName = $requestTypes->get($requestType);

if (!$columnName) {
return collect([]);
}

// auth()->user()->${}
return RoommateRequest::where($columnName, auth()->id())->orderBy('created_at', 'desc')->get();


<div class="flex items-center gap-2 px-4 py-2">
  <x-livewire.includes.favoriting-sxn :user="$user" />
  <x-livewire.includes.requesting-sxn :user="$user" />
  <x-filament-support::icon-button wire:click='showReportOrBlockModal' wire:loading.attr="disabled" style="border-radius: 0.5rem;" class="border rounded-lg border-secondary-300 disabled:cursor-not-allowed disabled:pointer-events-none shrink-0 " color="secondary" size="sm" icon="heroicon-s-dots-horizontal" aria-label="show user menu" title="show user menu" />
</div>