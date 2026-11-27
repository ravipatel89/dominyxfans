## JustFans - Premium Content Creators SaaS platform

#### About

Product description..

It is mainly based on following techs, plus couple of other dependencies and tools to ease up your development.

- Laravel 12
- Bootstrap 5.3.8
- jQuery

#### Requirements

* PHP 8.2-8.5
* Mysql / MariaDB (5.7/8.X)
* Apache & mod_rewrite / Nginx / OpenLiteSpeed
* Node 22 (see `.nvmrc`), Composer & at least 2GB of RAM for dev builds

#### Install

````
1) Create db
2) cp .env.example .env # Edit values, add db
3) composer install
4) php artisan npm:install
5) php artisan key:generate
6) php artisan migrate
7) php artisan db:seed
8) php artisan make-admin {email} # To add new admin user
````
_Note*_ If having issues with composer install, try `php -d memory_limit=1G /usr/bin/composer install`

The web installer saves phase progress in `storage/app/installing.json` and uses
`storage/app/installing.lock` to prevent concurrent execution. Returning to
`/install` in the owning browser session resumes its progress screen; failed
attempts wait for an explicit Retry. Resolve the underlying error before retrying.

For a fresh start, the owning session can use **Start over** after a failed phase.
If the browser session is lost, run `php artisan installer:reset` from the site
directory and confirm the prompt. Both resets refuse to run while a phase is
executing and clear progress only: they do not drop tables, erase data, or fix
installation errors. Back up anything needed and prepare an empty database before
starting again. Do not delete the lock file to bypass an executing installation.


#### Saving admin state via seeds

_Saving admin panel state. This will remove all prior admin related seeds and reverse genererate new ones - so default admin state & settings will persist._

````
php artisan admin:save
````

_Building Bootstrap 5 assets and replacing the generated frontend libraries in `public/libs`. The Artisan wrapper also republishes Filament and Livewire assets._
```
php artisan npm:publish
```

For a frontend-only or CI build, run `npm ci && npm run build`.

Release packaging uses `.release-build.json` (PHP 8.2, Sass) and `.nvmrc` to select
the build runtimes. Keep these files in the branch passed to the Envato bundler.

The build stages the Dart Sass → RTLCSS → PostCSS outputs and frontend libraries before replacing live assets. Compilation or staging failures leave published assets unchanged; a failed publish rolls back the affected outputs. `npm run build:css` safely rebuilds only the LTR/RTL pair, and `npm run assets:publish` publishes only libraries. Neither replaces generated branding (`public/css/theme/theme.css`) or customer overrides. Concurrent builds are blocked by `storage/app/frontend-build.lock`; after an interrupted build, verify no build is running before removing a stale lock. A process kill or server crash during publication can require manual recovery from the retained `.frontend-backup-*` files.

Both compiled BS5 direction bundles live in `public/css/theme`: `bootstrap.css` and
`bootstrap.rtl.css`, each including light and dark modes. Generated branding lives
alongside them in `theme.css` and loads after the selected base bundle. Asset builds
do not replace `theme.css`; theme generation/reset does not replace the base bundles.
Back up `theme.css` before deploying updates—do not delete the whole folder to rebuild assets.


_Running Code quality checkers and fixers_

````
php artisan code:check php/js cs/pasta
php artisan code:fix php/js
````

_Setting up the crons_

````
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
````

_Setting up payments_

For stripe:
1. In admin, add stripe public and secret key
2. In stripe, create a webhook ( all events )
3. Get Stripe's webhook secret and add it back to admin

For PP:

1. Login to PP Dev dashboard, create new app
2. Get Paypal's ClientID & Paypal Secret key and add them into the admin

Manual deposit receipts use the private `manual-payment-documents` disk and the
method's configurable File upload fields. Minimum receipt requirements count
completed private uploads only. Run pending migrations in maintenance mode when
deploying: the v12.0.0 migration copies historical receipts from their
original storage disks, verifies their contents, then removes the old attachment
records and `attachments.payment_request_id`. It is safe to retry after a failure;
uncopied associations are retained. Original storage objects are deliberately kept
as recovery backups, and the private disk needs enough free space for the copies.
Existing public source URLs are not revoked automatically; remove those backups
only after verifying the private documents and taking a separate storage backup.
The upgrade also clears Minify's cache; no CSS rebuild is required for this change.
Databases that already recorded v12.0.0 as applied need an explicit forward repair;
editing the migration does not rerun it automatically.


### Ionicons usage

Icons ( Ionicons )
Backend

````
 @ include('elements.icon',['icon'=>'chevron-heart'])
 @ include('elements.icon',['icon'=>'chevron-heart-outline'])
 @ include('elements.icon',['icon'=>'chevron-heart-outline','variant'=>'medium])
 @ include('elements.icon',['icon'=>'chevron-heart-outline','variant'=>'medium','centered'=>'true'])
````
            
Frontend
````
< ion-icon name="heart"> 
< ion-icon name="heart-outline"> 
< ion-icon name="heart-sharp"> 
< ion-icon size="small">
< ion-icon size="large">
````
            
### Translations

Backend
````
trans_choice('We got coconut.',2,['number'=>2])
We got coconut.

trans_choice('We got coconut.',1,['number'=>1])
We got coconut.

__('English is nice')
English is nice

__('Food is good',['food'=>'cacao cu lapte'])
Food is good
````

Frontend
````
trans_choice('We got 1 coconut.',2,{'number':2})
We got 1 coconut.

trans_choice('We got 1 coconut.',1,{'number':1})
We got 1 coconut.

trans('English is nice')
English is nice

trans('Food is good',{'food':'cacao cu lapte']})
Food is good
````
#### Benchmarks & Performance
 
Tested on a dual core, $10 Digital ocean droplet, running nginx wiht php-fpm and PHP74, which tends to throttle CPU usage we got the following results:

- Avg Max concurent request: ~240rps
- Avg Load time: ~0.5s
- Total bundle overhead (Gzipped): ~241KB

_Wrk Benchmark tool sample_
![alt text](https://i.imgur.com/gZ3o7eP.png)

_Google Lighthouse/Page Insights report sample_
![alt text](https://i.imgur.com/mFXY8Zb.png)

#### Questions?

Send us a message over http://qdev.tech .

<hr>

Qdev Techs SRL - All rights reserved.
