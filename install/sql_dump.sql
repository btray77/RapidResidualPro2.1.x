DROP TABLE IF EXISTS `rrp_admin_session`;
CREATE TABLE IF NOT EXISTS `rrp_admin_session` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `last_export` int(11) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=68 ;

INSERT INTO `rrp_admin_session` (`admin_id`, `hash`, `timestamp`, `last_export`) VALUES
(10, 'c88887c1a4a566e9310d4fe6840695e4', 1305874515, 0);

DROP TABLE IF EXISTS `rrp_admin_settings`;
CREATE TABLE IF NOT EXISTS `rrp_admin_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastlogin` int(11) DEFAULT NULL,
  `webmaster_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=68 ;

INSERT INTO `rrp_admin_settings` (`id`, `username`, `password`, `lastlogin`, `webmaster_email`, `role`, `status`) VALUES
(1, 'yasir', 'e10adc3949ba59abbe56e057f20f883e', 1307613888, 'yasir.rehman@live.com', 1, 1);

DROP TABLE IF EXISTS `rrp_affiliate`;
CREATE TABLE IF NOT EXISTS `rrp_affiliate` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `index_page` longtext COLLATE utf8_unicode_ci,
  `psponder` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agreement` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `rrp_affiliate` (`id`, `index_page`, `psponder`, `agreement`) VALUES
(1, '\r\n<p><span style="font-weight: bold;">Thank you for your interest in becoming our affiliate! </span></p>\r\n\r\n\r\n<p>It only takes a minute to sign up and you can start generating affilaite sales as soon as you complete the form below.</p>\r\n\r\n\r\n<p>Upon submitting the affiliate signup form you will be logged into our affiliate center and will be able to browse through our marketplace of products. The marketplace lists each products commission structure and your personal affiliate link. You can then use those links to promote our products and earn commissions which will be paid directly into your PayPal account.</p>\r\n\r\n\r\n<p><span style="font-weight: bold;">Commissions: </span></p>\r\n\r\n\r\n<p>Commissions work a little differently than you may be used to. Instead of earning a percentage of each sale you generate you will be paid 100% of all sales that you are owed commissions on.</p>\r\n\r\n\r\n<p>With a normal affiliate program you have to wait for your commission payments. For example, if you promote a product with 50% commissions you earn half of all sales generated through your affiliate id. However, you have to wait for weeks or longer for the site owner to decide to pay out the commissions. You are at the mercy of the site owner and if they decide to not pay out the commissions then your out money and time.</p>\r\n\r\n\r\n<p>With our commission structure you get paid 100% of the sale price for a percentage of sales. For example, if you promote a product with 50% commissions you will recieve the full product sales price for every other sale you generate. The difference is the full sales price goes directly to you for every sale where you are owed commissions. This works out to exactly the same amount of potential income for you, but you do not have to wait for your commissions. They are deposited directly into your PayPal account at the time of the sale! </p>\r\n\r\n\r\n<p><span style="font-weight: bold;">Payment of Commissions:</span></p>\r\n\r\n\r\n<p>Commissions are sent directly to your PayPal account at the time of sale. In order to become our affiliate you must have either a Premier or Business PayPal account. PayPal limits the payment type and amout of funds that Personal accounts can recieve so Premier or Business accounts are required to become and affiliate.</p>', '0', 59);

DROP TABLE IF EXISTS `rrp_amazon_cloud_front`;
CREATE TABLE IF NOT EXISTS `rrp_amazon_cloud_front` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `distribution_id` varchar(255) NOT NULL,
  `buket_id` varchar(255) NOT NULL,
  `domain_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_amazon_s3`;
CREATE TABLE IF NOT EXISTS `rrp_amazon_s3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `short_name` varchar(100) NOT NULL,
  `custom_token` varchar(100) NOT NULL,
  `content_access` enum('Public','Private') DEFAULT NULL,
  `creation_date` varchar(100) DEFAULT NULL,
  `content_size` int(11) DEFAULT NULL,
  `bucket_id` varchar(100) NOT NULL,
  `content_id` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `auto_play` enum('Yes','No') NOT NULL,
  `player_controls` enum('Yes','No') NOT NULL,
  `full_screen` enum('Yes','No') NOT NULL,
  `download_link` enum('Yes','No') NOT NULL,
  `player_height` int(11) NOT NULL,
  `player_width` int(11) NOT NULL,
  `download_graphic` varchar(50) NOT NULL,
  `player_color` varchar(20) NOT NULL,
  `buffer_time` varchar(20) NOT NULL,
  `description_page` text NOT NULL,
  `sales_page` text NOT NULL,
  `sold_page` text NOT NULL,
  `charge_to_view` enum('Yes','No') NOT NULL,
  `price_to_view` float NOT NULL,
  `paypal_button` varchar(100) NOT NULL,
  `alert_pay_button` varchar(100) NOT NULL,
  `google_checkout_button` varchar(100) NOT NULL,
  `clickbank_button` varchar(100) NOT NULL,
  `published` text,
  `publish_date` varchar(100) NOT NULL,
  `unpublish_date` varchar(100) NOT NULL,
  `allow_ripping_downloading` enum('Yes','No') NOT NULL,
  `custom_fields` varchar(500) NOT NULL,
  `storage_location` varchar(100) NOT NULL,
  `hidden_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_click_stats`;
CREATE TABLE IF NOT EXISTS `rrp_click_stats` (
  `id` int(11) NOT NULL auto_increment,
  `visited_date` date NOT NULL default '0000-00-00',
  `referrer` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `ip` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `product` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `cookies_ref` varchar(255) collate utf8_unicode_ci NOT NULL,
  `ip_ref` varchar(255) collate utf8_unicode_ci NOT NULL,
  `item_type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_comments`;
CREATE TABLE IF NOT EXISTS `rrp_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `page` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment` longtext COLLATE utf8_unicode_ci,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `checked` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_comments_reply`;
CREATE TABLE IF NOT EXISTS `rrp_comments_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `postedby` int(11) NOT NULL,
  `postedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_coupon_codes`;
CREATE TABLE IF NOT EXISTS `rrp_coupon_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `couponcode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `prod` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` date DEFAULT NULL,
  `discount` decimal(15,2) NOT NULL,
  `expire_date` datetime NOT NULL,
  `publish` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_emails`;
CREATE TABLE IF NOT EXISTS `rrp_emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

