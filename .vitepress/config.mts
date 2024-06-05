import { defineConfig } from 'vitepress'
import { flatComponents, getComponentsSidebar } from './data/components';
import { guidesSidebar } from './data/guides';
import { componentRoutePrefix, guideRoutePrefix } from './store/routing-store';

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Windwalker PHP Framework",
  description: "The Next Generation PHP Framework",
  appearance: 'force-dark',
  head: [
    // Links
    ['link', { rel: 'icon', href: '/images/logo-icon.png' }],

    // Meta
    ['meta', { property: 'og:image', content: 'https://repository-images.githubusercontent.com/15371097/87e3e600-736d-11e9-9c49-64382cf43093' }],

    // Scripts
    ['script', { async: true, src: 'https://www.googletagmanager.com/gtag/js?id=G-MYMEG7N4RV' }],
    ['script', {}, `  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-MYMEG7N4RV');`],
  ],
  themeConfig: {
    logo: "/images/logo-cw-h.svg",
    siteTitle: false,
    editLink: {
      pattern: 'https://github.com/windwalker-io/windwalker-io.github.io/blob/master/:path'
    },

    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      {
        text: 'Documentations',
        items: [
          { text: 'Guide', link: `/${guideRoutePrefix}/start/` },
          { text: 'Framework', link: '#' },
          { text: 'Components', link: `/${componentRoutePrefix}/` },
        ]
      },
      { text: 'Unicorn RAD', link: '#' },
      { text: 'Status', link: 'status' },
      { text: 'Others', link: '#' },
    ],

    sidebar: {
      [`/${guideRoutePrefix}`]: guidesSidebar.value,
      [`/${componentRoutePrefix}`]: await getComponentsSidebar(),
    },

    // sidebar: [
    //   {
    //     text: 'Examples',
    //     items: [
    //       { text: 'Markdown Examples', link: '/markdown-examples' },
    //       { text: 'Runtime API Examples', link: '/api-examples' }
    //     ]
    //   }
    // ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/windwalker-io/framework/' }
    ],

    footer: {
      message: 'Released under the MIT License.',
      copyright: `Copyright © ${new Date().getFullYear()} <a href="https://simular.co/" target="_blank">Simular Inc.</a>.`
    }
  },
  transformPageData(pageData, { siteConfig }) {
    if (pageData.frontmatter?.component) {
      const component = flatComponents.value
        .find((com) => com.alias === pageData.frontmatter?.component);

      pageData.titleTemplate = `:title | ${component?.title} Component | ${siteConfig.site.title}`;
    }
  }
})
