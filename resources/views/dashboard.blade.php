<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div>
        @livewire('issues-modal')

        @livewire('dashboard')
       
        <div id="toast_notif" style="display: none;" class="fixed left-0 z-50 flex items-center justify-between w-full max-w-2xl px-4 py-4 overflow-hidden text-white bg-blue-500 shadow-lg md:px-6 md:ml-8 md:w-1/2 md:rounded-md bottom-8" >

            <a href="" class="flex-shrink-0 ml-2 text-sm underline hover:text-blue-100 md:text-base">View blocklist</a>
        </div>
    </div>
@prepend('scripts')
<script>
    Livewire.on('userBlocked', (username)=>{
        let notif = document.getElementById('toast_notif');
        notif.style.display = 'flex';
        let p = `<p>You have succesfully blocked 
                    <span class="font-semibold">${username}</span>
                 </p>
                 <a href="" class="flex-shrink-0 ml-2 text-sm underline hover:text-blue-100 md:text-base">
                  View blocklist
                 </a>`
        notif.innerHTML = p;
        setTimeout(()=>{
            notif.style.display = "none"
        }, 5000)
    })
</script>
@endprepend
</x-app-layout>