CREATE TABLE `users` (
  `usr_id` int PRIMARY KEY,
  `usr_username` varchar(255) UNIQUE NOT NULL,
  `usr_sur_name` varchar(255),
  `usr_first_name` varchar(255),
  `usr_email` varchar(255),
  `usr_router` varchar(255),
  `usr_adress` varchar(255),
  `usr_postcode` int,
  `usr_role` varchar(255)
);

CREATE TABLE `orders` (
  `order_id` int PRIMARY KEY,
  `order_beschrijving` varchar(255),
  `order_status` varchar(255),
  `order_date` varchar(255),
  `order_shipping_adress` varchar(255),
  `order_invoice` longtext
);

CREATE TABLE `order_details` (
  `ord_det_id` int PRIMARY KEY,
  `ord_det_objectstatus` varchar(255),
  `ord_det_order_id` int,
  `ord_det_object_id` int,
  `ord_det_quantity` int
);

CREATE TABLE `object` (
  `obj_id` int PRIMARY KEY,
  `obj_name` varchar(255),
  `obj_printtime` int,
  `obj_price` int,
  `obj_size` varchar(255),
  `obj_GCODE` varchar(255)
);

CREATE TABLE `category` (
  `cat_id` int PRIMARY KEY,
  `cat_beschrijving` varchar(255),
  `cat_naam` varchar(255)
);

CREATE TABLE `object_category` (
  `obj_cat_id` int PRIMARY KEY,
  `object_id` int,
  `category_id` int
);

CREATE TABLE `printer` (
  `print_id` int PRIMARY KEY,
  `pr_user_id` int,
  `print_name` varchar(255),
  `print_location` varchar(255),
  `print_status` varchar(255)
);

CREATE TABLE `printerProfile` (
  `pp_id` int PRIMARY KEY,
  `pp_printer_id` int,
  `pp_settings` JSON
);

CREATE TABLE `images` (
  `img_id` int PRIMARY KEY,
  `img_obj_id` int,
  `img_name` varchar(255),
  `img_beschrijving` varchar(255),
  `img_path` varchar(255)
);

CREATE TABLE `postcodes` (
  `post_id` int PRIMARY KEY,
  `post_gemeente` int,
  `post_postcode` int
);

CREATE TABLE `files` (
  `fil_id` int PRIMARY KEY,
  `fil_name` varchar(255),
  `fil_GCODE` longtext,
  `fil_obj_id` int
);

CREATE TABLE `prices` (
  `pri_id` int PRIMARY KEY,
  `pri_obj_id` int,
  `pri_val` int,
  `pri_detail` varchar(255)
);

