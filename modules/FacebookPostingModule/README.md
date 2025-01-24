The purpose of this module is to post to facebook through taskhub.
Important steps to have this work is the following, in the .env file (not included as it contains facebook app secret) add the following outside of the standard changes:

FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
FACEBOOK_REDIRECT_URI=

Add the relevant info, do your standard install routine (Migrations including within the module itself, getting vendor sorted, etc.) and check that the routes work fine. Within only the module and root as it exists within the repo that should be 76 routes, if implemented in anything else, that number would of course be different.

As part of this module, some changes had to be made to root, these changes are located in web.php and services.php as well as the added files socialitelogincontroller and facebookauthcontroller.

When you have made sure that everything works fine, try to open the main dashboard and see if the Facebook Module item has appeared in the Admin settings with a small facebook icon, if not, migrate the module.

This should take you to the Module as it currently exists, with it's name, a link back to the dashboard as well as a centred login to facebook prompt. If everything is setup correctly, this allows you to connect to and use facebook within the module itself.
