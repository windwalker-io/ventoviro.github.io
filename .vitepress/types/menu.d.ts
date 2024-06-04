export interface ComponentMenu {
  name: string;
  alias?: string;
  group?: string;
  items: MenuItem[];
}

export interface MenuItem {
  text: string;
  link: string;
  [name: string]: anmy;
}
