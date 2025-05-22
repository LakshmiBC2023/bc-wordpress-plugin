=== BrainCert Virtual Classroom ===
Contributors: BrainCert
Tags: braincert, virtual classroom, whiteboard, screen sharing, video conference, audio conference, chat, annotate, wolfram alpha, latex, conference, meeting, webinar, live class, share screen, video player, line tools, blended learning, video chat, html5, webrtc
Requires at least: 4.5
Tested up to: 6.8
Stable tag: 2.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Transform the way you educate with BrainCert's Virtual Classroom API. Immerse your users in a world of interactive, dynamic, and effective online learning experiences. Our API is the key to unlocking a new era of educational possibilities, connecting learners globally and providing an unparalleled platform for knowledge exchange.


== Description ==
BrainCert's [Virtual Classroom](https://www.braincert.com/virtual-classroom-api) is an award-winning product to transform any website into a dynamic learning and collaboration platform. With our robust integration capabilities, you can effortlessly incorporate our extensive features into your existing website, LMS, CMS, or application, delivering an unmatched educational experience to your learners. Our White-Label Solution offers unparalleled customization options, including tailored branding, your own domain, a dedicated API endpoint, an SSL certificate, and a variety of theme choices, all within a secure and cohesive educational setting.

Get started by signing up for a free [BrainCert](https://www.braincert.com) account and obtaining your [API key](https://www.braincert.com/app/virtualclassroom). 

For further details, our [Developer documentation](https://www.braincert.com/docs/api/vc/) provides comprehensive guidance. BrainCert's RESTful API grants access to a wealth of Virtual Classroom resources, such as classes, video recordings, and shopping cart features.


== Virtual Classroom Features ==
* Experience crystal-clear Ultra HD audio and video conferencing, powered by WebRTC technology, with resilience for multiple full HD participants.
* Cross-platform WebRTC support for macOS and iOS on Safari 11, Android on Opera and Chrome, and desktops on Chrome and Firefox.
* Accessibility in 50 languages with options for API-driven language setting or attendee selection.
* Effortless cloud-based recording of sessions, available for download in 720p HD, with online sharing and playback for attendees.
* Flexible recording controls allow for manual or automatic capture of classes, with options to download individual recordings or merge them into a single file through a straightforward API call.
* Group screen sharing with HTML5 support, offering HD quality in a tabbed interface, allows for displaying an entire screen or a single application without any downloads or installations.
* Multiple interactive whiteboards featuring a suite of tools for drawing, writing LaTEX math equations, creating shapes and symbols, utilizing line tools, saving snapshots, and document sharing across multiple tabs.
* Secure sharing of documents and presentations, alongside streaming of audio/video files.
* Access to Wolfram|Alpha for computational knowledge and data across various subjects, including science, engineering, and mathematics.
* A comprehensive equation editor, group chat functionality, and advanced annotation tools to interact with uploaded materials.
A responsive 16:9 whiteboard design that adapts to any screen size and browser resolution, ensuring a consistent viewing experience for all participants.


== About BrainCert ==
[BrainCert](https://www.braincert.com) LMS platform is designed to deliver any type of training online, from creating, marketing, and selling courses, tests, and live classes to specific use cases such as creating & delivering assessments for academic and certification needs, compliance training, workplace skills improvement, proctored exams, customer and partner training, as well as real-time collaboration using an integrated virtual classroom. 

* Note: You can use the Virtual Classroom API by itself; it doesn't need the BrainCert LMS subscription. But you will need to subscribe to the API. Check out the prices on our website [here](https://www.braincert.com/pricing?tab=vc-mb)


== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the plugin
1. If you want to show front end live classes in a page, use short code `[class_list_front]` in your page.

[Download](https://www.braincert.com/downloads) plugin documentation.


== Frequently Asked Questions ==

= Where is the plugin documentation? = 
[Download](https://www.braincert.com/downloads) plugin documentation.

= Do you offer free trial? = 
The free plan supports 2 connections (1 instructor + 1 attendee) with a maximum duration of 30 minutes per session. It supports 600 minutes of Free API usage. [Upgrade](https://www.braincert.com/pricing?tab=vc-mb) your API account to use more attendees in a live session, and session duration. All paid API plans comes with premium features such as more attendees in a live class, session recording feature with HD video encoding, etc.,

= What about branding and white-label solution? = 
So glad you asked! With Virtual Classroom API, you can upload your own logo, set colors & theme, change API endpoint to your own domain, and even map SSL certificate.
1. [How to map your external domain with the API endpoint and generate SSL cert](https://support.braincert.com/kb/article/119/steps-to-setup-custom-hostname-and-ssl-certificate-for-your-virtual-classroom-vc-api-domain)


= Helpful links = 
1. [Virtual Classroom knowledge base](https://support.braincert.com/kb/section/33)
1. [Developer guide](https://www.braincert.com/docs/api/vc/)
1. [Documentation](https://www.braincert.com/downloads)
1. [FAQ](https://www.braincert.com/docs/api/vc/faq)

= Can I use my own shopping cart to sell live classes? =
Most certainly. You can use the API to schedule and launch classes, and use your own shopping cart system to collect payments.


= What about security? =
Data security is of utmost importance to us - all our traffic is done over SSL, the web standard for secure data transmission, and files are stored with top-grade secured infrastructure.

== Screenshots ==
1. HTML5 Virtual Classroom
2. Features Overview
3. Low-latency Datacenter Locations Worldwide
4. API Dashboard

== Upgrade Notice ==
=2.7
* Fixed: Frontend class list aligment issues

=2.6
* Fixed: Alignment issues in frontend 

= 2.5 =
* Enhanced security by addressing and resolving all warnings, including those related to XSS vulnerabilities.

= 2.4 =
* Enhanced security by addressing and resolving all warnings, including those related to XSS vulnerabilities.

= 2.3 =
* Secure Redirects: Integrated wp_redirect for safer URL redirections.
* Input Sanitization: Added sanitize_text_field to clean user text inputs.
* Enhanced Output Safety: Implemented esc_html to escape HTML in text inputs.

= 2.2 =
* Fixed: Improved handling of POST and GET variables by implementing htmlentities with ENT_QUOTES | ENT_HTML5 flags for enhanced XSS protection. This update ensures a safer processing environment by effectively escaping HTML entities based on the specified encoding.

= 2.1 =
* Fixed: Deprecated function issues with  newer versions has been sorted out.
* Fixed: Playing issues on some of the live class recordings.

= 2.0 =
* Fixed: Deprecated function issues that were causing conflicts with newer versions of WordPress.
* Resolved: Character encoding issues upon plugin activation.
* Enhanced: Sanitization of text fields to improve security and stability.
* Corrected: Issues with add_filter function implementation for better plugin extensibility and performance.

= 1.30 =
* Fixed Alignment Issues

== Upgrade Notice ==
= 1.29 =
* Fixed Alignment Issues 

== Upgrade Notice ==
= 1.28.1 =
* Fixed class listing issue for the instructors

= 1.27 =
* Fixed minor issues

= 1.26.1 =
* Fixed minor issues

= 1.26 =
* Added search and filter option in change user while scheduling the live class from the backend

= 1.25 = 
* Added DatePicker and Time picker while scheduling the live class
* Added search button in the frontend
* Added filter option to filter the live class with class id
* Added live class description
* Fixed minor design issues with the  latest  WordPress version 5.8.1
* Fixed Fusion theme class conflicts with the launch button.
* Fixed the playing issues on some of the live class recordings.
* Fixed the navigation issue on the frontend

= 1.15 & 1.16 =
* Fix minor issues
* Fixed error when using discount coupons during payment checkout process.

= 1.14 =
* Allow teacher to enter class 30 minutes in advance. Added "Enter to Prepare Class" button to join class.

= 1.13 =
* Introducing new enhanced recorder (isRecordingLayout=1) to record entire browser tab

= 1.12 =
* CURL call method is now removed and using "Requests::post()" instead for improved security

= 1.11 =
* Fix minor issues
* Allow entering 'coupon code' during checkout process of the shopping cart

= 1.10 =
* Fixed installation related errors caused by "vlcr_install" function ($wpdb->prepare) in vlcr_setup.php file.

= 1.9 =
* Introduced new option to cancel both one-time and recurring classes. With recurring class schedule, you can cancel current class in the recurring schedule or all classes in the recurring schedule.

= 1.8 =
* Introduced option "Allow instructor to control recording" when scheduling the live class. Now, you can take away the option for the instructor to stop recording during a live session. This ensures recording is always produced for the entire class duration.

= 1.7 =
* Introduced option to enable or disable private chat feature
* New feature to assign specific teacher to a class when scheduling a class
* New feature to allow teachers to schedule and launch classes from frontend without the need to login to backend using short code [class_schedule_teacher] on a page.
* Optimized code and fixed few other minor issues

= 1.6 =
* Replaced HighCharts with a new chart system for attendance reports
* Fixed PayPal related error such as "The amount is invalid" during checkout
* Fixed incorrect countdown timer for upcoming classes
* Optimized code and fixed few other minor issues

= 1.5 =
* Removed twitter bootstrap to have plugin work with its own native CSS file to avoid breaking WP templates.
* Support for WebRTC in macOS and iOS devices using Safari 11 browser. 
* Introduced support for all new API calls such as auto recording and multiple/single recorded videos
* Introduced options menu with several new features for classes to manage classes easily
* Added support to invite user or group to a class
* Introduced HTML5 video player to view and manage class recordings
* Published class recordings from the backend will now appear in the frontend class details page and all enrolled students to the class can view the recorded videos.
* New class details page with permanent link to the class that can be shared by email or in social media.
* Fix issues with scheduling recurring classes
* Addthis social sharing support in class details page to allow attendees to share the class easily.
* Integrated 'Class Attendance Report' that shows you wide variety of useful data such as duration, time in/out, and attendance report about your attendees along with an interactive graphical layout that can be exported.
* New class landing page design with an aesthetically pleasing responsive countdown timer that lets meeting participants know exactly how much time remains before the live class will begin.
* Use [student_invite]short code in a page to invite students to a class
* Use [class_list_front] short code in a page to list all classes
* Use [class_details id=classId] to list specific class to a page. For example, [class_details id=383922]
* Assign classes to a group. Requires groups plugin https://wordpress.org/plugins/groups/ installed.
* Fixed several minor issues.


== Changelog ==

= 1.14 =
* Allow teacher to enter class 30 minutes in advance. Added "Enter to Prepare Class" button to join class.

= 1.13 =
* Introducing new enhanced recorder (isRecordingLayout=1) to record entire browser tab

= 1.12 =
* CURL call method is now removed and using "Requests::post()" instead for improved security

= 1.11 =
* Fix minor issues
* Allow entering 'coupon code' during checkout process of the shopping cart

= 1.10 =
* Fixed installation related errors caused by "vlcr_install" function ($wpdb->prepare) in vlcr_setup.php file.

= 1.9 =
* Introduced new option to cancel both one-time and recurring classes. With recurring class schedule, you can cancel current class in the recurring schedule or all classes in the recurring schedule.

= 1.8 =
* Introduced new option "Allow instructor to control recording" when scheduling the live class. Now, you can take away the option for the instructor to stop recording during a live session. This ensures recording is always produced for the entire class duration.

= 1.7 =
* Introduced option to enable or disable private chat feature
* New feature to assign specific teacher to a class when scheduling a class
* New feature to allow teachers to schedule and launch classes from frontend without the need to login to backend using short code [class_schedule_teacher] on a page.
* Optimized code and fixed few other minor issues

= 1.6 =
* Replaced HighCharts with a new chart system for attendance reports
* Fixed PayPal related error such as "The amount is invalid" during checkout
* Fixed incorrect countdown timer for upcoming classes
* Optimized code and fixed few other minor issues

= 1.5 =
* Removed twitter bootstrap to have plugin work with its own native CSS file to avoid breaking WP templates.
* Support for WebRTC in macOS and iOS devices using Safari 11 browser. 
* Introduced support for all new API calls such as auto recording and multiple/single recorded videos
* Introduced options menu with several new features for classes to manage classes easily
* Added support to invite user or group to a class
* Introduced HTML5 video player to view and manage class recordings
* Published class recordings from the backend will now appear in the frontend class details page and all enrolled students to the class can view the recorded videos.
* New class details page with permanent link to the class that can be shared by email or in social media.
* Fix issues with scheduling recurring classes
* Addthis social sharing support in class details page to allow attendees to share the class easily.
* Integrated 'Class Attendance Report' that shows you wide variety of useful data such as duration, time in/out, and attendance report about your attendees along with an interactive graphical layout that can be exported.
* New class landing page design with an aesthetically pleasing responsive countdown timer that lets meeting participants know exactly how much time remains before the live class will begin.
* Use [student_invite]short code in a page to invite students to a class
* Use [class_list_front] short code in a page to list all classes
* Use [class_details id=classId] to list specific class to a page. For example, [class_details id=383922]
* Assign classes to a group. Requires groups plugin https://wordpress.org/plugins/groups/ installed.
* Fixed several minor issues.

= 1.4 =
* Changed plugin as per WordPress policy updates addressing security issues and general guidelines.

= 1.3 =
* Optimized code and minor security fixes.

= 1.2 =
* Fixed several minor issues.
* Support for external domain and SSL certificate mapping.
* Improved backend query for timezone conversion and loading time.
* Removed restriction for PM/AM classes that previously was giving error message "Classes cannot continue to next day".
* Added support for both HTML5 Virtual Classroom (https://api.braincert.com/v2) and Flash version (https://api.braincert.com/v1).
* Added support for global datacenter server selection using isRegion API call.
* Added support for auto record sessions using isRecord=2 API call.
* Added support to load only whiteboard or entire app with audio/video, and group chat using isBoard API call.
* Added search filters in component for classes, pricing schemes, discounts, etc.,

= 1.1 =
* Fixed Virtual Classroom launch issues with the latest WordPress releases.

= 1.0 =
* Initial Release.