# InVal
[![Build Status](https://travis-ci.com/tfettig01/InVal.svg?branch=master)](https://travis-ci.com/tfettig01/InVal)

InvVal is a stand alone validation library using a builder interface to validate values.

This project is currently an unstable pre-alpha. Tags will be added when stability is achieved.

Demonstration Usage: [DemonstrationTest Usage](https://github.com/tfettig01/InVal/blob/master/tests/DemonstrationTest.php).

```php
<?php
declare(strict_types=1);

use InVal\InVal;

$validation = new InVal();

$validation->intVal(1)
           ->min(0)
           ->errorMessage('This message will not be present on validation.');

$validation->floatVal(.6)
           ->between(.1, .9)
           ->errorMessage('This message will not be present on validation.');

$validation->stringVal('A string value that needs to be validated.')
           ->errorMessage('This message will not be present on validation.')
           ->regex('/^[\w .]+$/')
           ->minLen(0)
           ->maxLen(500);

assert($validation->success());
assert(count($validation->pullErrorMessages()) === 0);
```

Validator RoadMap:
- [x] Add boolean validator
- [x] Add integer validator
- [x] Add float validator
- [x] Add string validator
- [x] Add object that implement toString validator
- [x] Add instance of validator
- [x] Add a list validator
- [x] Add array validator
- [ ] Add ArrayObject validator

Array RoadMap
- [ ] Validation for an 'array' of integers
- [ ] Validation for an 'array' of strings
- [ ] Validation for an 'array' of floats
- [ ] Validation for an 'array' of booleans
- [ ] Validation for an 'array' of objects
- [ ] Validation for multi-dimensional array (recursion).