# Deployment Zip Guidelines

When we prepare a ZIP file for an update (a "patch zip"), we only include the specific folders and files that have been modified during the development session. This ensures the zip is lightweight, quick to upload, and won't overwrite things that haven't changed.

## Standard Folders Usually Included in a Patch
For a typical Laravel/Modules project update, we usually zip the following directories:
1. **`Modules/`**: Contains the majority of the backend logic, controllers, and admin views.
2. **`resources/`**: Contains the frontend Blade views, Vue/React components, and compiled CSS/JS.
3. **`app/`**: Contains standard Laravel models, middleware, and core files.
4. **`database/`**: Important when we have added new migrations or seeders.

## Generating the Patch Zip (Command Line)
The command we use in the terminal to generate the zip file looks like this:

```bash
zip -r phase3_updates.zip Modules app resources database -x "*.git*" "*node_modules*" "*.DS_Store*"
```

### Breakdown of the Command
- **`zip`**: The core command-line utility used to compress files.
- **`-r`**: Stands for "recursive", meaning it will zip the folder and all the files and folders inside it.
- **`phase3_updates.zip`**: The name of the resulting file that will be generated.
- **`Modules app resources database`**: The list of directories we want to include in the zip.
- **`-x "*.git*" "*node_modules*" "*.DS_Store*"`**: The exclusion flags. This tells the zip program to explicitly ignore huge or unnecessary folders (like `node_modules` or `.git`) to keep the file size extremely small.

## How to Apply the Patch on Your Server (cPanel/DirectAdmin/Plesk)
1. **Upload**: Take the `phase3_updates.zip` file and upload it to the root directory of your Laravel application on your server.
2. **Extract**: Right-click the zip file in your server's File Manager and select **Extract**.
3. **Overwrite**: It will extract the folders and automatically overwrite the old files with the newly updated files.
4. **Migrate**: If there were database changes (like in Phase 3), you must SSH into your server and run:
   ```bash
   php artisan migrate
   ```
5. **Clear Cache**: Finally, clear the application cache to ensure the changes take effect immediately:
   ```bash
   php artisan optimize:clear
   ```