INSERT INTO `rrp_emails` (`id`, `type`, `subject`, `message`) VALUES
(1, 'Email sent to admin for password reset', 'Your {sitename} Admin password has been reset', 'Dear {username},\r\n\r\nYour password has been reset. \r\n\r\nYour temporary password is:  {password}  \r\n\r\n{login_link}\r\nPlease log in and reset your password now.\r\n\r\nThank You!\r\nAdmin'),
(2, 'Email sent to member for password reset', 'Your {sitename} password has been reset', 'Dear {firstname} {lastname},\r\n\r\nYour password has been reset. \r\n\r\nYour temporary password is:  {password}  \r\n{login_link}\r\nPlease log in and reset your password now.\r\nThank You!\r\nAdmin'),
(3, 'Email sent to paid member after signup', 'Welcome to {sitename}.', 'Dear {firstname}, \r\n\r\nThank you for purchasing {product_name}. Your login details are below.\r\n\r\nEmail: {email}\r\nUsername: {username}\r\nPassword: {pass}\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\n{login_link}\r\n\r\nThank you.\r\nAdmin'),
(4, 'Email sent to admin after product payment', 'You have a sale.', 'Dear Admin, \r\n\r\nA new user has purchased {product_name}.\r\n\r\nThank you.\r\n\r\nAdmin'),
(5, 'Email sent to free member after signup', 'Welcome to {sitename}.', 'Dear {firstname}, \r\n\r\nThank you for joining our site.\r\n\r\nYour login details are below.\r\n\r\nEmail: {email}\r\nUsername: {username}\r\nPassword: {pass}\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\n{login_link}\r\n\r\nThank you.\r\nAdmin'),
(6, 'Email sent to admin on new user sign up', 'A new user has joined your {sitename} website.', 'Dear Admin,\r\n\r\nA new member has joined your website. \r\n\r\nThank You!\r\nAdmin'),
(7, 'Email sent to affiliate member after signup', 'Welcome to {sitename}.', 'Dear {firstname}, \r\n\r\nThank you for becoming our affiliate.\r\n\r\nYour login details are below.\r\n\r\nEmail: {email}\r\nUsername: {username}\r\nPassword: {pass}\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\n{login_link}\r\n\r\nThank you.\r\nAdmin'),
(8, 'Email sent to admin after affiliate signup', 'A new affiliate has joined your {sitename} website.', 'Dear Admin,\r\n\r\nA new affiliate has joined your website. \r\n\r\nThank You!\r\nAdmin'),
(9, 'Email sent to jv partner after signup', 'Welcome to {sitename}.', 'Dear {firstname}, \r\n\r\nThank you for becoming our JV Partner.\r\n\r\nYour login details are below.\r\n\r\nEmail: {email}\r\nUsername: {username}\r\nPassword: {pass}\r\nFirst Name: {firstname}\r\nLast Name: {lastname}\r\n{login_link}\r\n\r\nThank you.\r\nAdmin'),
(10, 'Email sent to admin after jv partner signup', 'A new jv partner has joined your {sitename} website.', 'Dear Admin,\r\n\r\nA new jv partner has joined your website. \r\n\r\nThank You!\r\nAdmin'),
(11, 'Email sent to new member after echeck payment', 'Welcome to {sitename}.', 'Thank you for purchasing {product_name}. Since you purchased with an echeck we can not process your transaction until the echeck clears through PayPal.\r\n\r\nAs soon as your payment clears we will send you an email with instructions on how to finish the signup process and access your purchase.\r\n\r\nThank you.\r\nAdmin'),
(12, 'Email sent to new member after echeck clears', 'Your {sitename} account is now ready for you.', 'Your echeck has now cleared PayPal and you can now set up your account and access your purchase.\r\n\r\nGo to the link below to complete the signup process and log into the member area where you download will be waiting for you.\r\n\r\n{signup_link}\r\n\r\nThank you.\r\nAdmin'),
(13, 'Email sent to new member for signup process', 'Your {sitename} account is now ready for you.', 'Go to the link below to complete the signup process and log into the member area where you download will be waiting for you.\r\n\r\n{signup_link}\r\n\r\nThank you.\r\nAdmin'),
(14, 'Email sent to admin for new member coaching message', 'You have a new message from {firstname} {lastname}', '\r\n=============================\r\n{message}\r\n=============================\r\n\r\nTo reply, log into your admin area:\r\n{loginurl}\r\n\r\n'),
(15, 'Email sent to member for new Admin coaching message', 'You have a new message from {productname} Coaching Program', 'Hello, {firstname} {lastname}.\r\n\r\nYou have new message from Administrator:\r\n=============================\r\n{message}\r\n=============================\r\n\r\nTo reply, log into your member''s area:\r\n{loginurl}\r\n\r\nThank you, Administration');

DROP TABLE IF EXISTS `rrp_email_blaster_groups`;
CREATE TABLE IF NOT EXISTS `rrp_email_blaster_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `published` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `filter_type` varchar(50) DEFAULT NULL,
  `filter_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_email_blaster_group_members`;
CREATE TABLE IF NOT EXISTS `rrp_email_blaster_group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `mail_status` tinyint(4) NOT NULL,
  `content_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_email_content`;
