# flattercms
Flatter. A FlatFile CMS. A lightweight, flat-file Content Management System (CMS) built with PHP and JSON storage. No database required.

## Features

- **No Database**: All content stored in JSON files
- **Simple Admin Interface**: Clean dashboard for content management
- **Flexible Content Types**: Pages, categories, menu entries, and media
- **WYSIWYG Editor**: Rich text editing with TinyMCE
- **Responsive Design**: Mobile-friendly frontend
- **Single User**: Simple authentication for one editor/admin

## Requirements

- PHP 7.4 or higher
- Web server (Apache, Nginx, or local server like MAMP/XAMPP)
- Write permissions for designated folders

## Installation

1. **Upload Files**
   ```
   Upload all files to your web server
   ```

2. **Set Permissions**
   ```bash
   chmod 755 data/ media/ uploads/
   chmod 644 data/*.json
   ```

3. **Configure Admin Access**
   Edit `admin/config.php`:
   ```php
   define('ADMIN_USERNAME', 'your_username');
   define('ADMIN_PASSWORD_HASH', password_hash('your_password', PASSWORD_DEFAULT));
   ```

4. **Access Your Site**
   - Frontend: `https://yourdomain.com/`
   - Admin: `https://yourdomain.com/admin/`

## File Structure

```
/
├── admin/                    # Admin dashboard
│   ├── config.php           # Configuration & credentials
│   ├── dashboard.php        # Admin dashboard
│   ├── create-*.php         # Creation forms
│   ├── edit-*.php           # List views
│   ├── update-*.php         # Edit forms
│   └── upload-media.php     # Media uploader
├── data/                    # JSON storage
│   ├── pages/              # Page content
│   ├── categories/         # Category definitions
│   ├── menus/              # Navigation items
│   └── media.json          # Media library index
├── media/                   # Uploaded files
├── includes/               # Frontend functions
├── functions/              # Shared functions
├── public/                 # Visitor-facing files
│   ├── index.php          # Homepage
│   ├── single.php         # Single page view
│   ├── category.php       # Category listing
│   └── styles.css         # Frontend styling
└── .htaccess              # Security rules
```

## Usage

### Creating Content
1. Log in at `/admin/`
2. Navigate to the desired section (Pages, Categories, Menus, or Media)
3. Use the "Create New" button
4. Fill out the form and save

### Managing Content
- **Pages**: Create, edit, delete articles and content pages
- **Categories**: Organize content with tags
- **Menus**: Build navigation menus linking to pages or categories
- **Media**: Upload and manage images/files

### Frontend Access
- Homepage: `/index.php`
- Single page: `/single.php?id=1`
- Category view: `/category.php?id=1`

## Security Notes

- Default admin path is `/admin/` - consider renaming for production
- JSON files are protected via `.htaccess` rules
- Session-based authentication
- Input sanitization on all forms

## Customization

### Styling
Edit `public/styles.css` for frontend appearance

### Configuration
Modify `admin/config.php` for:
- Admin credentials
- Site URLs
- Security settings

### Extending
Add new content types by:
1. Creating new JSON schema
2. Adding admin interface files
3. Updating frontend display functions

## Troubleshooting

**Permission Errors**
```bash
chmod 755 data pages categories menus media uploads
```

**JSON File Issues**
- Ensure no trailing commas in JSON
- Validate JSON format with online validator
- Check file encoding (UTF-8 without BOM)

**Upload Problems**
- Verify `upload_max_filesize` in php.ini
- Check directory write permissions
- Ensure `media/` directory exists

## License

Open source - free for personal and commercial use.

## Support

For issues and feature requests, please create an issue on GitHub.
