=== WP Client File Share ===
Contributors: Aaron Reimann, Adam Walker
Donate link: http://sideways8.com/donate
Tags: file share, client, share, file upload, frontend, front, end, upload
Requires at least: 3.2
Tested up to: 3.2.1
Stable 1.0

Share files between Admins and clients (users).  Users receive their "private" page to upload, and Admins can post files for the client to download.

== Description ==

This creates a way for Admins and clients (WP users) to share files. The client is able to login and access *their* page and upload files for an Admin. An Admin can also upload a file on the client's page so the client can download from there. The client page is able to access only their page, if they try to get to the backend, they are redirected to their page. An admin is able to get a list of the users that have the Role of "file sharer" on the plugin's admin page, from their the Admin selects the user, goes to their page and uploads to the clients page.

== Installation ==

Unzip and upload the plugin into the plugins directory and then activate it. Once the plugin has been activated a "Private Page" is created called "WP Client File Share" that the Admin owns.  The Admin will then have to create the user and give them a role of "File Sharer".  When the user is created, let us say the user is "client1", a page is created called "client′s File Share Page" and it is a child page of "WP Client File Share". An Admin will go to the user's page to upload the files for the client, and the client/user will login and upload their files on the same page.

== Frequently Asked Questions ==

= Why don't you have a lot of FAQ's?

This is the first release.

= Is this secure?

Yes and no.  Is an SFTP server more secure? Yes.  Can client's easily install a FTP program and upload to your server? No.

= How can I make this more secure?

If you are worried about one of your clients sending a URL to everyone they know about a file that you uploaded, then don't use this plugin. This plugin http://wordpress.org/extend/plugins/download-protect might be a good solution (yet you would still have to use this plugin to limit your user to one page, but would not use the upload feature in the plugin).

= Can you add feature Z, Y, and Z and can you support me?

We can add any feature, but it is hard for us to dedicate time to things that do not bring in direct income.  Donations go a long way though.

== Screenshots ==

Please see the screencast to see the plugin in action.

== Changelog ==

= 1.0 =
* Initial release
