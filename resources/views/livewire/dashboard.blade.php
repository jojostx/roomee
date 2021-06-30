<div class="grid gap-4 lg:gap-6 grid-col-1 sm:grid-cols-2 lg:grid-cols-3">
@foreach ($users as $user)
    @livewire('user-card', ['user' => $user], key($user->id))
@endforeach
</div>