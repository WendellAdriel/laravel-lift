import { defineConfig } from 'vitepress'

const configuredBase = process.env.VITEPRESS_BASE ?? '/'
const base = configuredBase.endsWith('/') ? configuredBase : `${configuredBase}/`
const siteUrl = 'https://laravel-lift.wendelladriel.com'
const description = 'Take your Eloquent Models to the next level'

export default defineConfig({
  title: 'Lift',
  description,
  base,
  head: [
    ['link', { rel: 'preconnect', href: 'https://fonts.googleapis.com' }],
    ['link', { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' }],
    ['link', { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Cascadia+Code:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap' }],
    ['meta', { property: 'og:type', content: 'website' }],
    ['meta', { property: 'og:title', content: 'Lift for Laravel' }],
    ['meta', { property: 'og:description', content: description }],
    ['meta', { property: 'og:image', content: `${siteUrl}/laravel-lift-banner.png` }],
    ['meta', { name: 'twitter:card', content: 'summary_large_image' }],
    ['meta', { name: 'twitter:title', content: 'Lift for Laravel' }],
    ['meta', { name: 'twitter:description', content: description }],
    ['meta', { name: 'twitter:image', content: `${siteUrl}/laravel-lift-banner.png` }],
  ],
  markdown: {
    theme: {
      light: 'catppuccin-latte',
      dark: 'catppuccin-mocha',
    },
  },
  themeConfig: {
    sidebar: [
      { text: 'Overview', link: '/' },
      {
        text: 'Getting Started',
        items: [
          { text: 'Installation', link: '/getting-started/installation' },
          { text: 'Changelog', link: '/getting-started/changelog' },
        ],
      },
      {
        text: 'Attributes',
        items: [
          { text: 'Cast', link: '/attributes/cast' },
          { text: 'Column', link: '/attributes/column' },
          { text: 'Config', link: '/attributes/config' },
          { text: 'DB', link: '/attributes/db' },
          { text: 'Events', link: '/attributes/events' },
          { text: 'Fillable', link: '/attributes/fillable' },
          { text: 'Hidden', link: '/attributes/hidden' },
          { text: 'Ignore Properties', link: '/attributes/ignore-properties' },
          { text: 'Immutable', link: '/attributes/immutable' },
          { text: 'Primary Key', link: '/attributes/primary-key' },
          { text: 'Relationships', link: '/attributes/relationships' },
          { text: 'Validation', link: '/attributes/validation' },
          { text: 'Watch', link: '/attributes/watch' },
        ],
      },
      {
        text: 'Methods',
        items: [
          { text: 'castAndCreate', link: '/methods/castandcreate' },
          { text: 'castAndFill', link: '/methods/castandfill' },
          { text: 'castAndSet', link: '/methods/castandset' },
          { text: 'castAndUpdate', link: '/methods/castandupdate' },
          { text: 'createValidationMessages', link: '/methods/createvalidationmessages' },
          { text: 'createValidationRules', link: '/methods/createvalidationrules' },
          { text: 'customColumns', link: '/methods/customcolumns' },
          { text: 'defaultValues', link: '/methods/defaultvalues' },
          { text: 'immutableProperties', link: '/methods/immutableproperties' },
          { text: 'updateValidationMessages', link: '/methods/updatevalidationmessages' },
          { text: 'updateValidationRules', link: '/methods/updatevalidationrules' },
          { text: 'validationMessages', link: '/methods/validationmessages' },
          { text: 'validationRules', link: '/methods/validationrules' },
          { text: 'watchedProperties', link: '/methods/watchedproperties' },
        ],
      },
      {
        text: 'Commands',
        items: [
          { text: 'lift:migration', link: '/commands/lift-migration' },
        ],
      },
    ],
    search: {
      provider: 'local',
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/wendelladriel/laravel-lift' },
    ],
    editLink: {
      pattern: 'https://github.com/wendelladriel/laravel-lift/edit/main/docs/:path',
      text: 'Edit this page on GitHub',
    },
  },
})
