<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2010 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Core\Service\TemplateEngine;

use Alchemy\Phrasea\Core,
    Alchemy\Phrasea\Core\Service,
    Alchemy\Phrasea\Core\Service\ServiceAbstract,
    Alchemy\Phrasea\Core\Service\ServiceInterface;

class Twig extends ServiceAbstract implements ServiceInterface
{

  /**
   *
   * @var \Twig_Environment
   */
  protected $twig;
  protected $templatesPath = array();

  public function __construct($name, Array $options)
  {
    parent::__construct($name, $options);
    $this->options = $this->resolveOptions($options);
    $this->templatesPath = $this->resolvePaths();

    try
    {
      $loader = new \Twig_Loader_Filesystem($this->templatesPath);
      $this->twig = new \Twig_Environment($loader, $this->options);
      $this->loadGlobals();
      $this->loadExtensions();
      $this->loadFilters();
    }
    catch (\Exception $e)
    {
      throw new \Exception(sprintf('Unable to load twig service %s', $e->getMessage()));
    }
  }

  /**
   * Load phraseanet global variable
   * it' s like any other template variable, 
   * except that it’s available in all templates and macros
   * @return void
   */
  private function loadGlobals()
  {
    $appbox = \appbox::get_instance();
    $session = $appbox->get_session();
    $browser = \Browser::getInstance();
    $registry = $appbox->get_registry();
    $request = new \http_request();

    $user = false;
    if ($session->is_authenticated())
    {
      $user = \User_Adapter::getInstance($session->get_usr_id(), $appbox);
    }

    $core = \bootstrap::getCore();
    $eventsmanager = \eventsmanager_broker::getInstance($appbox, $core);

    $this->twig->addGlobal('session', $session);
    $this->twig->addGlobal('version_number', $core->getVersion()->getNumber());
    $this->twig->addGlobal('version_name', $core->getVersion()->getName());
    $this->twig->addGlobal('core', $core);
    $this->twig->addGlobal('browser', $browser);
    $this->twig->addGlobal('request', $request);
    $this->twig->addGlobal('events', $eventsmanager);
    $this->twig->addGlobal('display_chrome_frame', $registry->is_set('GV_display_gcf') ? $registry->get('GV_display_gcf') : true);
    $this->twig->addGlobal('user', $user);
    $this->twig->addGlobal('current_date', new \DateTime());
    $this->twig->addGlobal('home_title', $registry->get('GV_homeTitle'));
    $this->twig->addGlobal('meta_description', $registry->get('GV_metaDescription'));
    $this->twig->addGlobal('meta_keywords', $registry->get('GV_metaKeywords'));
    $this->twig->addGlobal('maintenance', $registry->get('GV_maintenance'));
    $this->twig->addGlobal('registry', $registry);
  }

  /**
   * Load twig extensions
   * @return void
   */
  private function loadExtensions()
  {
    $this->twig->addExtension(new \Twig_Extension_Core());
    $this->twig->addExtension(new \Twig_Extension_Optimizer());
    $this->twig->addExtension(new \Twig_Extension_Escaper());
    $this->twig->addExtension(new \Twig_Extensions_Extension_Debug());
    // add filter trans
    $this->twig->addExtension(new \Twig_Extensions_Extension_I18n());
    // add filter localizeddate
    $this->twig->addExtension(new \Twig_Extensions_Extension_Intl());
    // add filters truncate, wordwrap, nl2br
    $this->twig->addExtension(new \Twig_Extensions_Extension_Text());
  }

