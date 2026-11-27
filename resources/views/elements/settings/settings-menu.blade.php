<!-- Collapsible Menu -->
<div class="collapse d-lg-block settings-nav" id="settingsNav">
    @php
        $settingsMenuItems = [];
        foreach ($availableSettings as $key => $setting) {
            $settingsMenuItems[$key] = array_merge($setting, ['label' => ucfirst(__(str_replace('-', ' ', $key)))]);
        }
    @endphp
    @include('elements.account.sidebar-menu', [
        'menuItems' => $settingsMenuItems,
        'menuRoute' => 'my.settings',
        'menuActive' => $activeSettingsTab,
    ])
</div>
