import { DefaultTheme } from 'vitepress';
import { computed } from 'vue';
import { guideRoutePrefix } from '../store/routing-store';

export const guidesSidebar = computed<DefaultTheme.Sidebar>(() => {
  return [
    {
      text: 'Start',
      items: [
        {
          text: 'Installation',
          link: `/${guideRoutePrefix}/start/`
        },
        {
          text: 'Debugging',
          link: `/${guideRoutePrefix}/start/debugging`
        },
      ]
    },
    {
      text: 'MVC Basic',
      items: [
        {
          text: 'Getting Started',
          link: `/${guideRoutePrefix}/mvc/getting-started`
        },
        {
          text: 'Modeling',
          link: `/${guideRoutePrefix}/mvc/modeling`
        },
      ]
    }
  ];
});
