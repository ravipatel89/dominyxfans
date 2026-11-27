@if(getSetting('site.allow_theme_switch'))
    @php($isDarkTheme = GenericHelper::isDarkMode())
    <button type="button"
            class="text-link footer-preference-button d-flex align-items-center dark-mode-switcher border-0 bg-transparent p-0"
            data-current-theme="{{ $isDarkTheme ? 'dark' : 'light' }}"
            aria-label="{{ $isDarkTheme ? __('Light mode') : __('Dark mode') }}">
        <span class="d-flex justify-content-center align-items-center">
            <span class="icon-wrapper d-flex justify-content-center align-items-center" aria-hidden="true">
                <span data-theme-light-class="d-none" class="d-inline-flex align-items-center {{ $isDarkTheme ? '' : 'd-none' }}">
                    @include('elements.icon',['icon'=>'contrast-outline','variant'=>'small','centered'=>false,'classes'=>'me-1'])
                </span>
                <span data-theme-dark-class="d-none" class="d-inline-flex align-items-center {{ $isDarkTheme ? 'd-none' : '' }}">
                    @include('elements.icon',['icon'=>'contrast','variant'=>'small','centered'=>false,'classes'=>'me-1'])
                </span>
            </span>
            <span class="d-block text-truncate side-menu-label ms-1"
                  data-theme-toggle-label
                  data-theme-light-label="{{ __('Light') }}"
                  data-theme-dark-label="{{ __('Dark') }}">
                {{ $isDarkTheme ? __('Light') : __('Dark') }}
            </span>
        </span>
    </button>
@endif
