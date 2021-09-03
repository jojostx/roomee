<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Roomee') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/welcome.js') }}" defer></script>
</head>

<body class="relative min-h-screen overflow-x-hidden font-sans antialiased bg-gray-100 vh">

    <!-- Page Content -->
    <main class="relative flex justify-center p-6">
        <div>
            <p class="text-2xl font-semibold">
                Welcome
            </p>
            <form id="payment-form" action="{{ route('braintree.payment') }}" method="post" class="max-w-md mb-2">
                @csrf
                
                <div id="dropin-container"></div>
                <input type="submit" class="w-full px-2 py-1 text-white bg-blue-800 rounded-md hover:bg-blue-900" value="Submit" />
                <input type="hidden" id="device-data" name="client_device_data" />
                <input type="hidden" id="nonce" name="payment_method_nonce" />
            </form>

            @if (session('paymentSuccesful'))
            <div class="px-4 py-4 text-blue-700 bg-blue-100 border border-blue-700">               
                <p>Your transaction was successful</p>
            </div>          
            @endif
        </div>
    </main>

    <script src="https://js.braintreegateway.com/web/dropin/1.31.2/js/dropin.min.js"></script>
    <script>
        console.log(braintree)

        const form = document.getElementById('payment-form');

        const CLIENTTOKEN = "{{ $clientToken }}";

        braintree.dropin.create({
            authorization: CLIENTTOKEN,
            container: '#dropin-container',
            dataCollector: true
        }).then((dropinInstance) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                dropinInstance.requestPaymentMethod()
                    .then((payload) => {

                        document.getElementById('nonce').value = payload.nonce;
                        document.getElementById('device-data').value = payload.deviceData;

                        form.submit();
                    })
                    .catch((error) => {
                        throw error;
                    });
            });
        }).catch((error) => {
            console.log('there was an error in copleting payment');
        });
    </script>
</body>

</html>