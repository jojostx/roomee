<div id="toast_notif" style="display: none;" class="fixed left-0 z-50 flex items-center justify-between w-full max-w-2xl px-4 py-4 overflow-hidden text-white bg-blue-500 shadow-lg md:px-6 md:ml-8 md:w-1/2 md:rounded-md bottom-8">
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

    function arrMinMaxNorm(budgetRange_1 = [0, 0], budgetRange_2 = [0, 0]){
        const [min_1, max_1] = budgetRange_1;
        const [min_2, max_2] = budgetRange_2;

        return (minMaxNorm(max_1, max_2) + minMaxNorm(min_1, min_2))/2;
    }

    function minMaxNorm(a = 0, b = 0) {
       return Math.pow(10, -(Math.abs(a - b)/Math.max(a, b)));
    }

    console.log(arrMinMaxNorm([100, 200], [500, 300]));
</script>
@endprepend

