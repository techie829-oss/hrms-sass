#!/bin/bash
export TMPDIR=/tmp
export COMPOSER_HOME=/tmp/.composer
composer require livewire/livewire -q
php artisan livewire:publish --config -q
echo "Livewire Setup Complete"
