<div class="row d-flex justify-content-center captcha-field mb-3">
    <x-captcha-container
        data-language="{{GenericHelper::getPreferredLanguage()}}"
        data-theme="{{GenericHelper::getSiteTheme()}}"
    />
    @if ($errors->has('cf-turnstile-response') || $errors->has('h-captcha-response') || $errors->has('g-recaptcha-response'))
        <span class="text-danger mt-1" role="alert">
            <strong>{{ __("Please check the captcha field.") }}</strong>
        </span>
    @endif
</div>