  /**
   * Load twig filters
   * return void
   */
  private function loadFilters()
  {
    $this->twig->addFilter('serialize', new \Twig_Filter_Function('serialize'));
    $this->twig->addFilter('sbas_names', new \Twig_Filter_Function('phrasea::sbas_names'));
    $this->twig->addFilter('sbas_name', new \Twig_Filter_Function('phrasea::sbas_names'));
    $this->twig->addFilter('unite', new \Twig_Filter_Function('p4string::format_octets'));
    $this->twig->addFilter('stristr', new \Twig_Filter_Function('stristr'));
    $this->twig->addFilter('implode', new \Twig_Filter_Function('implode'));
    $this->twig->addFilter('stripdoublequotes', new \Twig_Filter_Function('stripdoublequotes'));
    $this->twig->addFilter('phraseadate', new \Twig_Filter_Function('phraseadate::getPrettyString'));
    $this->twig->addFilter('format_octets', new \Twig_Filter_Function('p4string::format_octets'));
    $this->twig->addFilter('geoname_display', new \Twig_Filter_Function('geonames::name_from_id'));
    $this->twig->addFilter('get_collection_logo', new \Twig_Filter_Function('collection::getLogo'));
    $this->twig->addFilter('nl2br', new \Twig_Filter_Function('nl2br'));
    $this->twig->addFilter('floor', new \Twig_Filter_Function('floor'));
    $this->twig->addFilter('bas_name', new \Twig_Filter_Function('phrasea::bas_names'));
    $this->twig->addFilter('bas_names', new \Twig_Filter_Function('phrasea::bas_names'));
    $this->twig->addFilter('basnames', new \Twig_Filter_Function('phrasea::bas_names'));
    $this->twig->addFilter('urlencode', new \Twig_Filter_Function('urlencode'));
    $this->twig->addFilter('sbasFromBas', new \Twig_Filter_Function('phrasea::sbasFromBas'));
    $this->twig->addFilter('str_replace', new \Twig_Filter_Function('str_replace'));
    $this->twig->addFilter('strval', new \Twig_Filter_Function('strval'));
    $this->twig->addFilter('key_exists', new \Twig_Filter_Function('array_key_exists'));
    $this->twig->addFilter('array_keys', new \Twig_Filter_Function('array_keys'));
    $this->twig->addFilter('round', new \Twig_Filter_Function('round'));
    $this->twig->addFilter('formatdate', new \Twig_Filter_Function('phraseadate::getDate'));
    $this->twig->addFilter('getPrettyDate', new \Twig_Filter_Function('phraseadate::getPrettyString'));
    $this->twig->addFilter('prettyDate', new \Twig_Filter_Function('phraseadate::getPrettyString'));
    $this->twig->addFilter('prettyString', new \Twig_Filter_Function('phraseadate::getPrettyString'));
    $this->twig->addFilter('formatoctet', new \Twig_Filter_Function('p4string::format_octets'));
    $this->twig->addFilter('getDate', new \Twig_Filter_Function('phraseadate::getDate'));
    $this->twig->addFilter('geoname_name_from_id', new \Twig_Filter_Function('geonames::name_from_id'));
  }

  /**
   * Getter
   * @return \Twig_Environment
   */
  public function getTwig()
  {
    return $this->twig;
  }

  private function getDefaultTemplatePath()
  {
    $registry = \registry::get_instance();

    return array(
        'mobile' => array(
            $registry->get('GV_RootPath') . 'config/templates/mobile',
            $registry->get('GV_RootPath') . 'templates/mobile'
        ),
        'web' => array(
            $registry->get('GV_RootPath') . 'config/templates/web',
            $registry->get('GV_RootPath') . 'templates/web'
        )
    );
  }

  /**
   * Set default templates Path
   * According to the client device
   * @return void
   */
  private function resolvePaths()
  {
    $browser = \Browser::getInstance();

    $templatePath = $this->getDefaultTemplatePath();

    if ($browser->isTablet() || $browser->isMobile())
    {
      $paths = $templatePath['mobile'];
    }
    else
    {
      $paths = $templatePath['web'];
    }

    return $paths;
  }

  /**
   * Set configuration options
   * @param array $configuration
   * @return Array 
   */
  private function resolveOptions(Array $configuration)
  {
    $registry = \registry::get_instance();
    $options = $configuration;

    $options["optimizer"] = !!$options["optimizer"] ? -1 : 0;
    $options['cache'] = $registry->get('GV_RootPath') . 'tmp/cache_twig';
    $options['charset'] = 'utf-8';

    if (!!$options["debug"])
    {
      unset($options["cache"]);
    }

    return $options;
  }

  public function getService()
  {
    return $this->twig;
  }

  public function getType()
  {
    return 'twig';
  }

  public function getScope()
  {
    return 'template_engine';
  }

}