const fs = require('fs');

const pluginsFolder = __dirname + "/../../plugins/";
const versionsFile  = __dirname + "/../../../wp-includes/version.php";

// Set up array of site info
let siteInfo = {};

// File specs
siteInfo['bomFormat']   = 'CycloneDX';
siteInfo['specVersion'] = '1.3';
siteInfo['version']     = '1';

let components = [];

let wpVersion;
let versionsFileData = fs.readFileSync(versionsFile, 'utf8');
let versionsFileDataLines = versionsFileData.split('\n');
versionsFileDataLines.forEach(line => {
	if ( line.includes( '$wp_version = ' ) ) {
		wpVersion = line.replace("$wp_version = '","");
		wpVersion = wpVersion.replace("';","");
	}
});

// WordPress itself
let wordPressInfo = {};
wordPressInfo['type']    = 'application';
wordPressInfo['bom-ref'] = 'pkg:deb/debian/wordpress@' + wpVersion;
wordPressInfo['name']    = 'wordpress';
wordPressInfo['version'] = wpVersion;
wordPressInfo['purl']    = 'pkg:deb/debian/wordpress@' + wpVersion;

components.push(wordPressInfo);

// Read plugins folder
const folders = fs.readdirSync( pluginsFolder );
folders.forEach(folder => {
	const folderPath = pluginsFolder + folder;
	const folderPathStatSync = fs.statSync(folderPath);

	// Go to next if this is not a folder
	if( !folderPathStatSync.isDirectory() ) return;

	// Get all files in each plugin folder
	const pluginFiles = fs.readdirSync( folderPath );
	pluginFiles.forEach(pluginFile => {
		const pluginFolderPath = folderPath + '/' + pluginFile;
		// If this is not a PHP file, go to next.
		if ( !pluginFolderPath.includes( '.php' ) ) return;
		const data = fs.readFileSync( pluginFolderPath, 'utf8' );
		if ( !data.includes( 'Plugin Name:' ) ) return;
		if ( !data.includes( 'Version:' ) ) return;
		if ( !data.includes( 'Text Domain:' ) ) return;

		let 
			plugin = {},
			pluginExternal = {},
			pluginName,
			pluginVersion,
			pluginTextDomain,
			pluginAuthor,
			pluginDescription,
			pluginURI,
			pluginAuthorURI,
			pluginUpdateURI;

		let lines = data.split('\n');
		lines.forEach(line => {
			if ( line.includes( 'Plugin Name:' ) ) {
				pluginName = line.replace('Plugin Name:','');
				pluginName = pluginName.replace('* ','');
				pluginName = pluginName.trim();
			}
			if ( line.includes( 'Version:' ) ) {
				pluginVersion = line.replace('Version:','');
				pluginVersion = pluginVersion.replace('* ','');
				pluginVersion = pluginVersion.trim();
			}
			if ( line.includes( 'Text Domain:' ) ) {
				pluginTextDomain = line.replace('Text Domain:','');
				pluginTextDomain = pluginTextDomain.replace('* ','');
				pluginTextDomain = pluginTextDomain.trim();
			}
			if ( line.includes( 'Author:' ) ) {
				pluginAuthor = line.replace('Author:','');
				pluginAuthor = pluginAuthor.replace('* ','');
				pluginAuthor = pluginAuthor.trim();
			}
			if ( line.includes( 'Description:' ) ) {
				pluginDescription = line.replace('Description:','');
				pluginDescription = pluginDescription.replace('* ','');
				pluginDescription = pluginDescription.trim();
			}
			if ( line.includes( 'Plugin URI:' ) ) {
				pluginURI = line.replace('Plugin URI:','');
				pluginURI = pluginURI.replace('* ','');
				pluginURI = pluginURI.trim();
			}
			if ( line.includes( 'Author URI:' ) ) {
				pluginAuthorURI = line.replace('Author URI:','');
				pluginAuthorURI = pluginAuthorURI.replace('* ','');
				pluginAuthorURI = pluginAuthorURI.trim();
			}
			if ( line.includes( 'Update URI:' ) ) {
				pluginUpdateURI = line.replace('Update URI:','');
				pluginUpdateURI = pluginUpdateURI.replace('* ','');
				pluginUpdateURI = pluginUpdateURI.trim();
			}
		});

		plugin['type']    = 'application';
		plugin['bom-ref'] = 'pkg:wordpress/plugins/' + pluginTextDomain + '@' + pluginVersion;
		plugin['name']    = pluginTextDomain;
		plugin['version'] = pluginVersion;
		plugin['purl']    = 'pkg:wordpress/plugins/' + pluginTextDomain + '@' + pluginVersion;

		if ( pluginAuthor !== '' ) {
			plugin['author'] = pluginAuthor;
		}

		if ( pluginDescription !== '' ) {
			plugin['description'] = pluginDescription;
		}

		if ( pluginDescription !== '' ) {
			plugin['description'] = pluginDescription;
		}

		components.push(plugin);
	});
});

siteInfo['components'] = components;

var jsonContent = JSON.stringify(siteInfo);

fs.writeFile("bom.json", jsonContent, 'utf8', function (err) {
    if (err) {
        console.log("An error occured while writing JSON Object to File.");
        return console.log(err);
    }
 
    console.log("JSON BOM file has been saved.");
});