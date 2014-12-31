layout: documentation.twig
title: Use Controller

---

# Controller Overview

Windwalker Controller is a main entry of a page, after routing, the request input and IoC container will be injected into controller and execute it. Every our code of this page will start at `doExecute()`.

## Single Action Pattern

Windwalker Controller follows single responsibility   
