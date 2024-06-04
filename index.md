---
# https://vitepress.dev/reference/default-theme-home-page
layout: home

hero:
  name: "Windwalker"
  text: "The Next Generation PHP Framework"
#  tagline: My great project tagline
  image:
      src: /images/logo-icon.png
      alt: Windwalker
  actions:
    - theme: brand
      text: 4.x Documentation
      link: /documentation/components/
    - theme: alt
      text: 3.x
      link: https://windwalker.io/site-legacy/

features:
  - title: Easy and Powerful
    icon: 😃
    details: Learning a new framework is hard, we provide simple and semantic interface to help developers understand this framework.
  - title: Fully Decoupled
    icon: 🧩
    details: Windwalker is a set of PHP tools, you can easily install them by composer without too many dependencies.
  - title: Extendable
    icon: 🛠️
    details: The package system helps us organize our classes and routing to build large enterprise level applications.
  - title: Standard
    icon: 📐
    details: We follow PSR standard, you can easily integrate 3rd party middlewares or caching library into Windwalker.
  - title: Rapid Development
    icon: 🧙
    details: Windwalker is a RAD framework, building a usable system prototype with powerful UI is very fast.
  - title: IDE friendly
    icon: ⌨️
    details: Class searching, auto-completion and many useful IDE functions are working well with IoC.
---

<script setup>
import { VPButton } from 'vitepress/theme';
</script>

## Getting Started

```shell
composer create-project windwalker/starter ^4.0
```

<VPButton href="/documentation/components/" text="Documentation"></VPButton>
