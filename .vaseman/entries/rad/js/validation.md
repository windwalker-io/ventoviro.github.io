---

layout: rad.twig
title: Validation

---

## Introduction

Phoenix provides a built-in JS validation object to help us do front-end form validation.

Include Phoenix Validation:

``` php
\Phoenix\Script\PhoenixScript::validation();

// Custom selector and options
\Phoenix\Script\PhoenixScript::validation('#my-form', [
    'events' => ['change']
    'scroll' => [
        'enabled'  => true,
        'offset'   => -100,
        'duration' => 1000
    ]
]);
```

## Required

Add required to form field:

``` php
// In FieldDefinition

$this->text('title')
    ->label('Title')
    ->required();

```

OR just add it to HTML

``` html
<input name="title" type="text" required />
```

Then Validation will block form submit action if this input is empty, and auto scroll to this input:

![required](https://i.imgur.com/yW8lDO6.gif)

> You can also add `required` to class attribute to enable validation.

## Validate Values

### Use HTML5 Input Validation

If you use HTML5 inputs with special types, for example, `<inpug type="email">`, the validator will auto use 
HTML5 validation to check values.

You can also add `pattern` attribute to check by your own rules.

```php
$this->text('title')
    ->label('Title')
    ->pattern('^[0-9]{9}$');
```

![](https://i.imgur.com/9L1eGY3.jpg)

### Built-in JS Validators

We can check input values matches some formats or not, and show warning and hint.

Add `data-validate="{validator}"` attribute to form field:

``` php
// In FieldDefinition

$this->text('url')
    ->label('URL')
    ->attr('data-validate', 'url');

```

Now if input has value and it not matches the format you set, validator will show warning to this input:

![p-2016-07-21-006](https://i.imgur.com/d3zQ1sP.jpg)

> Validator will be ignored if input has no value, only checked if user typed something in this input.

You can add multiple validators with separated by `|`. `->attr('data-validate', 'a|b|c')`

Available Default Validators:

- `password`
- `numeric`
- `email`
- `url`
- `alnum`
- `color`
- `creditcard`
- `ip`

## Add Custom JS Validator

You can add your own validators in runtime. Here we add a password check validator.

```js
// Get Validation object
var validation = $('#admin-form').validation();

// Add validator
validation.addValidator('password-confirm', function (value, $input) {
    return /^\S[\S ]{2,98}\S$/.test(value) && $('#input-password').val() === value;
})
```

Then add `data-validate="password-confirm"` class to input.

This validator will check password value that is valid string and matches the `input.input-password` value.

### Hint Text

HTML5 validation has there return status:

- value-missing
- type-mismatch
- pattern-mismatch
- too-long
- too-short
- range-underflow
- range-overflow
- step-mismatch
- bad-input
- custom-error

If you want to custom `type-mismatch` message, use `data-{type}-message` attribute.

```php
// In FieldDefinition

$this->text('url')
    ->label('URL')
    ->attr('data-type-mismatch-message', 'Please enter valid URL');
```

Result:

![](https://i.imgur.com/WGkCB3c.jpg)

###  Message for Custom Validators

Custom validators uses `custom-error` status, so just add `data-custom-error-message` attribute.

## Call Validation By JS

You can manually call validation by JS code without a submit event:

```js
// Validate all inputs
$('#admin-form').validation().validateAll();

// Add field to input queue to validate
$('#admin-form').validation().addField($('.input-myfield'));

// Validate one input
$('#admin-form').validation().validate($('.input-myfield'))
```

## Events

### After Validate Success

Phoenix Validation will trigger `phoenix.validate.success` event after validate success and prepare to submit form, this
event is helpful if you want to disabled the submit button.

```html
<script>
$('#admin-form').on('phoenix.validate.success', function (e) {
    $('.submit-button').attr('disabled');
});
</script>
```

### Validate Response

When call validation, every form field will trigger `phoenix.validate.{state}`, for example:

```js
$('#input-url').on('phoenix.validate.fail', function (event) {
    // event.input
    // event.state
    // event.help
    alert('Wrong URL format.');
});
```

Supported state:

- success
- fail (Wrong format)
- none (Clear response)
- empty (Required and empty)

There will also trigger a global event: `validation.response`

```js
Phoenix.on('validation.response', function (params) {
    // Params: $input, state, help, validation (validation object)
    if (params.$input.attr('name') === 'url' && state === 'fail') {
      alert('...');
    }
});
```

There has also a `validation.remove` event when all response clear.
