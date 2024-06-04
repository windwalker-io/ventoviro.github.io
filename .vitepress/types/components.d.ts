import { ComponentMenu } from './menu';

export interface ComponentSet {
  [group: string]: Record<string, ComponentDefine>
}

export interface ComponentDefine {
  title: string;
  description: string;
  alias?: string;
  group?: string;
  menu?: () => Promise<{ default: ComponentMenu }>;
  extra?: any,
}
