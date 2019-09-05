# Description 

**WP SafePassword** will allow your wordpress users to authenticate with **SafePassword**.

<a href="https://safepassword.net">SafePassword</a> is a service that protect your website users.

Practically if you will connect your website to this service your users will not need anymore a password for their accounts.
They will receive a temporary password on own phone/email when they want to authenticate.(only if they enable 'Login with SafePassword' option)

Each user can **enable/disable** this service from **wp-admin -> Profile page -> 'Login with SafePassword'** section.

**Attention !** For using this plugin you must be agree with <a href="https://safepassword.net/tos-for-users">SafePassword's terms of service</a>. ( including <a href="https://safepassword.net/privacy">Privacy Policy</a> ).

<h3>What is SafePassword</h3>

It’s an authentication system that generates really strong, safe and temporary passwords, example: #B$53f5@.
- Strong: It would take 9 hours to someone to hack it and the time you have for using it, is only 10 minutes.
- Temporary: Once you log in, the password auto-destroys.
- Safe: You have 3 tries, after these your password expires and you would have to request another one.
- Reliable: You don’t need to worry about SafePassword.net shutting down, it’s hosted on Amazon servers.

<h3>How it works</h3>

In the moment that you connect your site with SafePassword.net, everyone (users & admins ) will have the option to choose between logging in with their usual password, or with SafePassword.
And so, if someone tries to break one of your user’s password, it will be almost impossible, because there will be no more passwords (if logging in with SafePassword is enabled)
For logging in in your wp-admin dashboard, you just have to go to your website login area and request your SafePassword. As simple as logging in everywhere else.

<h3>Why is it almost impossible to crack a SafePassword?</h3>

- Our generated passwords contain 8 random characters that are randomly chosen between 94 different characters (111 billion combinations).
- To crack one of our passwords, someone should guess the password in maximum 3 attempts, or else it will auto-destroy.
- As said before, it would take ±9 hours to crack one of our passwords, and the hacker has a window of time of 10 minutes after which the password expires.
- Beyond all of these things, that someone should also guess the moment when you’re trying to connect (which is almost impossible), and use your password before you.

# Installation 

Upload the folder **wp-safepassword** from the zip file to "wp-content/plugins" and activate the plugin in your admin panel or upload **wp-safepassword.zip** in the "Add new" section.

# Frequently Asked Questions

- **How to connect with SafePassword ?**

- After the plugin activation you'll be redirected to the SafePassword dashboard, so that you can finalize the connection.
You'll have to enter your e-mail address, accept our terms & conditions and then click on the **Connect** button.

- **How to Enable SafePassword for your user ?**

- You must go in **wp-admin -> Users -> Your Profile** and then find the **Login with SafePassword** section, it should be at the end of the page.
Then click on the Enable button, enter your email, your phone number, select the offer that suits you the best and click on **Save**.