CREATE TABLE IF NOT EXISTS `rrp_email_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `published` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_release_id` int(11) NOT NULL,
  `before_day` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_email_group`;
CREATE TABLE IF NOT EXISTS `rrp_email_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `content_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_email_log`;
CREATE TABLE IF NOT EXISTS `rrp_email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_email_settings`;
CREATE TABLE IF NOT EXISTS `rrp_email_settings` (
  `id` int(11) NOT NULL,
  `from_email` varchar(45) NOT NULL,
  `from_name` varchar(45) NOT NULL,
  `host` varchar(255) NOT NULL,
  `port` varchar(10) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `rrp_email_settings` (`id`, `from_email`, `from_name`, `host`, `port`, `username`, `password`) VALUES
(1, 'yasir.rehman@nxb.com.pk', 'Yasir Rehman', 'localhost', '25', 'yasir', '123456');

DROP TABLE IF EXISTS `rrp_help_desks`;
CREATE TABLE IF NOT EXISTS `rrp_help_desks` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

INSERT INTO `rrp_help_desks` (`id`, `name`, `url`, `published`) VALUES
(2, 'yasir rehman', 'http://rapidresidualpro.vteamslabs.com/referrer/yasir/cVideos/paypal', 0);

DROP TABLE IF EXISTS `rrp_invitecode`;
CREATE TABLE IF NOT EXISTS `rrp_invitecode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `invitefor` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_jvsign`;
CREATE TABLE IF NOT EXISTS `rrp_jvsign` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `index_page` longtext COLLATE utf8_unicode_ci,
  `psponder` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agreement` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `rrp_jvsign` (`id`, `index_page`, `psponder`, `agreement`) VALUES
(1, '\r\n<p><span style="font-weight: bold;">Thank you for your interest in becoming our JV Partner! </span></p>\r\n\r\n\r\n<p>It only takes a minute to sign up and you can start generating sales as soon as you complete the form below.</p>\r\n\r\n\r\n<p>Upon submitting the JV Partner signup form you will be logged into our partner center and will be able to browse through our marketplace of products. The marketplace lists each products commission structure and your personal affiliate link. You can then use those links to promote our products and earn commissions which will be paid directly into your PayPal account.</p>\r\n\r\n\r\n<p><span style="font-weight: bold;">Commissions: </span></p>\r\n\r\n\r\n<p>Commissions work a little differently than you may be used to. Instead of earning a percentage of each sale you generate you will be paid 100% of all sales that you are owed commissions on.</p>\r\n\r\n\r\n<p>With a normal affiliate program you have to wait for your commission payments. For example, if you promote a product with 50% commissions you earn half of all sales generated through your affiliate id. However, you have to wait for weeks or longer for the site owner to decide to pay out the commissions. You are at the mercy of the site owner and if they decide to not pay out the commissions then your out money and time.</p>\r\n\r\n\r\n<p>With our commission structure you get paid 100% of the sale price for a percentage of sales. For example, if you promote a product with 50% commissions you will recieve the full product sales price for every other sale you generate. The difference is the full sales price goes directly to you for every sale where you are owed commissions. This works out to exactly the same amount of potential income for you, but you do not have to wait for your commissions. They are deposited directly into your PayPal account at the time of the sale! </p>\r\n\r\n\r\n<p><span style="font-weight: bold;">Payment of Commissions:</span></p>\r\n\r\n\r\n<p>Commissions are sent directly to your PayPal account at the time of sale. In order to become our JV Partner you must have either a Premier or Business PayPal account. PayPal limits the payment type and amount of funds that Personal accounts can recieve so Premier or Business accounts are required to become and affiliate.</p>', '0', 59);

DROP TABLE IF EXISTS `rrp_login_attempts`;
CREATE TABLE IF NOT EXISTS `rrp_login_attempts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `attempts` int(11) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_marketing_banners`;
CREATE TABLE IF NOT EXISTS `rrp_marketing_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `product_id` int(11) DEFAULT NULL,
  `banner_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_marketing_emails`;
CREATE TABLE IF NOT EXISTS `rrp_marketing_emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_members`;
CREATE TABLE IF NOT EXISTS `rrp_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_joined` date NOT NULL DEFAULT '0000-00-00',
  `last_login` int(11) DEFAULT NULL,
  `randomstring` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ref` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None',
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_zipcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skypeid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '2',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `is_block` int(11) NOT NULL DEFAULT '0',
  `alertpay_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alertpay_ipn_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `clickbank_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `report_abuse` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=94 ;

INSERT INTO `rrp_members` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `ip`, `date_joined`, `last_login`, `randomstring`, `ref`, `paypal_email`, `address_street`, `address_city`, `address_state`, `address_zipcode`, `address_country`, `skypeid`, `telephone`, `status`, `published`, `is_block`, `alertpay_email`, `alertpay_ipn_code`, `clickbank_email`, `report_abuse`) VALUES
(2, 'rapid', 'residualpro', 'info@rapidresidualpro.com', 'rapid', 'e10adc3949ba59abbe56e057f20f883e', '', '2011-05-18', 83920132, 'da56233a0692bf43c49b2d0cdd6b27e8', '', '', '', '', '', '', '', '', '', 2, 1, 0, '', '', '', 0);

DROP TABLE IF EXISTS `rrp_member_messages`;
CREATE TABLE IF NOT EXISTS `rrp_member_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `product` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `checked` int(11) NOT NULL DEFAULT '0',
  `mchecked` int(11) NOT NULL DEFAULT '0',
  `vis` int(11) NOT NULL DEFAULT '0',
  `upload_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_member_navigation`;
CREATE TABLE IF NOT EXISTS `rrp_member_navigation` (
  `navigation_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `url` varchar(200) NOT NULL,
  `visit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`navigation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

INSERT INTO `rrp_member_navigation` (`navigation_id`, `member_id`, `hash`, `url`, `visit_time`) VALUES
(1, 0, '', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-07 11:50:02'),
(2, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-07 11:50:03'),
(3, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/downloads.php', '2011-06-07 11:50:08'),
(4, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/marketplace.php', '2011-06-07 11:50:10'),
(5, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/downloads.php', '2011-06-07 11:50:15'),
(6, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/profile.php', '2011-06-07 11:50:16'),
(7, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/profile.php', '2011-06-07 11:50:48'),
(8, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/products.php?short=demo?short=demo', '2011-06-07 11:50:51'),
(9, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-07 11:50:54'),
(10, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/profile.php', '2011-06-07 11:50:55'),
(11, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/profile.php', '2011-06-07 11:50:57'),
(12, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/downloads.php', '2011-06-07 11:50:59'),
(13, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/marketplace.php', '2011-06-07 11:51:02'),
(14, 2, 'ad77a116eb9df66e429b08de6db0e25b', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-07 11:51:03'),
(15, 0, '', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-08 21:47:35'),
(16, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-08 21:47:37'),
(17, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/products.php?short=demo?short=demo', '2011-06-08 21:48:31'),
(18, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/sales.php?short=demo?short=demo', '2011-06-08 21:48:35'),
(19, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/downloads.php', '2011-06-08 21:48:47'),
(20, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/profile.php', '2011-06-08 21:49:08'),
(21, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/index.php', '2011-06-08 21:49:16'),
(22, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/affiliate_home.php', '2011-06-08 21:49:21'),
(23, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/downloads.php', '2011-06-08 21:49:25'),
(24, 2, '07df8796a2a496bf56057973d2040f23', 'http://www.rapidresidualpro.biz//member/marketplace.php', '2011-06-08 21:49:26');

DROP TABLE IF EXISTS `rrp_member_products`;
CREATE TABLE IF NOT EXISTS `rrp_member_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `txn_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `refunded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_member_session`;
CREATE TABLE IF NOT EXISTS `rrp_member_session` (
  `member_id` int(11) NOT NULL DEFAULT '0',
  `hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` decimal(10,4) NOT NULL,
  `longitude` decimal(10,4) NOT NULL,
  `operating_system` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `browser` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `rrp_member_session` (`member_id`, `hash`, `country`, `city`, `latitude`, `longitude`, `operating_system`, `browser`, `time`) VALUES
(2, '07df8796a2a496bf56057973d2040f23', '', '', '0.0000', '0.0000', '', '', 1307605742);

DROP TABLE IF EXISTS `rrp_member_session_archive`;
CREATE TABLE IF NOT EXISTS `rrp_member_session_archive` (
  `member_id` int(11) NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `latitude` decimal(10,4) NOT NULL,
  `longitude` decimal(10,4) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `operating_system` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `browser` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`,`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `rrp_member_session_archive` (`member_id`, `hash`, `country`, `city`, `latitude`, `longitude`, `ip`, `operating_system`, `browser`, `time`) VALUES
(2, 'ad77a116eb9df66e429b08de6db0e25b', '', '', '0.0000', '0.0000', '', '', '', 1307519354),
(2, '07df8796a2a496bf56057973d2040f23', '', '', '0.0000', '0.0000', '', '', '', 1307605631);

DROP TABLE IF EXISTS `rrp_menus`;
CREATE TABLE IF NOT EXISTS `rrp_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL,
  `menu_alias` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_alias` (`menu_alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

INSERT INTO `rrp_menus` (`id`, `menu_name`, `menu_alias`, `created_date`, `published`) VALUES
(1, 'Main Menu', 'menu_main', '2011-01-18 17:55:49', 1),
(2, 'Left Menu', 'menu_left', '2011-01-18 17:55:49', 1),
(6, 'Footer Menu', 'menu_footer', '2011-01-27 17:07:01', 1),
(7, 'Member Menu', 'menu_member', '2011-02-02 20:30:00', 1),
(8, 'Affiliate Menu', 'menu_affiliate', '2011-02-04 09:46:06', 1),
(9, 'JV Partner', 'menu_jvpartner', '2011-02-04 09:46:29', 1);

DROP TABLE IF EXISTS `rrp_menus_items`;
CREATE TABLE IF NOT EXISTS `rrp_menus_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `type` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `published` tinyint(4) NOT NULL,
  `permission` tinyint(4) NOT NULL,
  `menuid` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `target` varchar(255) NOT NULL,
  `nofollow` tinyint(4) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `asign_template` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

INSERT INTO `rrp_menus_items` (`id`, `name`, `alias`, `url`, `type`, `content`, `published`, `permission`, `menuid`, `order`, `target`, `nofollow`, `created_date`, `asign_template`) VALUES
(35, 'Blog', 'blog', '/blog.php', 'custom', 'custom>', 1, 0, 1, 2, '0', 1, '2011-01-21 00:17:28', ''),
(61, 'Home', '', '/member/index.php', 'custom', 'custom>', 1, 0, 9, 1, '0', 1, '2011-02-04 09:49:38', ''),
(9, 'About Us', 'about-us', '/content.php?page=recommends', 'content', 'content>recommends', 1, 1, 2, 1, '0', 0, '2011-01-17 23:39:47', ''),
(12, 'Testimonials', 'testimonials', '/blog.php?page=bestplans1', 'blog', 'blog>bestplans1', 1, 1, 2, 2, '0', 1, '2011-01-17 23:39:47', ''),
(41, 'Home', 'home', '/index.php', 'custom', 'custom>', 1, 0, 6, 1, '0', 0, '2011-01-27 17:07:41', ''),
(34, 'Home', 'home', '/', 'custom', 'custom>', 1, 0, 2, 3, '0', 0, '2011-01-21 00:11:16', ''),
(42, 'Earnings Disclaimer', '', '/content.php?page=earning-disclamer', 'content', 'content>earning-disclamer', 1, 0, 6, 2, '0', 1, '2011-01-27 17:08:41', ''),
(31, 'Latest News', '', '/news.php', 'custom', 'custom>', 1, 0, 2, 4, '0', 1, '2011-01-20 23:44:45', ''),
(22, 'Home', 'home', '/', 'custom', 'custom>', 1, 0, 1, 1, '0', 0, '2011-01-20 22:51:48', ''),
(24, 'Contact Us', 'contactus', '/content.php?page=contact-us', 'content', 'content>contact-us', 1, 0, 1, 7, '0', 0, '2011-01-20 22:56:01', ''),
(40, 'Squeeze', 'squeeze', '/squeeze.php?page=A better token algorithm with PHP', 'squeeze', 'squeeze>A better token algorithm with PHP', 1, 0, 2, 5, '0', 0, '2011-01-21 00:34:45', ''),
(43, 'Terms of Use', '', '/content.php?page=term-and-condition', 'content', 'content>term-and-condition', 1, 0, 6, 3, '0', 1, '2011-01-27 17:09:51', ''),
(44, 'Privacy Policy', '', '/content.php?page=privacypolicy', 'content', 'content>privacypolicy', 1, 0, 6, 4, '0', 0, '2011-01-27 17:10:33', ''),
(45, 'Membership Agreement', '', '/content.php?page=membership-agreement', 'content', 'content>membership-agreement', 1, 0, 6, 5, '0', 0, '2011-01-27 17:11:31', ''),
(46, 'Marketplace', 'market-place', 'marketplace.php', 'custom', 'custom>', 1, 0, 6, 6, '0', 0, '2011-01-27 17:13:39', ''),
(71, 'Profile', '', 'profile.php', 'custom', 'custom>', 1, 0, 8, 1, '0', 1, '2011-05-11 21:31:06', ''),
(70, 'Member', 'member', '/member/', 'custom', 'custom>', 1, 0, 6, 8, '0', 0, '2011-02-23 12:00:10', ''),
(53, 'JV Partners', '', '/jvsign.php', 'custom', 'custom>', 1, 0, 6, 7, '0', 0, '2011-02-01 23:54:31', ''),
(54, 'Home', 'home', '/member/index.php', 'custom', 'custom>', 1, 0, 7, 1, '0', 0, '2011-02-02 20:30:31', ''),
(55, 'Log Out', 'logout', '/member/logout.php', 'custom', 'custom>', 1, 0, 7, 7, '0', 0, '2011-02-02 20:31:26', ''),
(56, 'Profile', 'profile', '/member/profile.php', 'custom', 'custom>', 1, 0, 7, 2, '0', 0, '2011-02-02 21:47:51', ''),
(57, 'My Download & Coaching', 'my-download', '/member/downloads.php', 'custom', 'custom>', 1, 0, 7, 3, '0', 0, '2011-02-02 21:48:46', ''),
(58, 'Product Marketplace  ', '', '/member/marketplace.php', 'custom', 'custom>', 1, 0, 7, 4, '0', 1, '2011-02-02 21:49:19', ''),
(64, 'Product Marketplace', '', '/member/marketplace.php', 'custom', 'custom>', 1, 0, 9, 4, '0', 1, '2011-02-04 10:09:00', ''),
(65, 'Profile', '', 'profile.php', 'custom', 'custom>', 1, 0, 9, 5, '0', 1, '2011-02-04 10:11:01', ''),
(66, 'My Downloads & Coaching', '', 'downloads.php', 'custom', 'custom>', 1, 0, 9, 3, '0', 1, '2011-02-04 10:15:00', ''),
(67, 'Log Out', '', '/member/logout.php', 'custom', 'custom>', 1, 0, 9, 6, '0', 0, '2011-02-04 10:17:52', ''),
(68, 'Home', 'home', 'index.php', 'custom', 'custom>', 1, 0, 8, 0, '0', 0, '2011-02-06 23:50:07', ''),
(69, 'Log Out', 'logout', 'logout.php', 'custom', 'custom>', 1, 0, 8, 4, '0', 0, '2011-02-07 20:16:59', ''),
(72, 'My Products and Downloads', '', 'downloads.php', 'custom', 'custom>', 1, 0, 8, 2, '0', 1, '2011-05-11 21:35:10', '');

DROP TABLE IF EXISTS `rrp_misc_pages`;
CREATE TABLE IF NOT EXISTS `rrp_misc_pages` (
  `id` int(11) NOT NULL DEFAULT '1',
  `member_main` longtext COLLATE utf8_unicode_ci,
  `affiliate_main` longtext COLLATE utf8_unicode_ci,
  `jv_main` longtext COLLATE utf8_unicode_ci,
  `member_menu` longtext COLLATE utf8_unicode_ci,
  `affiliate_menu` longtext COLLATE utf8_unicode_ci,
  `jv_menu` longtext COLLATE utf8_unicode_ci,
  `member_menu_id` int(11) NOT NULL,
  `affiliate_menu_id` int(11) NOT NULL,
  `jv_menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `rrp_misc_pages` (`id`, `member_main`, `affiliate_main`, `jv_main`, `member_menu`, `affiliate_menu`, `jv_menu`, `member_menu_id`, `affiliate_menu_id`, `jv_menu_id`) VALUES
(1, '<p>Member Index Page</p>\r\n\r\n<p>Welcome {{firstname}}, <br />\r\n	<br />\r\n	Please use the right side menu to access your content.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p align=center>{{video_swimsuit1_38}}</p>', '<p>Affiliate Index Page</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Welcome {{firstname}}, <br />\r\n	<br />\r\n	Please use the right side menu to access your content.<br />\r\n	</p>', '<p>JV Partner Index Page</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Welcome {{firstname}}, <br />\r\n	<br />\r\n	Please use the right side menu to access your content.<br />\r\n	<br />\r\n	</p>', '\r\n\r\n<p align="center"><a href="index.php">Home</a>&nbsp; |&nbsp; <a href="profile.php">Profile</a>&nbsp; |&nbsp; <a href="downloads.php">My Downloads</a>&nbsp; |&nbsp; <a href="marketplace.php">Product Marketplace</a>&nbsp; |&nbsp; <a href="logout.php">Log Out</a></p>', '\r\n\r\n<p align="center"><a href="index.php">Home</a>&nbsp; |&nbsp; <a href="profile.php">Profile</a>&nbsp; |&nbsp; <a href="downloads.php">My Downloads</a>&nbsp; |&nbsp; <a href="marketplace.php">Product Marketplace</a>&nbsp; |&nbsp; <a href="logout.php">Log Out</a></p>', '\r\n\r\n<p align="center"><a href="index.php">Home</a>&nbsp; |&nbsp; <a href="profile.php">Profile</a>&nbsp; |&nbsp; <a href="downloads.php">My Downloads</a>&nbsp; |&nbsp; <a href="marketplace.php">Product Marketplace</a>&nbsp; |&nbsp; <a href="logout.php">Log Out</a></p>', 7, 8, 9);

DROP TABLE IF EXISTS `rrp_orders`;
CREATE TABLE IF NOT EXISTS `rrp_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `item_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pending_reason` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `txnid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payer_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `payee_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `randomstring` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `referrer` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_gateway` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_oto_check`;
CREATE TABLE IF NOT EXISTS `rrp_oto_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `oto_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_pages`;
CREATE TABLE IF NOT EXISTS `rrp_pages` (
  `pageid` int(5) NOT NULL AUTO_INCREMENT,
  `pagename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pcontent` longtext COLLATE utf8_unicode_ci NOT NULL,
  `linkproduct` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `description` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rss` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `showurls` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `nofollow` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `width` int(3) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'content',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `asign_template` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  PRIMARY KEY (`pageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=72 ;

INSERT INTO `rrp_pages` (`pageid`, `pagename`, `filename`, `pcontent`, `linkproduct`, `date_added`, `description`, `comments`, `rss`, `keywords`, `showurls`, `nofollow`, `width`, `type`, `published`, `asign_template`) VALUES
(67, 'CLICK BANK PAYMENT', 'clickbank', '\r\n<p>THANK YOU FOR PURCHASING {{product_name}}!<br />\r\n   Your billing statement will show a charge to Clickbank or CLKBANK*COM.</p>\r\n<p>Your initial charge will be ${{product_price}}.</p>\r\n<p>{{subscription_msg}}</p>\r\n<p>{{signup_form}}</p>\r\n<p>If you have any questions or problems, please use the contact us form below.</p>\r\n<p>{{contact_form}}</p>\r\n<p>Regards,</p>\r\n<p>Admin</p><br />\r\n', 'Legal', '2011-05-26 17:35:02', '', 'no', 'no', 'click bank', 'no', 'no', 0, 'content', 1, 'default'),
(65, 'AFFILIATE BANNED', 'affiliate-banned', '\r\n<h2 style="text-align: left; color: rgb(166, 0, 18);">We Apologize...</h2><span style="font-weight: bold;">The link that brought you to our site is from an affiliate who has been banned from selling our products.</span><br />\r\n<br />\r\nYou were sent to our site through a link from an affiliate who has engaged in fraudulent or suspicious activity, and is not allowed to sell our products at this time. We apologize the inconvenience.<br />\r\n<br />\r\nWe appreciate your interest in our programs, products and services and certainly encourage you to review our site -- please feel free to look around. <br />\r\n<br />\r\n<span style="font-weight: bold;">In all fairness however</span>, know that the person who originally referred you to our site will not receive credit because apparently they violated our affiliate agreement in some way.<br />\r\n<br />\r\nIf you were contacted by a person claiming to represent one of our products, programs or services, please know that this person DOES NOT represent us in any way at this time.<br />\r\n<br />\r\nWe respect our customers and community and have very high standards for those who represent our programs.&nbsp; We do not engage in SPAM tactics or fraud - and do not tolerate such practices.<br />\r\n<br />\r\n<br />\r\n<hr />\r\n\r\n<p>If you have any questions, please use the form below:</p>	\r\n<p>{{contact_form}}</p><br />\r\n\r\n<div style="text-align: center;"><span style="font-family: Verdana; font-size: 10pt;"><span style="line-height: 115%; font-size: 10pt;">&copy; MMXI REI360, LLC and RapidResidualPro – All Rights Reserved.</span><br />\r\n				<br />\r\n				<span style="line-height: 115%; font-size: 10pt;">Powered by Rapid Residual Pro</span></span><br />\r\n		</div>\r\n\r\n', 'Legal', '2011-03-31 16:45:43', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(68, 'Blog', 'rapid-residual-pro', 'Blogs<br />', '', '2011-06-03 18:36:29', 'Blogs', 'yes', 'no', '', 'yes', 'no', 0, 'blog', 1, 'default'),
(50, 'MEMBERSHIP AGREEMENT', 'membership-agreement', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:45:20', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(58, 'EXTERNAL LINKS POLICY', 'external-links-policy', '\r\n\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:51:36', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(51, 'TERMS AND CONDITIONS OF USE', 'term-and-condition', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:45:53', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(52, 'EARNINGS DISCLAIMER', 'earning-disclamer', '\r\n\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:46:30', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(53, 'HOW WE PROTECT YOUR PRIVACY', 'privacy-policy', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:47:45', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(54, 'RETURNS POLICY', 'returns-policy', '<h4>RETURNS POLICY</h4>', 'Legal', '2011-02-01 07:48:25', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'palmtrees'),
(55, 'DIGITAL MILLENNIUM COPYRIGHT', 'dmc', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:48:59', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(56, 'ANTI-SPAM POLICY', 'antispan-policy', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:49:35', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(57, 'COMPENSATION DISCLOSURE POLICY', 'compensation-disclosure-policy', '\r\n\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:50:32', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(59, 'AFFILIATE AGREEMENT', 'affiliate-agreement', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 07:52:23', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(60, 'CONTACT US', 'contact-us', 'Contact Us<br />\r\n\r\n<p><span style="font-weight: bold;">				\r\n		\r\n		\r\n		\r\n		\r\n		</span>{{contact_form}}<br />\r\n			\r\n	\r\n	\r\n	\r\n	\r\n	<br />\r\n	<span style="font-weight: bold;"></span></p>', 'Legal', '2011-02-01 07:53:02', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(61, 'PRIVACY POLICY', 'privacypolicy', '\r\nAdd Legal Statements Here. If you need a quality resource for legal \r\ndocuments, Rapid Residual Pro recommends: <a href="http://www.rapidresidualpro.com/likes/legal">http://www.rapidresidualpro.com/likes/legal</a>,\r\n a great resource for your online legal documentation needs.<br />\r\n', 'Legal', '2011-02-01 08:04:38', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(69, 'LEGAL', 'legal', '<div>The following pages represent the legal rights, requirements, and statements of our website. Use of this website constitutes agreement with each of these documents in total. Please review these and become familiar with them. Thank you for visiting!</div>\r\n\r\n<blockquote style="MARGIN-RIGHT: 0px" dir=ltr>\r\n	\r\n<blockquote style="MARGIN-RIGHT: 0px" dir=ltr>\r\n		\r\n<div><strong>Privacy Policy </strong></div>\r\n		\r\n<div><strong></strong></div>\r\n	\r\n<div><strong>Earnings Disclaimer</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Copyright Notice</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Digital Millenium Copyright Agreement (DMCA Notice)</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Privacy Policy</strong></div>\r\n		\r\n<div><strong></strong></iv>\r\n		\r\n<div><strong>Affiliate Agreement</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>External Links Policy</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Membership Agreement</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Online Subscription Agreement</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Compensation Disclosure Policy</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Anti-Spam Policy</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Returns Policy</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Terms and Conditions of Use Policy</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Virus Notice</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Chat Room Agreement</strong></div>\r\n		\r\n<div><strong></strong></div>\r\n		\r\n<div><strong>Contact Us</strong></div></blockquote></blockquote>', 'Legal', '2011-06-07 02:55:50', '', 'no', 'no', '', 'yes', 'yes', 0, 'content', 1, 'default'),
(70, '404', '404', '<h2>Page not found. Please enter correct URL</h2>', 'Legal', '2011-06-22 02:05:45', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default'),
(71, '403', '403', '<h2>You don''t have permission to access this page. Please contact to website administrator.</h2>', 'Legal', '2011-06-22 02:07:47', '', 'no', 'no', '', 'no', 'no', 0, 'content', 1, 'default');

DROP TABLE IF EXISTS `rrp_privileges`;
CREATE TABLE IF NOT EXISTS `rrp_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `role` tinyint(4) NOT NULL,
  `all` tinyint(4) NOT NULL DEFAULT '0',
  `view` tinyint(4) NOT NULL,
  `edit` tinyint(4) NOT NULL,
  `add` int(11) NOT NULL,
  `delete` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=145 ;

INSERT INTO `rrp_privileges` (`id`, `name`, `url`, `role`, `all`, `view`, `edit`, `add`, `delete`) VALUES
(1, 'Change Password', '/admin/admin_settings.php', 0, 1, 1, 1, 1, 1),
(2, 'Site Settings', '/admin/site_settings.php', 2, 0, 1, 0, 0, 0),
(3, 'Change login', '/admin/change_login.php', 0, 1, 1, 1, 1, 1),
(22, 'Add Content Pages', '/admin/page-add.php', 2, 0, 1, 1, 1, 0),
(21, 'Content Pages', '/admin/pages.php', 3, 0, 1, 1, 1, 0),
(20, 'Content Pages', '/admin/pages.php', 2, 0, 1, 1, 1, 0),
(19, 'Content Page', '/admin/pages.php', 2, 0, 1, 1, 1, 0),
(18, 'Outgoing Email Management', '/admin/emails.php', 5, 0, 1, 1, 0, 0),
(17, 'Outgoing Email Management', '/admin/emails.php', 5, 0, 1, 1, 0, 0),
(10, 'Outgoing Email Management', '/admin/emails.php', 2, 0, 1, 0, 0, 0),
(16, 'Edit Outgoing Email Management', '/admin/edit_email.php', 5, 0, 1, 0, 0, 0),
(15, 'Edit Outgoing Email Management', '/admin/edit_email.php', 2, 0, 1, 0, 0, 0),
(23, 'Add Content Pages', '/admin/page-add.php', 3, 0, 1, 1, 1, 0),
(24, 'Edit Content Pages', '/admin/page-edit.php', 2, 0, 1, 1, 1, 0),
(25, 'Edit Content Pages', '/admin/page-edit.php', 3, 0, 1, 1, 1, 0),
(26, 'Blog', '/admin/blog.php', 2, 0, 1, 1, 1, 0),
(27, 'Blog', '/admin/blog.php', 3, 0, 1, 1, 1, 0),
(28, 'Add Blog', '/admin/blog-add.php', 2, 0, 1, 1, 1, 0),
(29, 'Add Blog', '/admin/blog-edit.php', 3, 0, 1, 1, 1, 0),
(30, 'Edit Blog', '/admin/blog-edit.php', 2, 0, 1, 1, 1, 0),
(31, 'Edit Blog', '/admin/blog-edit.php', 3, 0, 1, 1, 1, 0),
(32, 'Index Pages', '/admin/mindex.php', 2, 0, 1, 1, 1, 0),
(33, 'Index Pages', '/admin/mindex.php', 3, 0, 1, 1, 1, 0),
(34, 'Menu', '/admin/menus.php', 2, 0, 1, 1, 1, 0),
(35, 'Menu', '/admin/menus.php', 3, 0, 1, 1, 1, 0),
(36, 'Squeeze Page', '/admin/squeeze_view.php', 2, 0, 1, 1, 1, 0),
(37, 'Squeeze Page', '/admin/squeeze_view.php', 3, 0, 1, 1, 1, 0),
(38, 'Add Squeeze Page', '/admin/squeeze_add.php', 2, 0, 1, 1, 1, 0),
(39, 'Add Squeeze Page', '/admin/squeeze_add.php', 3, 0, 1, 1, 1, 0),
(40, 'Edit Squeeze', '/admin/squeeze_edit.php', 2, 0, 1, 1, 1, 0),
(41, 'Edit Squeeze', '/admin/squeeze_edit.php', 3, 0, 1, 1, 1, 0),
(42, 'Aweber', '/admin/aweber.php', 2, 0, 1, 1, 1, 0),
(75, 'Products', '/admin/paid_products.php', 4, 0, 1, 1, 1, 0),
(44, 'Add Aweber', '/admin/addaweber.php', 2, 0, 1, 1, 1, 0),
(46, 'Edit Aweber', '/admin/editaweber.php', 2, 0, 1, 1, 1, 0),
(74, 'Products', '/admin/paid_products.php', 2, 0, 1, 1, 1, 0),
(48, 'Affiliate SignUp Page', '/admin/affiliate.php', 2, 0, 1, 1, 1, 0),
(49, 'Affiliate SignUp Page', '/admin/affiliate.php', 3, 0, 1, 1, 1, 0),
(50, 'JV Partner Signup Page', '/admin/jvsign.php', 2, 0, 1, 1, 1, 0),
(51, 'JV Partner Signup Page', '/admin/jvsign.php', 3, 0, 1, 1, 1, 0),
(52, 'Legal Documents', '/admin/tpd.php', 2, 0, 1, 1, 1, 0),
(53, 'Legal Documents', '/admin/tpd.php', 3, 0, 1, 1, 1, 0),
(54, 'GetResponse Autoresponder', '/admin/gr.php', 2, 0, 1, 1, 1, 0),
(81, 'Allocate Products', '/admin/allocate_product.php', 4, 0, 1, 1, 1, 0),
(56, 'Add GetResponse Autoresponder', '/admin/addgr.php', 2, 0, 1, 1, 1, 0),
(58, 'Edit GetResponse Autoresponder', '/admin/editgr.php', 2, 0, 1, 1, 1, 0),
(80, 'Allocate Products', '/admin/allocate_product.php', 2, 0, 1, 1, 1, 0),
(60, 'Autoresponse Plus', '/admin/arp.php', 2, 0, 1, 1, 1, 0),
(79, 'Edit Products', '/admin/edit_product.php', 4, 0, 1, 1, 1, 0),
(62, 'Add Autoresponse Plus', '/admin/addarp.php', 2, 0, 1, 1, 1, 0),
(78, 'Edit Products', '/admin/edit_product.php', 2, 0, 1, 1, 1, 0),
(64, 'Edit Autoresponse Plus', '/admin/editarp.php', 2, 0, 1, 1, 1, 0),
(77, 'Add Products', '/admin/add_product.php', 4, 0, 1, 1, 1, 0),
(66, 'Member Management', '/admin/member_view.php', 2, 0, 1, 1, 1, 0),
(76, 'Add Products', '/admin/add_product.php', 2, 0, 1, 1, 1, 0),
(68, 'Add Member', '/admin/member_add.php', 2, 0, 1, 1, 1, 0),
(70, 'Edit Member', '/admin/member_edit.php', 2, 0, 1, 1, 1, 0),
(72, 'Single Member Mail', '/admin/single_mail.php', 2, 0, 1, 1, 1, 0),
(82, 'Time-Released', '/admin/tcampaigns.php', 2, 0, 1, 1, 1, 0),
(83, 'Time-Released', '/admin/tcampaigns.php', 4, 0, 1, 1, 1, 0),
(84, 'Add Time-Released', '/admin/add_campaign.php', 2, 0, 1, 1, 1, 0),
(85, 'Add Time-Released', '/admin/add_campaign.php', 4, 0, 1, 1, 1, 0),
(86, 'Edit Time-Released', '/admin/edit_campaign.php', 2, 0, 1, 1, 1, 0),
(87, 'Edit Time-Released', '/admin/edit_campaign.php', 4, 0, 1, 1, 1, 0),
(88, 'Coupon Code', '/admin/coupon.php', 2, 0, 1, 1, 1, 0),
(103, 'Marketing Banners Management', '/admin/market_banners.php', 4, 0, 1, 1, 1, 0),
(90, 'Edit Coupon Code', '/admin/editcoupon.php', 2, 0, 1, 1, 1, 0),
(92, 'Add Coupon Code', '/admin/addcoupon.php', 2, 0, 1, 1, 1, 0),
(102, 'Marketing Banners Management', '/admin/market_banners.php', 2, 0, 1, 1, 1, 0),
(94, 'View Content Page', '/admin/view-content-page.php', 2, 0, 1, 1, 1, 0),
(95, 'View Content Page', '/admin/view-content-page.php', 3, 0, 1, 1, 1, 0),
(96, 'Time Based content', '/admin/list_timed_content.php', 2, 0, 1, 0, 0, 0),
(97, 'Time Based content', '/admin/list_timed_content.php', 4, 0, 1, 1, 1, 0),
(98, 'Add Time Based content', '/admin/add_timed_content.php', 2, 0, 1, 1, 1, 0),
(99, 'Add Time Based content', '/admin/add_timed_content.php', 4, 0, 1, 1, 1, 0),
(100, 'Edit Time Based content', '/admin/edit_timed_content.php', 2, 0, 1, 1, 1, 0),
(101, 'Edit Time Based content', '/admin/edit_timed_content.php', 4, 0, 1, 1, 1, 0),
(104, 'Add Marketing Banners Management', '/admin/getbanner.php', 2, 0, 1, 1, 1, 0),
(105, 'Add Marketing Banners Management', '/admin/getbanner.php', 4, 0, 1, 1, 1, 0),
(106, 'Affiliate Emails Management', '/admin/affiliate_emails.php', 2, 0, 1, 1, 1, 0),
(107, 'Affiliate Emails Management', '/admin/affiliate_emails.php', 4, 0, 1, 1, 1, 0),
(108, 'Add Affiliate Emails Management', '/admin/add_affiliate_email.php', 2, 0, 1, 1, 1, 0),
(109, 'Add Affiliate Emails Management', '/admin/add_affiliate_email.php', 4, 0, 1, 1, 1, 0),
(110, 'Edit Affiliate Emails Management', '/admin/edit_affiliate_email.php', 2, 0, 1, 1, 1, 0),
(111, 'Edit Affiliate Emails Management', '/admin/edit_affiliate_email.php', 4, 0, 1, 1, 1, 0),
(112, 'Sales Reports', '/admin/sales.php', 2, 0, 1, 1, 1, 0),
(113, 'Product Conversion Reports', '/admin/conversion.php', 2, 0, 1, 1, 1, 0),
(114, 'Short URLs', '/admin/shorturl.php', 2, 1, 1, 1, 1, 0),
(116, 'Add Short URLs', '/admin/add_shorturl.php', 2, 1, 1, 1, 1, 0),
(118, 'Edit Short URLs', '/admin/editshorturl.php', 2, 1, 1, 1, 1, 0),
(120, 'Help Desk Management ', '/admin/help_desks.php', 1, 1, 1, 1, 1, 0),
(121, 'Add Help Desk', '/admin/add_helpdesk.php', 2, 1, 1, 1, 1, 0),
(122, 'Edit Help Desk', '/admin/edit_helpdesk.php', 2, 1, 1, 1, 1, 0),
(139, 'Add Menus', '/admin/menus/add-menus.php', 2, 0, 1, 1, 1, 0),
(138, 'Menu Manager', '/admin/menus/index.php', 7, 0, 1, 1, 1, 0),
(137, 'Menu Manager', '/admin/menus/index.php', 2, 0, 1, 1, 1, 0),
(126, 'Comments', '/admin/comment_moderation.php', 2, 0, 1, 1, 1, 0),
(127, 'Comments', '/admin/comment_moderation.php', 3, 0, 1, 1, 1, 0),
(128, 'Add Comments', '/admin/comment_moderation_add.php', 2, 0, 1, 1, 1, 0),
(129, 'Add Comments', '/admin/comment_moderation_add.php', 3, 0, 1, 1, 1, 0),
(130, 'Comment Reply', '/admin/comment_reply.php', 2, 0, 1, 1, 1, 0),
(131, 'Comment Reply', '/admin/comment_reply.php', 3, 0, 1, 1, 1, 0),
(132, 'View Pages', '/admin/managetemplate/index.php', 7, 0, 1, 1, 1, 0),
(133, 'Manage Pages', '/admin/managetemplate/manage.php', 7, 0, 1, 1, 1, 0),
(134, 'Manage Template', '/admin/managetemplate/', 7, 0, 1, 1, 1, 0),
(135, 'Help Desk', '/admin/helpdesk.php', 1, 1, 1, 1, 1, 0),
(136, 'Forum', '/admin/community.php', 1, 1, 1, 1, 1, 0),
(140, 'Add Menus', '/admin/menus/add-menus.php', 7, 0, 1, 1, 1, 0),
(141, 'Menus Listings', '/admin/menus/menu-listings.php', 2, 0, 1, 1, 1, 0),
(142, 'Menus Listings', '/admin/menus/menu-listings.php', 7, 0, 1, 1, 1, 0),
(143, 'Add Menu Items', '/admin/menus/add-menu-items.php', 2, 0, 1, 1, 1, 0),
(144, 'Add Menu Items', '/admin/menus/add-menu-items.php', 7, 0, 1, 1, 1, 0);

DROP TABLE IF EXISTS `rrp_products`;
CREATE TABLE IF NOT EXISTS `rrp_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pshort` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission` int(3) NOT NULL DEFAULT '0',
  `jvcommission` int(3) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'paypal_buynow.gif',
  `index_page` longtext COLLATE utf8_unicode_ci NOT NULL,
  `download_form` longtext COLLATE utf8_unicode_ci NOT NULL,
  `prodtype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'paid',
  `marketplace` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `affiliate_link` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `imageurl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prod_description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `one_time_offer` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `otocheck` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `down_one_time_offer` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `otodowncheck` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `psponder` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `no_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No Thanks!, I understand that this is the only chance I will have to take advantage of this offer.',
  `qlimit` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `quantity_cap` int(3) NOT NULL DEFAULT '0',
  `quantity_met_page` longtext COLLATE utf8_unicode_ci NOT NULL,
  `subscription_active` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `period1_active` int(3) NOT NULL DEFAULT '0',
  `period1_value` int(3) NOT NULL DEFAULT '0',
  `period1_interval` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `amount1` decimal(15,2) NOT NULL DEFAULT '0.00',
  `period2_active` int(3) NOT NULL DEFAULT '0',
  `period2_value` int(3) NOT NULL DEFAULT '0',
  `period2_interval` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `amount2` decimal(15,2) NOT NULL DEFAULT '0.00',
  `period3_value` int(3) NOT NULL DEFAULT '0',
  `period3_interval` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'M',
  `amount3` decimal(15,2) NOT NULL DEFAULT '0.00',
  `srt` int(3) NOT NULL DEFAULT '0',
  `squeeze_check` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `squeezename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pp_header` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pp_return` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tcontent` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `coaching` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `template` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `paypal_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alertpay_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `clickbank_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `click_bank_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `click_bank_security_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `add_in_sidebar` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `member_marketplace` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `button_html` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `button_forum` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `button_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `show_affiliate_link_paypal` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `show_affiliate_link_alertpay` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `show_affiliate_link_clickbank` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `enable_product_partner` tinyint(4) NOT NULL DEFAULT '0',
  `product_partner_paypal_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `product_partner_alertpay_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ap_partner_ipn_security_code` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `partner_commission` int(11) NOT NULL,
  `trash` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `rrp_products` (`id`, `product_name`, `pshort`, `price`, `commission`, `jvcommission`, `image`, `index_page`, `download_form`, `prodtype`, `marketplace`, `affiliate_link`, `imageurl`, `prod_description`, `one_time_offer`, `otocheck`, `down_one_time_offer`, `otodowncheck`, `psponder`, `no_text`, `qlimit`, `quantity_cap`, `quantity_met_page`, `subscription_active`, `period1_active`, `period1_value`, `period1_interval`, `amount1`, `period2_active`, `period2_value`, `period2_interval`, `amount2`, `period3_value`, `period3_interval`, `amount3`, `srt`, `squeeze_check`, `squeezename`, `pp_header`, `pp_return`, `tcontent`, `coaching`, `published`, `template`, `paypal_image`, `alertpay_image`, `clickbank_image`, `click_bank_url`, `click_bank_security_code`, `add_in_sidebar`, `member_marketplace`, `button_html`, `button_forum`, `button_link`, `show_affiliate_link_paypal`, `show_affiliate_link_alertpay`, `show_affiliate_link_clickbank`,`keywords`) VALUES
(1, 'Demo', 'demo', '0.00', 0, 0, '', '<h2 style="text-align: center;">Sales Letter Demo</h2>', '<h3 style="TEXT-ALIGN: center">Download page</h3><br>', 'paid', 'yes', 'no', '', '', '', 'no', '', 'no', '0', '', 'no', 0, '<br />', '', 0, 0, 'D', '0.00', 0, 0, '', '0.00', 0, 'D', '0.00', 0, 'no', '0', '', '', '0', 'no', 1, 'default', '', '', '', '', '', '', '', '', '', '', '', '', '','demo');

DROP TABLE IF EXISTS `rrp_products_short`;
CREATE TABLE IF NOT EXISTS `rrp_products_short` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_url` text NOT NULL,
  `redirect_url` text NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


DROP TABLE IF EXISTS `rrp_recommends`;
CREATE TABLE IF NOT EXISTS `rrp_recommends` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_responders`;
CREATE TABLE IF NOT EXISTS `rrp_responders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aweber_unit` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aweber_meta` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rspname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rspname2` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `gr_campaign` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `posturl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trackingtag1` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trackingtag2` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trackingtag3` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trackingtag4` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trackingtag5` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arp_list_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_site_settings`;
CREATE TABLE IF NOT EXISTS `rrp_site_settings` (
  `id` int(11) NOT NULL DEFAULT '1',
  `aws_access_key` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sitename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta` longtext COLLATE utf8_unicode_ci,
  `useeditor` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sandbox_paypal_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paypal_sandbox` tinyint(1) DEFAULT NULL,
  `prot_down` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `swf_down` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `prod` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'onsite',
  `tracking` text COLLATE utf8_unicode_ci,
  `kunaki_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kunaki_pass` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sitepartner` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `partner_paypal_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partner_alertpay_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `partner_commission` int(3) DEFAULT NULL,
  `second_sitepartner` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `second_partner_paypal_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `second_partner_alertpay_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `second_partner_commission` int(3) DEFAULT NULL,
  `alertpay_merchant_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alertpay_action_url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alertpay_test_mode` int(11) NOT NULL DEFAULT '1',
  `alertpay_ipn_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `partner1_alertpay_ipn_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `partner2_alertpay_ipn_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `aws_secret_key` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `allowed_file_types` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_enable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `alertpay_enable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `email_from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mailer` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `smtpsecure` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `smtphost` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `smtpport` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `smtpusername` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `smtppassword` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mailer_details` text COLLATE utf8_unicode_ci NOT NULL,
  `affiliate_invite_code` tinyint(4) NOT NULL DEFAULT '0',
  `cloud_fornt` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cookie_mode` enum('first','last') COLLATE utf8_unicode_ci NOT NULL,
  `cookie_expiry` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `license_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `click_api_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `click_user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sidebar_my_download_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sidebar_instruction_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sidebar_new_products_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `social_media_widgets` text COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT  'logo.png',
  `tagline` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `rrp_site_settings` (`id`, `aws_access_key`, `sitename`, `description`, `keywords`, `meta`, `useeditor`, `paypal_email`, `sandbox_paypal_email`, `paypal_sandbox`, `prot_down`, `swf_down`, `prod`, `tracking`, `kunaki_user`, `kunaki_pass`, `version`, `sitepartner`, `partner_paypal_email`, `partner_alertpay_email`, `partner_commission`, `second_sitepartner`, `second_partner_paypal_email`, `second_partner_alertpay_email`, `second_partner_commission`, `alertpay_merchant_email`, `alertpay_action_url`, `alertpay_test_mode`, `alertpay_ipn_code`, `partner1_alertpay_ipn_code`, `partner2_alertpay_ipn_code`, `aws_secret_key`, `allowed_file_types`, `paypal_enable`, `alertpay_enable`, `email_from_name`, `from_name`, `mailer`, `smtpsecure`, `smtphost`, `smtpport`, `smtpusername`, `smtppassword`, `mailer_details`, `affiliate_invite_code`, `cloud_fornt`, `cookie_mode`, `cookie_expiry`, `license_key`, `click_api_key`, `click_user_id`,`sidebar_my_download_text`,`sidebar_instruction_text`,`sidebar_new_products_text`) VALUES
(1, '', 'Rapid Residual Pro', 'Complete membership site script and affiliate program all-in-one! The best membership site software available especially for time released content or drip feed content and fixed term membership sites. | Rapid Residual Pro membership script.', 'membership script, membership scripts, best membership script, drip feed content, time released content, microcontinuity, micro continuity, fixed term membership sites, ftm, membership site software, best membership site software, membership software', '', '1', '', '', 1, '/images/documents/', '/images/media/', '', '', NULL, NULL, '2.0', 'no', '', '', 1, 'no', '', '', 30, '', 'https://www.alertpay.com/PayProcess.aspx', 1, '', '', '', '', 'jpg,jpeg,gif,png,wma,swf,flv,mov,mpeg,mp3,mp4,bmp,pdf,txt,doc,docx,mpg,3gp,zip', 'yes', 'yes', '', 'Rapid Residual Pro', '', '', '', '', '', '', '', 1, 1, 'last', '90', 'e7a26f538c2f031b2dc121882ff58fb9', '', '','My Download','Instruction','New Products');

DROP TABLE IF EXISTS `rrp_squeeze_pages`;
CREATE TABLE IF NOT EXISTS `rrp_squeeze_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `squeezepage` longtext COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(3) NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `asign_template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `access` enum('Public','Private') COLLATE utf8_unicode_ci NOT NULL,
  `keyword` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_discription` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `rrp_squeeze_pages` (`id`, `name`,`page_title`,`seo_title`, `squeezepage`, `comments`, `width`, `published`, `asign_template`, `access`, `keyword`,`meta_discription`) VALUES
(1, 'under-construction','Under Construction','Under Construction', '<h1 style="text-align: center;">Page under construction.</h1>', 'no', 0, 1, 'none', 'Public', 'Under Construction','Page under construction');
DROP TABLE IF EXISTS `rrp_subscription_payment_history`;
CREATE TABLE IF NOT EXISTS `rrp_subscription_payment_history` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subscribtion_id` varchar(30) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `create_date` date NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `oid` (`oid`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `subscribtion_id` (`subscribtion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;
DROP TABLE IF EXISTS `rrp_tccampaign`;
CREATE TABLE IF NOT EXISTS `rrp_tccampaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longname` varchar(255) DEFAULT NULL,
  `shortname` varchar(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `comments` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `rrp_template`;
CREATE TABLE IF NOT EXISTS `rrp_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_blog` tinyint(4) NOT NULL,
  `default_member` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `default` tinyint(4) NOT NULL,
  `css_path` text NOT NULL,
  `custom_body` text NOT NULL,
  `custom_header` text NOT NULL,
  `custom_content` text NOT NULL,
  `custom_footer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=169 ;

INSERT INTO `rrp_template` (`id`, `default_blog`, `default_member`, `name`, `created_on`, `default`, `css_path`, `custom_body`, `custom_header`, `custom_content`, `custom_footer`) VALUES
(159, 0, 0, 'blog', '2012-02-09 18:15:15', 0, '', '', '', '', ''),
(160, 0, 0, 'memberarea', '2012-02-09 18:15:47', 0, ' ', '', '', '', ''),
(158, 0, 0, 'black', '2012-02-09 18:14:52', 0, '', '', '', '', ''),
(169, 1, 0, 'defaultblog', '2012-02-09 18:15:37', 0, '', '', '', '', ''),
(170, 0, 0, 'defaultmain', '2012-02-09 18:15:03', 1, '', '', '', '', ''),
(171, 0, 1, 'defaultmember', '2012-02-09 18:15:47', 0, '', '', '', '', '');

DROP TABLE IF EXISTS `rrp_template_assign`;
CREATE TABLE IF NOT EXISTS `rrp_template_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `template_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `rrp_template_assign` (`id`, `application_id`, `template_id`) VALUES
(1, 1, 'blog'),
(2, 2, 'combination'),
(3, 3, 'combination');

DROP TABLE IF EXISTS `rrp_timed_content`;
CREATE TABLE IF NOT EXISTS `rrp_timed_content` (
  `pageid` int(5) NOT NULL AUTO_INCREMENT,
  `pagename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pcontent` longtext COLLATE utf8_unicode_ci NOT NULL,
  `available` int(5) DEFAULT NULL,
  `campaign` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `comments` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `trorder` int(11) NOT NULL,
  PRIMARY KEY (`pageid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rrp_constant_contact` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `api_key` varchar(75) NOT NULL,
  `consumerSecret` varchar(75) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


