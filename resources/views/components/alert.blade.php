@props(['hasCloseButton', 'showAlert', 'type', 'closeAfterTimeout' => false])

<div x-data="alert" x-bind="dialogue" x-cloak class="alert" role="alert">
  <div class="flex w-full text-sm font-medium md:justify-center md:items-center">
    <svg class="flex-shrink-0 w-5 h-5 mr-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
    </svg>
    <div x-text="message" class="inline-flex"></div>
  </div>

  @if ($hasCloseButton)
  <button x-bind="closeButton" aria-label="close" type="button" class="close-button">
    <span class="sr-only">Close</span>
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
    </svg>
  </button>
  @endif

  <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('alert', () => ({
          // Data //
          show_alert: '{{ $showAlert }}',
          
          show_close_button: '{{ $hasCloseButton }}',
          
          closeAfterTimeout: '{{ $closeAfterTimeout }}',

          message: '{{ $slot }}',

          alert_type: '{{ $type }}',

          alert_types: {
            success: {
              'dialogue_classes': 'alert-success',
              'button_classes': 'close-button__success',
            },

            danger: {
              'dialogue_classes': 'alert-danger',
              'button_classes': 'close-button__danger',
            },

            warning: {
              'dialogue_classes': 'alert-warning',
              'button_classes': 'close-button__warning',
            },

            info: {
              'dialogue_classes': 'alert-info',
              'button_classes': 'close-button__info',
            },

            default: {
              'dialogue_classes': 'alert-default',
              'button_classes': 'close-button__default',
            },
          },
          
          // Data [getters] //
          getDialogueClasses() {
            return this.alert_types[this.alert_type]['dialogue_classes']
          },

          getButtonClasses() {
            return this.alert_types[this.alert_type]['button_classes']
          },

          // binders //
          closeButton: {
            ['@click']() {
              this.show_alert = false;
            },

            ['x-show']() {
              return this.show_close_button;
            },

            [':class']() {
              return this.getButtonClasses();
            },
          },

          dialogue: {
            [':class']() {
              return this.getDialogueClasses();
            },

            ['x-show']() {
              return this.show_alert;
            },

            ['@open-alert.window']($event) {
              if (!$event.detail?.message || !$event.detail?.alert_type) {
                return;
              }

              message = $event.detail.message;

              alert_type = $event.detail.alert_type;

              this.message = message;

              this.alert_type = (alert_type in this.alert_types) ? alert_type : 'default';

              this.show_alert = true;

              if (this.closeAfterTimeout || $event.detail?.closeAfterTimeout) {
                setInterval(() => {
                  this.show_alert = false;
                }, 5000);
              }
            },

            ['@close-alert.window']() {
              this.show_alert = false;
            },
          },
        }))
    })
  </script>
</div>