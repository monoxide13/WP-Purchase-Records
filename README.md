# WP-Purchase-Records

## Purpose:
A wordpress plugin that creates a custom database and post type, allowing the logging of purchase orders and items.

## Reason for design:
I wrote this plugin to be able to log purchases for a project of mine. I wanted a way to be able to record, search, and sum transactions. This way I can easily add up the total cost of the project, cost of tools, tax paid, shipping paid, and lots of other stuff. I wanted to save this information into it's own table vs metadata for easy access and use.

## Usage:
1. Download and extract this into your wordpress plugin folder typically, at wp-content/plugins.
2. Activate the plugin in wordpress.

## Options:
- database_prefix
  - Sets the prefix for the database. This pretty much just makes it easier to identify when browsing the database.

## Shortcodes:
- [pr-order]
  - args:
    - orderid
- [pr-item]
  - args:
    - itemid
- [pr-cost]
  - orderid  (optional, default 0)
  - itemid  (optional, default 0)
  - type=[parts, tools, shipping, tax, total]  (optional, default 'total')
  - If itemid is specified, it will force orderid to 0. If no orderid is specified, it will default to 0 and total all orders.

