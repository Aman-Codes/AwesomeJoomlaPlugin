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

/**
 * Awesome plugin implementation via processing document on backend.
 */
class PlgContentAwesome extends CMSPlugin
{

	protected $app;

	protected $autoloadLanguage = true;

	/**
	 * Function to find the specified HTML tag in DOM and add customHeading in beginning.
	 *
	 * @param   object   $dom            The DOM object.
	 * @param   string   $tag            The HTML tag to be searched in DOM.
	 * @param   string   $customHeading  The custom heading which needs to be added before every heading.
	 * @param   string   &$html          The HTML string used to build DOM.
	 *
	 * @return  void
	 */
	public function processTag($dom, $tag, $customHeading, &$html)
	{
		$tagsItems = $dom->getElementsByTagName($tag);
		foreach ($tagsItems as $tagsItem) {
			$tagsItem->nodeValue = $customHeading . $tagsItem->nodeValue;
		}
		$html = $dom->saveHTML();
	}

	/**
	 * Function to process the HTML DOM and add custom heading before each heading.
	 *
	 * @param   object   $dom            The DOM object.
	 * @param   string   $customHeading  The custom heading which needs to be added before every heading.
	 * @param   string   &$html          The HTML string used to build DOM.
	 *
	 * @return  void
	 */
	public function processDOM(&$dom, $customHeading, &$html)
	{
		$this->processTag($dom, 'h1', $customHeading, $html);
		$dom = $this->loadHTML($html);
		$this->processTag($dom, 'h2', $customHeading, $html);
		$dom = $this->loadHTML($html);
		$this->processTag($dom, 'h3', $customHeading, $html);
		$dom = $this->loadHTML($html);
		$this->processTag($dom, 'h4', $customHeading, $html);
		$dom = $this->loadHTML($html);
		$this->processTag($dom, 'h5', $customHeading, $html);
		$dom = $this->loadHTML($html);
		$this->processTag($dom, 'h6', $customHeading, $html);
		$dom = $this->loadHTML($html);
	}

	/**
	 * Function to convert HTML string to DOM.
	 *
	 * @param   string   $html   The HTML string used to build DOM.
	 *
	 * @return  object   $dom    The DOM object.
	 */
	public function loadHTML($html)
	{
		libxml_use_internal_errors(true);
		$dom = new domDocument('1.0', 'utf-8');
		$dom->loadHTML($html);
		$dom->preserveWhiteSpace = false;
		return $dom;
	}

  public function onAfterRender()
	{
		if (!$this->app->isClient('site') || $this->app->getDocument()->getType() !== 'html')
		{
			return;
		}

		$customHeading = $this->params->get('custom_heading');

		// Don't manipulate HTML if customHeading is empty.
		if (empty($customHeading))
		{
			return;
		}

		// Get the response body.
		$html = $this->app->getBody();

		// Convert response body string into DOM.
		$dom = $this->loadHTML($html);

		// Do modifications to $dom.
		$this->processDOM($dom, $customHeading, $html);

		// Update the response body.
		$this->app->setBody($html);
		return;
	}
}
