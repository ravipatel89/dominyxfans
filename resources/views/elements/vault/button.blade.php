@if(AttachmentHelper::isVaultEnabled() && (!getSetting('site.enforce_user_identity_checks') || GenericHelper::isUserVerified()))
    <button type="button" class="{{ $vaultButtonClass ?? 'form-action-link' }} vault-picker-open" title="{{ __($vaultButtonLabel ?? 'Select from Vault') }}" aria-label="{{ __($vaultButtonLabel ?? 'Select from Vault') }}" aria-haspopup="dialog" aria-controls="vault-picker-dialog">
        @unless($vaultTextOnly ?? false)
            @include('elements.icon', ['icon' => $vaultButtonIcon ?? 'albums-outline', 'variant' => $vaultIconVariant ?? 'medium', 'centered' => true])
        @endunless
        @unless($vaultIconOnly ?? false)<span>{{ __($vaultButtonLabel ?? 'Vault') }}</span>@endunless
    </button>
@endif
