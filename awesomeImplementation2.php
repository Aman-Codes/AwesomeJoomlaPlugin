<?php
/**
 * @package    Joomla.Plugin
 * @subpackage Content.awesome
 *
 * @copyright 2021 Aman Dwivedi (aman.dwivedi5@gmail.com)
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\ApplicationWeb;

/**
 * Awesome plugin implementation via injecting JavaScript.
 */
class PlgContentAwesome extends CMSPlugin
{

	protected $app;

	protected $autoloadLanguage = true;

	public function onContentPrepare($context, &$row, $params, $page=0)
	{
		$customHeading = $this->params->get('custom_heading');

		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

		$wa->addInlineScript("
							const allHeadings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
							for(let i=0;i<allHeadings.length;++i) {
								allHeadings[i].innerText =  '$customHeading ' + allHeadings[i].innerText;
							}",
							[],
							['type' => 'module']
						);
		return true;
	}
}
