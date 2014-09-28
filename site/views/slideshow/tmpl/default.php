<?php
/**
 * @version $Id: wbtyslider.php 2011-01-25 12:41:40 svn $
 * @package    Wbtyslider
 * @subpackage Base
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('=;)');

//echo $this->loadTemplate('scripts');

if ($this->params->show_page_heading) : ?>
	<h1><?php echo $this->escape($this->params->page_heading); ?></h1>
<?php endif; ?>

<div class="wbty_gallery_wrap wbty_gallery_component">

<?php
echo $this->loadTemplate('slider');
echo $this->loadTemplate('thumbnails');
?>

</div>