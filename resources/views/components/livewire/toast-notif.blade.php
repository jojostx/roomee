<div id="toast_notif" wire:ignore style="display: none;" class="fixed left-0 z-50 flex items-center justify-between w-full max-w-2xl px-4 py-4 overflow-hidden text-white bg-blue-500 shadow-lg md:px-6 md:ml-8 md:w-1/2 md:rounded-md bottom-8">
    <a href="" class="flex-shrink-0 ml-2 text-sm underline hover:text-blue-100 md:text-base">View blocklist</a>
</div>
@prepend('scripts')
<script>
    Livewire.on('actionTakenOnUser', (username, actionTaken)=>{
        let notif = document.getElementById('toast_notif');
        let p;
        notif.style.display = 'flex';

        if (actionTaken == 'block') {
            p = `<p>You have succesfully ${actionTaken}ed 
                    <span class="font-semibold">${username}</span>
                 </p>
                 <a href="{{ route('blocklist') }}" class="flex-shrink-0 ml-2 text-sm underline hover:text-blue-100 md:text-base">
                  View blocklist
                 </a>`;
        }
        
        if (actionTaken == 'unblock') {
            p = `<p>You have succesfully ${actionTaken}ed 
                    <span class="font-semibold">${username}</span>
                 </p>`;
        }
        
        if (actionTaken == 'favorite') {
            p = `<p>You have succesfully added
                    <span class="font-semibold">${username}</span>
                    ${actionTaken}s
                 </p>
                 <a href="{{ route('favorites') }}" class="flex-shrink-0 ml-2 text-sm underline hover:text-blue-100 md:text-base">
                  View favorites
                 </a>`;
        }

        if (actionTaken == 'unfavorite') {
            p = `<p>You have succesfully ${actionTaken}d 
                    <span class="font-semibold">${username}</span>
                 </p>`;
        }
        
        if (actionTaken == 'request') {
            p = `<p>Your roommate ${actionTaken} has been sent to 
                    <span class="font-semibold">${username}</span>
                 </p>
                 <a href="{{ route('favorites') }}" class="flex-shrink-0 ml-2 text-sm underline hover:text-blue-100 md:text-base">
                  View all requests
                 </a>`;
        }

        if (actionTaken == 'report') {
            p = `<p>Your report has been submitted. 
                    Our team will review your report ASAP. Thanks!  
                 </p>`;
        }
        
        notif.innerHTML = p;
        setTimeout(()=>{
            notif.style.display = "none"
        }, 7000)
    })
</script>
@endprepend

