---

layout: rad.twig
title: Record Fields

---

## Record Autocomplete

All Phoenix generated Records will use a trait with same name, to provides fields hint.

```php
namespace Flower\Record;

use Asuka\Flower\Record\Traits\SakuraDataTrait;
use Windwalker\Record\Record;

class SakuraRecord extends Record
{
	use SakuraDataTrait;
	
	// ...
```

```php
namespace Flower\Record\Traits;

/**
 * The SakuraDataTrait class.
 *
 * @property  integer  id
 * @property  string   title
 * @property  string   alias
 * @property  string   url
 * @property  string   intortext
 * @property  string   fulltext
 * @property  string   image
 * @property  string   state
 * @property  string   ordering
 * @property  string   created
 * @property  integer  created_by
 * @property  string   modified
 * @property  integer  modified_by
 * @property  string   language
 * @property  string   params
 *
 * @since  1.1
 */
trait SakuraDataTrait
{

}
```

So IDE are able to auto-complete your record item in template.

![Imgur](https://i.imgur.com/7UKtaey.jpg)

## Update Fields

After you update DB fields or migrations, just run this command to update trait's docblock:

```bash
$ php windwalker phoenix record sync flower sakura

File: /.../Flower/Record/Traits/SakuraDataTrait.php exists, do you want to override it? [N/y]: y

Writing file: /.../Flower/Record/Traits/SakuraDataTrait.php success.
```
