<div class="max-w-3xl px-4 py-10 mx-auto sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white border border-gray-200 shadow-xs rounded-xl">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-900">Verification In Progress</h1>
            <p class="mt-2 text-sm text-gray-600">
                Your identity documents are currently under review.
            </p>
        </div>

        <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-700">
                Expected review timeline:
                <span class="font-semibold text-gray-900">{{ $this->timelineText }}</span>.
            </p>

            <p class="text-sm text-gray-700">
                You will receive an email once your verification is approved or rejected.
            </p>

            <div class="flex flex-wrap gap-3 pt-2">
                <a
                    href="{{ route('profile.update') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-700"
                >
                    Go to Profile Update
                </a>

                <a
                    href="{{ route('settings.account') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
                >
                    Go to Account Settings
                </a>
            </div>
        </div>
    </div>
</div>

