# Lock Posts


**INACTIVE NOTICE: This plugin is unsupported by WPMUDEV, we've published it here for those technical types who might want to fork and maintain it for their needs.**

## Translations

Translation files can be found at https://github.com/wpmudev/translations

## About

Lock Posts lets a network super admin 'lock' or 'fix' posts so they can't be edited by the users of that site – even administrators.

### Block post editing

Perfect for stopping school assignments from being edited after submission or adding a post to a network that you don't want removed. Lock Posts adds a post status module that's only available to post or page admins. Easily toggle the status between locked and unlocked. 

![Lock Posts Editor](http://premium.wpmudev.org/wp-content/uploads/2009/06/lock-posts-1150-editor.png)

 Quickly toggle between lock status

 Quickly see what posts have been locked by the super admin from 'All Posts'. 

![Lock Posts Locked](http://premium.wpmudev.org/wp-content/uploads/2009/06/lock-posts-1150-locked.png)

 Status information conveniently shown in 'All Posts'

 Take control of what posts can be edited across an entire Multisite network with Lock Posts.

## Usage

### To Get Started:

Start by reading the [Installing Plugins](https://premium.wpmudev.org/wpmu-manual/installing-regular-plugins-on-wpmu/) section in our comprehensive [WordPress and WordPress Multisite Manual](https://premium.wpmudev.org/wpmu-manual/) if you are new to WordPress. _Important notes:_

*   If you have an older version of the plugin installed in /mu-plugins/ please delete it.
*   When this plugin is activated on a single site, only admins have the capability to lock/unlock posts.
*   When activated on any site in a multisite install, it is activated on all sites, but only the network admin can lock/unlock posts.

### To Use:

Log into your site as an admin (or network-admin) user. Then go to any post, page or custom post type you want to lock. Click the "Edit" link and scroll to the bottom of the edit screen. 

![Lock Posts Editor](https://premium.wpmudev.org/wp-content/uploads/2009/06/lock-posts-1150-editor.png)

 Once a post has been locked, you'll see that indicated in a new column on the All Posts screen. 

![Lock Posts Locked](https://premium.wpmudev.org/wp-content/uploads/2009/06/lock-posts-1150-locked.png)

 Any other user who tries to edit a locked post will get a friendly message informing them that it has been locked. 

![Lock Posts Locked Message](https://premium.wpmudev.org/wp-content/uploads/2009/06/lock-posts-1150-locked-message.png)

 Please note the following:

*   On a network install, only super-admin can lock/unlock posts. Individual site admins and other roles cannot.
*   On a single site install, site admin can lock/unlock, other roles cannot.
