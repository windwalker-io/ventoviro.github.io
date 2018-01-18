---

layout: rad.twig
title: Admin UI Guide

---

## Phoenix Admin UI

If you init a package with `default` template, you may see this admin UI interface.

![img](https://cloud.githubusercontent.com/assets/1639206/9725055/0cc4e1fc-5613-11e5-9f0d-c373d7d68c87.png)

> To remove the stars (`**`), please open `/etc/dev/config.yml` and set `language.debug` to `0`.

## Grid Page

Grid table is the main part of List manager page. 

### Change State

You may check some rows and press buttons to operate this items.

![Imgur](https://i.imgur.com/1UxL0ML.jpg)

Or click the state button to publish / close / copy or delete an item.

![Imgur](https://i.imgur.com/UHp4TxQ.jpg)

### Ordering

![Imgur](https://i.imgur.com/hWWHstH.jpg)

### Pagination

![Imgur](https://i.imgur.com/v96Pl9B.jpg)

### Filter Bar

Filter help us search or filter items.

#### Search

Type something to search keyword.

![Imgur](https://i.imgur.com/EH4L5ot.jpg)

#### Filters

Click `Filters` button will expand filter tools. select a state to filter items.

![Imgur](https://i.imgur.com/uw6B6SV.jpg)

You may click `X` button to clear both search and filters.

![Imgur](https://i.imgur.com/NwA569R.jpg)

### Batch

Batch is a useful function to update multiple items in one time.

![Imgur](https://i.imgur.com/rbiI9yI.jpg)

## Edit Page

Click an item title or the `Create` green button, you will enter the edit page.
 
![Imgur](https://i.imgur.com/bZZBHpx.jpg)

If you click save without filling any field, the validation will auto work to prevent form submit.

![Imgur](https://i.imgur.com/qOJ7iY0.jpg)

If you enter the title and save, it will show success message.

![Imgur](https://i.imgur.com/cdY5h6r.jpg)

Click `Save & Close` or `Cancel`, will back to grid page. 

![Imgur](https://i.imgur.com/A3zSr6T.jpg)
