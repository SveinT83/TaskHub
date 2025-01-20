The purpose of this module is to post to facebook through taskhub.
Important steps to have this work is the following, in the .env file (not included as it contains facebook app secret) add the following:

FACEBOOK_APP_ID=
FACEBOOK_APP_SECRET=
FACEBOOK_REDIRECT_URI=

Add the relevant info, do your standard install routine and check that the routes work fine.
As part of this module, some changes had to be made to root, these changes are located in web.php, services.php, socialitelogincontroller and facebookauthcontroller.


