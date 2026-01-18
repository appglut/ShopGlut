# ShopGlut WooCommerce Category Shortcode

## Quick Start

### Basic Usage

```
[shopglut_woo_category id="your-category-slug"]
```

### With WooTemplate

```
[shopglut_woo_category id="electronics" template="my-template-id"]
```

### Full Example

```
[shopglut_woo_category
    id="electronics"
    template="product-card-1"
    title="1"
    desc="1"
    cols="4"
    colspad="2"
    colsphone="1"
    items_per_page="12"
    orderby="price"
    order="ASC"
    toolbar="1"
    paging="1"
    async="1"
]
```

## Key Features

✅ Query products from WooCommerce categories
✅ Multiple category support with operators (IN, NOT IN, AND)
✅ WooTemplates integration for custom product display
✅ Responsive grid system (desktop/tablet/mobile)
✅ Advanced toolbar with search, sort, and filter
✅ Pagination with async loading option
✅ Customizable icons, titles, and descriptions
✅ Bootstrap-like styling

## Important Parameters

| Parameter | Description | Example |
|-----------|-------------|---------|
| `id` | **Required.** Category slug(s) | `id="electronics"` or `id="cat1,cat2"` |
| `template` | WooTemplate ID for custom display | `template="my-template-id"` |
| `cols` | Desktop columns (1-12) | `cols="4"` |
| `items_per_page` | Products per page | `items_per_page="12"` |
| `orderby` | Sort field | `orderby="price"` |
| `toolbar` | Show toolbar | `toolbar="1"` or `toolbar="compact"` or `toolbar="0"` |
| `paging` | Show pagination | `paging="1"` |
| `async` | Async pagination | `async="1"` |

## Documentation Files

- **CATEGORY_SHORTCODE_DOCUMENTATION.md** - Complete parameter reference
- **TESTING_GUIDE.md** - Step-by-step testing instructions
- **README.md** - This file (quick reference)

## Troubleshooting

### Shortcode Not Showing?

1. **Check WooCommerce is active**
2. **Verify category slug is correct** (go to Products → Categories)
3. **Ensure products exist in the category**
4. **Test with simple shortcode first:**
   ```
   [shopglut_woo_category id="uncategorized"]
   ```

### Template Not Working?

1. **Verify template ID exists** (ShopGlut → WooTemplates)
2. **Test without template first:**
   ```
   [shopglut_woo_category id="electronics"]
   ```
3. **Check template has valid HTML/CSS**

## Files Structure

```
src/tools/shortcodeShowcase/
├── CategoryShortcode.php              # Main handler
├── assets/
│   ├── css/
│   │   └── category-shortcode.css     # Styles
│   └── js/
│       └── category-shortcode.js      # Async loading
├── CATEGORY_SHORTCODE_DOCUMENTATION.md # Full docs
├── TESTING_GUIDE.md                    # Testing steps
└── README.md                           # This file
```

## Integration

The shortcode is automatically initialized in:
- `ShortcodeShowcase.php` (line 24-26)
- Visible in WordPress admin: **ShopGlut → Shortcode Showcase**

## Support

For issues or questions:
1. Check TESTING_GUIDE.md for debugging steps
2. Enable WP_DEBUG and check debug.log
3. Verify all prerequisites are met

## Version

Current version: 1.0.0
Compatible with: ShopGlut 1.6.6+
Requires: WooCommerce 3.0+
