<div class="fixed inset-x-0 bottom-0 z-50 pb-2 js-cookie-consent cookie-consent">
    <div class="px-6 mx-auto max-w-7xl">
        <div class="p-2 rounded-lg shadow-xl bg-primary-50">
            <div class="flex flex-wrap items-center justify-between">
                <div class="items-center">
                    <p class="ml-3 text-black cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="flex-shrink-0 mt-2 sm:mt-0">
                    <button class="flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md cursor-pointer js-cookie-consent-agree cookie-consent__agree text-primary-100 bg-primary-900 hover:bg-primary-700">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
