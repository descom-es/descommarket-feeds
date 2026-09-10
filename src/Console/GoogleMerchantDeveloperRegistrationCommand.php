<?php

namespace DescomMarket\Feeds\Console;

use DescomMarket\Feeds\Google\Merchant\Services\DeveloperRegistration\DeveloperRegistrationService;
use Exception;
use Illuminate\Console\Command;

class GoogleMerchantDeveloperRegistrationCommand extends Command
{
    protected $signature = 'dm360:google:merchant:developer-registration
                            {--register : Register the GCP project with the merchant account}
                            {--email= : Optional contact Google invites and grants the API developer role}';

    protected $description = 'Check or register the GCP project with the Merchant API';

    public function handle(): int
    {
        try {
            $gcpIds = DeveloperRegistrationService::gcpIds();
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($gcpIds) {
            $this->info('Registered GCP projects: '.implode(', ', $gcpIds));

            if ($this->option('register')) {
                $this->line('Nothing to do: the account already has a developer registration.');
            }

            return self::SUCCESS;
        }

        $this->warn('The merchant account has no developer registration, so Merchant API calls fail.');

        if (! $this->option('register')) {
            $this->line('Run it again with --register to register the GCP project.');

            return self::FAILURE;
        }

        $email = $this->option('email');

        try {
            $registration = DeveloperRegistrationService::registerGcp($email);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $registered = array_map('strval', (array) ($registration['gcpIds'] ?? []));

        $this->info('Registered GCP projects: '.implode(', ', $registered));

        if ($email) {
            $this->line("Google invited {$email}, and the registration is only complete once it is accepted.");
        } else {
            $this->line('No contact given, so only the project was linked: give an existing user the API');
            $this->line('developer role in Merchant Center, under Access and services > People and access.');
        }

        $this->line('Google needs about five minutes before the API accepts calls.');

        return self::SUCCESS;
    }
}
