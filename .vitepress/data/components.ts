
export const componentNames = [
  'attributes',
  'authentication',
  'authorization',
  'cache',
  'crypt',
  'data',
  'database',
  'di',
  'dom',
  'edge',
  'environment',
  'event',
  'filesystem',
  'filter',
  'form',
  'html',
  'http',
  'language',
  'orm',
  'pool',
  'promise',
  'query',
  'queue',
  'reactor',
  'renderer',
  'scalars',
  'session',
  'stream',
  'test',
  'uri',
  'utilities',
];

export const groupedComponents: ComponentSet = {
  'Core': {
    'attributes': {
      title: 'Attributes',
      description: 'PHP8 Attributes decorator component.',
      menu: () => import('./menu/components/attributes'),
      extra: {},
    },

    'di': {
      title: 'DI',
      description: 'A powerful PHP Dependency Injection library / IoC Container.',
      menu: () => import('./menu/components/di'),
      extra: {},
    },

    'promise': {
      title: 'Promise',
      description: 'PHP Promise/A+ library with ES like interface.',
      extra: { 'wip': true },
    },

    'reactor': {
      title: 'Reactor',
      description: 'Simple tools to supports event-looping library.',
      extra: { 'wip': true },
    },
  },

  'System': {
    'cache': {
      title: 'Cache',
      description: 'PSR-6 / PSR-16 compatible cache package.',
      extra: { 'wip': true },
    },
    'environment': {
      title: 'Environment',
      description: 'A tool to provider runtime server and browser information.',
      extra: { 'wip': true },
    },
    'event': {
      title: 'Event',
      description: 'PSR-14 compatible event dispatchers.',
      extra: { 'wip': true },
    },
    'filesystem': {
      title: 'Filesystem',
      description: 'Simple library to provide a fluent interface for file operations.',
      extra: { 'wip': true },
    },
    'queue': {
      title: 'Queue',
      description: 'Multiple connection queue management.',
      extra: { 'wip': true },
    },
    'language': {
      title: 'Language',
      description: 'I18n library for PHP, support multiple file formats.',
      extra: { 'wip': true },
    },
  },

  'HTTP': {
    'http': {
      title: 'HTTP',
      description: 'PSR-7 / PSR-15 HTTP message foundation, including Uri, Http client tools.',
      extra: { 'wip': true },
    },
    'stream': {
      title: 'Stream',
      description: 'PSR-7 Streaming library.',
      extra: { 'wip': true },
    },
    'session': {
      title: 'Session',
      description: 'Object oriented interface to manage PHP sessions.',
      extra: { 'wip': true },
    },
    'uri': {
      title: 'Uri',
      description: 'PSR-7 Uri class to manipulate URL data.',
      extra: { 'wip': true },
    },
  },

  'Security': {
    'authentication': {
      title: 'Authentication',
      description: 'A component to support multiple authenticate gateway.',
      extra: { 'wip': true },
    },
    'authorization': {
      title: 'Authorization',
      description: 'Simple ACL component.',
      extra: { 'wip': true },
    },
    'crypt': {
      title: 'Crypt',
      description: 'Openssl and libsodium encryption and password hashing adapters for PHP.',
      extra: { 'wip': true },
    },
    'filter': {
      title: 'Filter',
      description: 'A set of filter / validate rules.',
      extra: { 'wip': true },
    },

  },

  'Database': {
    'database': {
      title: 'Database',
      description: 'Simple but powerful DBAL component.',
      extra: { 'wip': true },
    },
    'query': {
      title: 'Query',
      description: 'A QueryBuilder component, can use without any framework.',
      extra: { 'wip': true },
    },
    'orm': {
      title: 'ORM',
      description: 'An ORM component with DataMapper / Entity pattern.',
      extra: { 'wip': true },
    },
    'pool': {
      title: 'Pool',
      description: 'Simple connection pool library.',
      extra: { 'wip': true },
    },
  },

  'Data': {
    'scalars': {
      title: 'Scalars',
      description: 'PHP scalars objects to enhance data operations.',
      extra: { 'wip': true },
    },
    'data': {
      title: 'Data',
      description: 'Provide Collection object and multi-format structured data encode / decode.',
      extra: { 'wip': true },
    },
  },

  'HTML & Rendering': {
    'dom': {
      title: 'DOM',
      description: 'A DOMDocument wrapper and toolset to build DOM elements.',
      extra: { 'wip': true },
    },
    'html': {
      title: 'HTML',
      description: 'A set of HTML element building helpers.',
      extra: { 'wip': true },
    },
    'form': {
      title: 'Form',
      description: 'A HTML form builder with multiple field types.',
      extra: { 'wip': true },
    },
    'edge': {
      title: 'Edge',
      description: 'A Blade compatible template engine with much extendable interface.',
      extra: { 'wip': true },
    },
    'renderer': {
      title: 'Renderer',
      description: 'A multiple template engine adapter, supports Plates / Blade / Mustache / Twig etc.',
      menu: () => import('./menu/components/renderer'),
      extra: {},
    },
  },

  'Utilities': {
    'test': {
      title: 'Test',
      description: 'Simple test helpers to help unit-testing.',
      extra: { 'wip': true },
    },
    'utilities': {
      title: 'Utilities',
      description: 'Some core and sharing classes for Windwalker components.',
      extra: { 'wip': true },
    },
  },
};

import { computed } from 'vue';
import { componentRoutePrefix } from '../store/routing-store';
import { ComponentDefine, ComponentSet } from '../types/components';

export const flatComponents = computed(() => {
  const components: ComponentDefine[] = [];

  for (const group in groupedComponents) {
    const items = groupedComponents[group];

    for (const name in items) {
      const item = items[name];
      item.alias = name;
      item.group = group;
      components.push(item);
    }
  }

  return components;
});

export async function getComponentsSidebar() {
  const sidebar = [];

  for (const group in groupedComponents) {
    const items = [];

    const components = groupedComponents[group];

    for (const name in components) {
      const component = components[name];

      const menu = (await component?.menu?.())?.default;

      items.push({
        text: component.title,
        link: `/${componentRoutePrefix}/${name}/index`,
        collapsed: true,
        items: menu?.items?.map((item) => {
          item = { ...item };
          item.link = `/${componentRoutePrefix}/${name}/${item.link}`;
          return item;
        }),
      });
    }

    sidebar.push({
      text: group,
      items
    });
  }

  return sidebar;
}
