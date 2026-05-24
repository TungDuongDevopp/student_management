<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_configs')) {
                $mailConfigs = \App\Models\SystemConfig::whereIn('key', [
                    'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'
                ])->pluck('value', 'key');

                if (isset($mailConfigs['mail_host']) && $mailConfigs['mail_host'] != '') {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => $mailConfigs['mail_host'],
                        'mail.mailers.smtp.port' => $mailConfigs['mail_port'] ?? 587,
                        'mail.mailers.smtp.encryption' => $mailConfigs['mail_encryption'] ?? 'tls',
                        'mail.mailers.smtp.username' => $mailConfigs['mail_username'],
                        'mail.mailers.smtp.password' => $mailConfigs['mail_password'],
                        'mail.from.address' => $mailConfigs['mail_from_address'] ?? $mailConfigs['mail_username'],
                        'mail.from.name' => $mailConfigs['mail_from_name'] ?? 'Hệ thống Quản lý',
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Ignore DB errors during boot
        }
    }
}
