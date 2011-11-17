=== WP Client File Share ===
Contributors: Aaron Reimann, Adam Walker
Donate link: http://sideways8.com/plugins/wp-client-file-share
Tags: file, share, client, private, frontend, upload, front, end
Requires at least: 3.2
Tested up to: 3.2.1
Stable 1.3.1

Share files between Admins & users.  Users get a "private" page to upload / Admin can post files that user.

== Description ==

This creates a way for Admins and clients (WP users) to share files. The client is able to login and access *their* page and upload files for an Admin. An Admin can also upload a file on the client's page so the client can download from there. The client page is able to access only their page, if they try to get to the backend, they are redirected to their page. An admin is able to get a list of the users that have the Role of "file sharer" on the plugin's admin page, from their the Admin selects the user, goes to their page and uploads to the clients page.

== Installation ==

1. Unzip and upload the plugin into the plugins directory and then activate it.
1. Once the plugin has been activated a "Private Page" is created called "WP Client File Share" that the Admin owns.
1. The Admin will then have to create the user and give them a role of "File Sharer".
1. When the user is created, let us say the user is "client1", a page is created called "client′s File Share Page" and it is a child page of "WP Client File Share". 
1. An Admin will go to the user's page to upload the files for the client, and the client/user will login and upload their files on the same page.

== Frequently Asked Questions ==

= The user's pages are not being created, what do I do?

We have had a few situations where the individual pages have not been created when the user is created.  There have been a few plugins that have conflicted, disable all plugins and try again.

= Is this secure?

Yes and no.  Is an SFTP server more secure? Yes.  Can client's easily install a FTP program and upload to your server? No.

= Can you add feature Z, Y, and Z and can you support me?

We can add any feature, but it is hard for us to dedicate time to things that do not bring in direct income.  Donations go a long way though.

== Screenshots ==

Please see the screencast to see the plugin in action:  <a href="http://sideways8.com/plugins/wp-client-file-share/">http://sideways8.com/plugins/wp-client-file-share/</a>

== Changelog ==

= 1.3.1 =
* Fixes redirect issue for logged in clients

= 1.3.0 =
* Adds newsletter signup on the backend
* Adds donate button on the backend
* Adds style for the backend UI 

= 1.2.0 =
* Adds the option to disable "client/user" form upload so only admin see the form on the front-end
* Notification system when a file is uploaded
* Specify number of files to list option

= 1.1.0 =
* Adds limited integration with another plugin (Download Protect)

= 1.0.2 =
* Bugfix with admin options page

= 1.0.1 =
* Bugfix with admin bar

= 1.0 =
* Initial release
