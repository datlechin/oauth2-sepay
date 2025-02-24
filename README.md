# SePay Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/release/datlechin/oauth2-sepay.svg?style=flat-square)](https://github.com/datlechin/oauth2-sepay/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/datlechin/oauth2-sepay.svg?style=flat-square)](https://scrutinizer-ci.com/g/datlechin/oauth2-sepay/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/datlechin/oauth2-sepay.svg?style=flat-square)](https://scrutinizer-ci.com/g/datlechin/oauth2-sepay)
[![Total Downloads](https://img.shields.io/packagist/dt/datlechin/oauth2-sepay.svg?style=flat-square)](https://packagist.org/packages/league/oauth2-github)

This package provides SePay OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```bash
composer require datlechin/oauth2-sepay
```

## Usage

Usage is the same as The League's OAuth client, using `\Datlechin\OAuth2\Client\Provider\SePay` as the provider.

### Authorization Code Flow

```php
$provider = new \Datlechin\OAuth2\Client\Provider\SePay([
    'clientId' => '{sepay-client-id}',
    'clientSecret' => '{sepay-client-secret}',
    'redirectUri' => 'https://example.com/callback-url',
]);

if (! isset($_GET['code'])) {
    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;
// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {
    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {
        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getFullName());
    } catch (Exception $e) {
        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Managing Scopes

When creating your SePay authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['profile','company', 'bank-account:read'] // array or string; at least 'profile' is required
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```

If neither are defined, the provider will utilize internal defaults.

At the time of authoring this documentation, the following scopes are available.

- profile
- company
- bank-account:read
- transaction:read
- webhook:read
- webhook:write
- webhook:delete

## Testing

```bash
./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/datlechin/oauth2-sepay/blob/main/CONTRIBUTING.md) for details.

## Credits

- [Ngô Quốc Đạt](https://github.com/datlechin)
- [All Contributors](https://github.com/datlechin/oauth2-sepay/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/datlechin/oauth2-sepay/blob/main/LICENSE) for more information.
