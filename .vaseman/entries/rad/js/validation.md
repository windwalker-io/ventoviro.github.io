---

layout: rad.twig
title: Validation

---

## Introduction

Phoenix provides a built-in JS validation object to help us do front-end form validation.

Include Phoenix Validation:

``` php
\Phoenix\Script\PhoenixScript::formValidation();

// Custom selector and options
\Phoenix\Script\PhoenixScript::formValidation('#my-form', [
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

![required](https://cloud.githubusercontent.com/assets/1639206/17024215/9e40782e-4f89-11e6-8c23-06d97085d8df.gif)

> You can also add `required` to class attribute to enable validation.

## Validate Values

We can check input values matches some formats or not, and show warning and hint.

Add `validate-xxx` class to form field:

``` php
// In FieldDefinition

$this->text('url')
    ->label('URL')
    ->setClass('validate-url');

```

Now if input has value and it not matches the format you set, validator will show warning to this input:

![p-2016-07-21-006](https://cloud.githubusercontent.com/assets/1639206/17024500/bac3484a-4f8a-11e6-9150-98a1efee5b91.jpg)

> Validator will be ignored if input has no value, only checked if user typed something in this input.

Available Default Validators:

- `validate-password`
- `validate-numeric`
- `validate-email`
- `validate-url`
- `validate-alnum`
- `validate-color`
- `validate-creditcard`
- `validate-ip`

## Add Custom Validator

You can add your own validators in runtime. Here we add a password check validator.

``` js
// Get Validation object
var validation = $('#admin-form').validation();

// Add validator
validation.addValidator('password-check', function (value, $input) {
    return /^\S[\S ]{2,98}\S$/.test(value) && $('.input-passwird').val() == value;
})
```

This validator will check password value is valid string and matches `input.input-password` value.

### Hint Text

We can add text hint to a validator:

``` js
// Add validator
validation.addValidator('url', function(value, element) {
    value = punycode.toASCII(value);
    var regex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
    return regex.test(value);
}, {notice: 'Not a valid URL'})
```

![p-2016-07-21-007](https://cloud.githubusercontent.com/assets/1639206/17025137/329497be-4f8d-11e6-8d70-cf15f37a3642.jpg)

You can also use callback to generate hint text:

``` js
// Add validator
validation.addValidator('url', function(value, element) {
    // ...
}, {notice: function ($input, validation) {
    return 'Input: ' + $input.attr('name') + ' is invalid.';
}})
```

## Call Validation By JS

You can manually call validation by JS code without a submit event:

``` js
// Validate all inputs
$('#admin-form').validation()->validateAll();

// Add field to input queue to validate
$('#admin-form').validation().addField($('.input-myfield'));

// Validate one input
$('#admin-form').validation().validate($('.input-myfield'))
```
