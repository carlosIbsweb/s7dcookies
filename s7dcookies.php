<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.sef
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! SEF Plugin.
 *
 * @since  1.5
 */
class PlgSystemS7dcookies extends JPlugin
{
	/**
	 * Application object.
	 *
	 * @var    JApplicationCms
	 * @since  3.5
	 */
	protected $app;

	/**
	 * Add the canonical uri to the head.
	 *
	 * @return  void
	 *
	 * @since   3.5
	 */
	public function onAfterDispatch()
	{

		if(!$this->app->isClient('site')){
			return;
		};

		$doc = $this->app->getDocument();
		$plg_path = JURI::root(true).'/plugins/system/s7dcookies';
		$doc->addStyleSheet($plg_path.'/css/style.css');

		self::styles($doc,$this->params);
		self::render($doc,$this->params);

	}

	protected static function styles($doc,$p){

		$style = array();
		$style[] = '
			.acceptCook .acCoBtns a.saccept {
				background: '.$p->get('btnBackColor').';
				color: '.$p->get('btnTextColor').';
			}
		';

		$style = implode('',$style);

		$doc->addStyleDeclaration($style);
	}

	/**
	 * Replace the matched tags.
	 *
	 * @param   array  &$matches  An array of matches (see preg_match_all).
	 *
	 * @return  string
	 *
	 * @deprecated  4.0  No replacement.
	 */
	protected static function render($doc,$p)
	{

		$btnText = empty($p->get('btnText')) ? 'OK' : $p->get('btnText');
		$script = "

		jQuery(function($){
			var montCo = `
			    <div class=\"acceptCook\">
			        <p>
			            ".$p->get('text')."
			        </p>
			        <p class=\"acCoBtns\">
			            <a data-cook=\"canceled\" class=\"s7d-cookie-btn\">Recusar Cookies</a>
			            <a data-cook=\"accept\" class=\"s7d-cookie-btn saccept\">".$btnText."</a>
			        </p>
			    </div>
			`;

			if(!localStorage.getItem('s7dAcceptC'))
			{
			    $('body').prepend(montCo);
			}

			$(document).on('click','.s7d-cookie-btn',function(){
			    $('.acceptCook').fadeOut(function(){
			        localStorage.setItem('s7dAcceptC',$(this).data('cook'));
			    })   
			})
		})";

		$doc->addScriptDeclaration($script);

		
	}
}
