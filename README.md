# Epgrafico Theme

Epgrafico theme for WooThemes StoreFront WooCommerce theme.

## Getting Started

This theme is designed to be used as a theme for the WooCommerce StoreFront theme which you can download for free below.

* [WooCommerce StoreFront Theme](https://wordpress.org/themes/storefront/)
* [StoreFront Documentation](http://docs.woothemes.com/documentation/themes/storefront/)
* [StoreFront Extensions](http://www.woothemes.com/product-category/storefront-extensions/)
* [wpack.io](https://github.com/swashata/wp-webpack-script/blob/master/README.md)

### Development Server

Setup the correct host and proxy on 'wpackio.server.js'.

```
  host: 'epgrafico.local',
  // Your WordPress development server address
  // This is super important
  proxy: 'https://epgrafico.local',
```

Start the development server by running.

- `npm run build`
- `yarn run build`

Once run the deployment server should take you to host:3000 on the web browser.

Development server is setup with **Hot Module Replacement (_HMR_)**.
We can see things load live without page refresh.

### Translate

Create a .pot file on `languages` folder using:

- `npm run translate`
- `yarn translate`

### Build

Create production build by running:

- `npm run build`
- `yarn build`

### Archive

Create a production archive on the `packages` folder using:

- `npm run archive`
- `yarn archive`

Our plugin/theme is now ready to be used.
Upload the created .zip file or folder inside `packages` folder.

---
