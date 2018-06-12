---

layout: rad.twig
title: Crypto

---

## Instruction

`Phoenix.Crypto` is a Phoenix JS tool to help us do text encryption and decryption in browser side by simple XOR cipher.

Include Crypto:

``` php
\Phoenix\Script\PhoenixScript::crypto();
```

## Encrypt/Decrypt Text

```js
var text = 'Windwalker';
var key = 'dfG9O34F';
var secret = Phoenix.encrypt(key, text);

console.log(secret);
console.log(Phoenix.decrypt(key, secret));
```

Output:

```
NTEsMTUsNDEsOTMsNTYsODIsODgsNDUsMSwyMA==
Windwalker
```

## Base64

Simple methods to wrap `btoa()` and `atob()`

```js
var base64 = Phoenix.base64Encode('Hello');

console.log(base64);
console.log(Phoenix.base64Decode(base64));
```

Output:

```js
SGVsbG8=
Hello
```

## MD5

A JS md5 implementation, will be useful if you need an identify when you generating elements in frontend.

```js
Phoenix.md5('Windwalker'); // fa0a731220e28af75afba7135723015e
```

## UUID v4

UUID is useful if you need to generate random ID for dynamic elements.

```js
Phoenix.uuid4();
```

The result:

```
46a37daa-ef63-4b0c-9b4c-ec7008b19b70
```

## Uniqid

This method same as PHP `uniqid()`

```js
Phoenix.uniqid();
Phoenix.uniqid('Prefix', true);
```
