<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\DeveloperRegistration;

use DescomMarket\Feeds\Google\Merchant\MerchantClientBuilder;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class DeveloperRegistrationService
{
    private const ENDPOINT = 'https://merchantapi.googleapis.com/accounts/v1';

    /**
     * @return array<string, mixed>|null
     */
    public static function get(): ?array
    {
        $response = self::request()->get(self::url());

        if ($response->notFound() || self::isNotRegistered($response)) {
            return null;
        }

        $response->throw();

        return (array) $response->json();
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * Sin email solo se enlaza el proyecto, y el rol de developer hay que darlo
     * a mano a un usuario que ya exista. Con email, Google le manda invitación.
     */
    public static function registerGcp(?string $developerEmail = null): array
    {
        $url = self::url().':registerGcp';

        $response = $developerEmail
            ? self::request()->post($url, ['developerEmail' => $developerEmail])
            : self::request()->withBody('{}', 'application/json')->post($url);

        if ($response->conflict()) {
            throw new \Exception(
                'The GCP project is already registered, but the API still rejects calls. '
                .'Google can need a few minutes, and the registration is only complete once a '
                .'user holds the API developer role in Merchant Center: check that no invitation '
                .'is left pending in Settings > People and access.'
            );
        }

        $response->throw();

        return (array) $response->json();
    }

    /**
     * @return string[]
     */
    public static function gcpIds(): array
    {
        $registration = self::get();

        if (! $registration) {
            return [];
        }

        return array_map('strval', (array) ($registration['gcpIds'] ?? []));
    }

    /**
     * Sin registro la propia lectura devuelve 401, así que hay que distinguirlo
     * de un 401 de credenciales o de permisos.
     */
    private static function isNotRegistered(Response $response): bool
    {
        return $response->unauthorized()
            && str_contains($response->body(), 'GCP_NOT_REGISTERED');
    }

    private static function url(): string
    {
        return self::ENDPOINT.'/'.MerchantClientBuilder::account().'/developerRegistration';
    }

    private static function request(): PendingRequest
    {
        $token = MerchantClientBuilder::credentials()->fetchAuthToken();

        if (empty($token['access_token'])) {
            throw new \Exception('Could not get an access token for the Merchant API');
        }

        return Http::withToken($token['access_token'])->acceptJson();
    }
}
