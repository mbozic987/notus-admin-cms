# Development

## Docker Setup
- Install Docker (depending on your system, install WSP for Windows and update to WSP2).
- Copy and rename .env.example to .env.
- Generate keys: `php artisan key:generate`
- Composer update: `composer update`
- Add Sail to the root: `alias sail='bash vendor/bin/sail`
- Install volumes: `sail install`
- Start containers: `sail up`
- Symlink: `sail artisan storage:link`
- Migrate and seed the database: `sail artisan migrate --seed`

## Helpers
- Reset database: `sail artisan migrate:fresh --seed`
- Show all routes: `sail artisan route:list`
- Close containers: `sail down`
- Clear Laravel configuration: `sail artisan config:clear`
