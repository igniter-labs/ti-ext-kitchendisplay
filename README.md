<p align="center">
    <a href="https://github.com/igniter-labs/ti-ext-kitchendisplay/actions"><img src="https://github.com/igniter-labs/ti-ext-kitchendisplay/actions/workflows/pipeline.yml/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/igniterlabs/ti-ext-kitchendisplay"><img src="https://img.shields.io/packagist/dt/igniterlabs/ti-ext-kitchendisplay" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/igniterlabs/ti-ext-kitchendisplay"><img src="https://img.shields.io/packagist/v/igniterlabs/ti-ext-kitchendisplay" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/igniterlabs/ti-ext-kitchendisplay"><img src="https://img.shields.io/packagist/l/igniterlabs/ti-ext-kitchendisplay" alt="License"></a>
</p>

## Introduction

The TastyIgniter Kitchen Display System extension provides a digital solution for managing and displaying orders in a restaurant kitchen. This extension allows kitchen staff to view, organize, and update order statuses in real-time, improving communication and efficiency in the kitchen.

### Features

- **Real-time Order Display** - View incoming orders instantly with automatic updates via event broadcasting
- **Kanban Board Layout** - Organize orders into customizable columns (New, Preparing, Ready, Completed, On Hold)
- **Order Status Management** - Update order statuses with a single click
- **Wait Time Management** - Increment wait time or set custom times for orders
- **On-Hold Status** - Dedicated pause button for holding orders
- **Flexible Filtering** - Filter by location, status, order type, and menu category
- **Responsive Design** - Optimized for desktop and tablet displays
- **Scroll Position Preservation** - Maintains scroll position during automatic refreshes

## Installation

You can install the extension via composer:

```bash
composer require igniterlabs/ti-ext-kitchendisplay -W
```

Run database migrations:

```bash
php artisan igniter:up
```

## Documentation

Full documentation can be found [here](docs/index.md).

The documentation includes:
- Complete feature overview
- Installation and setup instructions
- Configuration guide with examples
- User interface walkthrough
- Troubleshooting guide
- Best practices

## Requirements

- TastyIgniter 4.0 or higher
- PHP 8.1 or higher

## Quick Start

1. Install the extension via composer
2. Run database migrations
3. Navigate to **Tools → Kitchen Display** in the admin panel
4. Create a new Kitchen Display configuration
5. Click on the display to open it and start managing orders

## Key Configuration Options

- **Locations**: Filter orders by specific locations
- **Order Status**: Show only orders with selected statuses
- **Order Types**: Filter by delivery or collection
- **Menu Categories**: Display orders with items from specific categories
- **Board Columns**: Customize which statuses appear in each column
- **Hidden Fields**: Hide sensitive information (customer name, order ID, order type)
- **Orders Limit**: Control how many orders are displayed (default: 20)
- **Display From Date**: Choose the starting date for order display

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Reporting Issues

If you encounter a bug in this extension, please report it using the [Issue Tracker](https://github.com/igniter-labs/ti-ext-kitchendisplay/issues) on GitHub.

## Contributing

Contributions are welcome! Please read [TastyIgniter's contributing guide](https://tastyigniter.com/docs/resources/contribution-guide).

## Security Vulnerabilities

For reporting security vulnerabilities, please see [our security policy](https://github.com/igniter-labs/ti-ext-kitchendisplay/security/policy).

## License

The Kitchen Display System extension is open-source software licensed under the [MIT license](LICENSE).
