# Add meta support for images;
ALTER TABLE  `#__wbty_gallery_images` ADD  `page_heading` VARCHAR( 255 ) NOT NULL ,
ADD  `metadesc` TEXT NOT NULL ,
ADD  `metakey` TEXT NOT NULL;