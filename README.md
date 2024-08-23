# TWC Version Change

**Version:** 1.0  
**Author:** The WebCoder  
**Text Domain:** twc_version_change  

## Description

TWC Version Change is a WordPress plugin that allows you to append a version string to all URLs on your website, including CSS and JavaScript files. This is particularly useful for cache busting and ensuring that users see the most recent versions of your content. The plugin offers flexible control, allowing you to specify IP addresses for which the version string should be applied, or to enable it for all visitors.

## Features

- **Enable/Disable Versioning:** Easily enable or disable the appending of version strings to URLs from the WordPress admin panel.
- **Version String Input:** Specify the version string to be appended to your URLs (e.g., `1.0.0`).
- **IP Address-Specific Versioning:** Apply versioning only to specific IP addresses or universally across all users.
- **CSS and JS Versioning:** Append version strings to CSS and JavaScript files to ensure users load the latest versions.
- **User-Friendly Settings:** Manage all plugin settings from a dedicated settings page in the WordPress admin dashboard.

## Installation

1. **Download the Plugin:**
   - Download the plugin from the [GitHub releases](https://github.com/maheshkamani309/wp-site-version/releases) or clone the repository using the command:
     ```bash
     git clone https://github.com/maheshkamani309/wp-site-version.git
     ```

2. **Upload to WordPress:**
   - Upload the `twc-version-change` folder to the `/wp-content/plugins/` directory.

3. **Activate the Plugin:**
   - Go to the WordPress admin panel and navigate to `Plugins`. Find "TWC Version Change" in the list and click "Activate".

4. **Configure the Plugin:**
   - Once activated, navigate to `Settings > TWC Version Change` to configure the plugin settings according to your needs.

## Usage

- **Enable Versioning:**
  - Check the "Enable Versioning" option to activate the appending of version strings to all URLs.

- **Version String:**
  - Enter the version string (e.g., `1.1.1`) that you wish to append to your URLs.

- **IP Address-Specific Versioning:**
  - Enter a comma-separated list of IP addresses to which the versioning should be applied. Leave it blank to apply to all users.

- **CSS and JS Versioning:**
  - Enable the "Versioning to CSS and JS" option to append version strings to your CSS and JavaScript files.

## Hooks and Filters

- **Filters:**
  - `post_type_link`, `post_link`, `page_link`: Modify post and page URLs to append the version string.
  - `script_loader_src`, `style_loader_src`: Modify script and style URLs to append the version string.

- **Actions:**
  - `admin_init`: Register plugin settings.
  - `admin_menu`: Register the options page in the admin panel.
  - `template_redirect`: Redirect requests to URLs with the appended version string if conditions are met.

## Changelog

**1.0**
- Initial release with core functionalities: URL versioning, IP address-specific versioning, and CSS/JS versioning.

## License

This plugin is licensed under the [MIT License](LICENSE).

## Contributing

If you would like to contribute to the development of this plugin, please fork the repository and submit a pull request. Issues and feature requests can be submitted via the [GitHub issue tracker](https://github.com/maheshkamani309/wp-site-version/issues).

## Support

For any questions or support, please open an issue on the GitHub repository or contact the author at [https://the-webcoder.com](https://the-webcoder.com).

