---
layout: documentation.twig
title: Crypt

---

## Hashing String or Password

Use `Hasher` or it's instance to hash string:
 
```php
use Windwalker\Core\Security\Hasher;

$hash = Hasher::create($password);

// Verify it
$bool = Hasher::verify($password, $hash);

// Get instance from container:
$hasher = $container->get('hasher');

$hasher->create(...);
$hasher->verify(..., ...);
```

### Configuration:

In `etc/config.yml`, you can change some settings:

```yaml
crypt:
    # The Crypt cipher method.
    # Support ciphers: blowfish (bf) / aes-256 (aes) / 3des / php_aes / sodium
    cipher: blowfish

    # The hashing algorithm
    # Support algorithms: blowfish (bf) / md5 / sha256 / sha512 / argon2 / scrypt
    hash_algo: blowfish

    # The hashing cost depends on different algorithms. Keep default if you don't know how to use it.
    hash_cost: ~
```

The `hash_algo` is the hash algorithm you want to use, default is `blowfish`.

The `argon2` and `scrypt` algorithm is powered by [libsodium](https://github.com/jedisct1/libsodium), you must install
`ext-libsodium` or use php 7.2 later to support them.

More usage please see [Windwalker Crypt](https://github.com/ventoviro/windwalker-crypt#windwalker-crypt)

## Encrypt and Decrypt Sensitive Data

Use `Crypto` and it's instance to encrypt and decrypt string.

```php
use Windwalker\Core\Security\Crypto;

$encrypted = Crypto::encrypt('hello');

echo Crypto::decrypt($encrypted); // hello

Crypto::verify('hello', $encrypted); //true
```

Get instance from container:

```php
$crypt = $container->get('crypt');

$crypt->encrypt('hello');
```

### Cipher Configuration:

See [Configuration above](#configuration), you can set cipher you want to encrypt data.

The default cipher is `blowfish` but you can use other openssl cipher like `aes` or `3des`,
if your environment has no openssl extension support, you can use `php_aes` instead them.

The libsodium also supports here, you can install `paragonie/sodium_compat` package to encrypt by sodium cipher
without php libsodium extension, but we still recommend you to install `ext-libsodium` or use php 7.2 later to 
support memory wipe and with higher performance.

If you get `sodium_memzero() only supports after php 7.2 or ext-libsodium installed.` message, 
You can disable memory wipe by `ignoreMemzero()` (But we don't recommend to do this):

```php
// Add this code in a listener or anywhere before your application 
$cipher = Crypto::getCipher();

if ($cipher instanceof \Windwalker\Crypt\Cipher\SodiumCipher) {
    $cipher->ignoreMemzero(true);
}

// Now you can encrypt text by sodium
Crypto::encrypt('Hello');
```

See [Windwalke Crypt](https://github.com/ventoviro/windwalker-crypt#symmetric-key-algorithm-encryption)
