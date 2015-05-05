<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) 2010-2011 Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DashboardBundle\Block;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface as BlockContextInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;

class GoogleDashboardBlockService extends BaseBlockService
{
    protected $initialized;
    protected $statistics;
    protected $request;
    protected $authTemplate;
    
    protected $width;
    protected $height;
    protected $days;
    
    public function __construct($name, EngineInterface $templating, array $config)
    {
        parent::__construct($name, $templating);
        $this->initialized = false;
        $this->statistics = $config['stats'];
        $this->authTemplate = $config['authorize_template'];
        $this->width = $config['chart_width'];
        $this->height = $config['chart_height'];
        $this->days = $config['chart_days'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        // TODO: Implement validateBlock() method.
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $block, Response $response = null)
    {
        if ($this->initialized === false) {
            $content = $this->getInitTemplate();
            $this->initialized = true;
        }
        
        $content .= $this->getTemplating()->render('SonataDashboardBundle:Block/Google:dashboard.html.twig', array(
            'width'    => $this->width,
            'height'   => $this->height,
            'days'     => $this->days
        ));
        
        return new Response($content);
    }

    protected function getInitTemplate()
    {
        return $this->getTemplating()->render('SonataDashboardBundle:Block/Google:init.html.twig', array(
                'statistics' => $this->statistics,
                'authTemplate' => $this->authTemplate
        ));
    }
}
