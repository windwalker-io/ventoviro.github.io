<script setup>
import { useData, withBase } from 'vitepress';
import DefaultTheme from 'vitepress/theme';
import { computed } from 'vue';
import { flatComponents } from '../data/components';
import { componentRoutePrefix } from '../store/routing-store';
import icon from '../../public/images/logo-icon.png';
// import { register } from 'swiper/element/bundle';
// register();

const { Layout } = DefaultTheme;
const data = useData();
const { page } = useData();

const componentAlias = computed(() => page.value.frontmatter.component || '');
const component = computed(() => flatComponents.value.find((com) => com.alias === componentAlias.value));
const isComponent = computed(() => !!page.value.frontmatter.component);

</script>

<template>
<Layout>
  <template #home-features-after>
<!--    <section class="container l-section l-section&#45;&#45;start my-7">-->
<!--      <h2 class="l-section__title"-->
<!--          style="font-size: 2rem; text-align: center"-->
<!--      >GETTING STARTED</h2>-->

<!--      <div class="mt-4 row justify-content-center">-->
<!--        <div class="col-lg-6 col-md-8">-->
<!--          <pre><code class="language-bash">$ composer create-project windwalker/starter</code></pre>-->
<!--        </div>-->
<!--      </div>-->

<!--      <div class="mt-4 text-center">-->
<!--        <a class="btn btn-primary btn-lg"-->
<!--            href="{{ $uri->path('documentation') }}">-->
<!--          <i class="fa-solid fa-file-lines"></i>-->
<!--          Documentation-->
<!--        </a>-->
<!--      </div>-->
<!--    </section>-->
  </template>


  <template #doc-before>
    <div v-if="isComponent" class="c-card" style="margin-bottom: 2rem; padding: 1.5rem; ">
      <div style="display: flex; gap: .5rem; margin-bottom: .5rem; align-items: center">
        <img :src="icon"
            style="height: 30px"
            alt="Windwalker">
        <a :href="`/${componentRoutePrefix}/${component.alias}/`">
          <h2 style="font-size: 1.5rem;">{{ component.title }}</h2>
        </a>
        <div style="opacity: .6">Component</div>
      </div>
      <div style="opacity: .4">
        {{ component.description }}
      </div>
    </div>
  </template>
</Layout>
</template>

<style scoped>

</style>
