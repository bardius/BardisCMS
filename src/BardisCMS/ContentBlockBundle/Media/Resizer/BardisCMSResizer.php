<?php
/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\ContentBlockBundle\Media\Resizer;

use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Gaufrette\File;
use Sonata\MediaBundle\Model\MediaInterface;
use Imagine\Image\ImageInterface;
use Imagine\Exception\InvalidArgumentException;
use Sonata\MediaBundle\Resizer\ResizerInterface;
use Imagine\Image\Point;

class BardisCMSResizer implements ResizerInterface
{
    protected $adapter;

    protected $mode;

    /**
     * @param ImagineInterface $adapter
     * @param string           $mode
     */
    public function __construct(ImagineInterface $adapter, $mode = 'inset')
    {
        $this->adapter = $adapter;
        $this->mode    = $mode;
    }

    /**
     * {@inheritdoc}
     */
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings)
    {
        if (!isset($settings['width'])) {
            throw new \RuntimeException(sprintf('Width parameter is missing in context "%s" for provider "%s"', $media->getContext(), $media->getProviderName()));
        }
        
        $image          = $this->adapter->load($in->getContent());
        $size           = $image->getSize();
        $originalRatio  = $size->getWidth() / $size->getHeight();

        if ($settings['width'] == null && $settings['height'] == null) {
            $settings['width']  = $size->getWidth();
            $settings['height'] = $size->getHeight();            
        }
        
        if (!$settings['height']) {
            $settings['height'] = $settings['width'] * $originalRatio;
        }
        if($size->getHeight() > $size->getWidth()) {
            $thRatio    = $settings['width'] / $settings['height'];
            $higher     = $size->getHeight();
            $lower      = $size->getWidth();
            $newHeight  = $lower / $thRatio;
            
            if($newHeight < $settings['height'])
            {
                $newHeight  = $settings['height'];
            }
            $crop       = ($higher - ($newHeight))/2;
            if ($crop > 0)
            {
                $point      = new Point(0, $crop);
                $image->crop($point, new Box($lower, $newHeight));               
            }
        } else {
            $thRatio    = $settings['width'] / $settings['height'];
            $higher     = $size->getWidth();
            $lower      = $size->getHeight();
            $newWidth   = $thRatio * $lower;
            if($newWidth < $settings['width'])
            {
                $newWidth  = $settings['width'];
            }
            $crop   = $higher - $newWidth;
            if ($crop > 0)
            {
                $point  = new Point($crop / 2, 0);        
                $image->crop($point, new Box($newWidth, $lower));               
            }
        }
        
        $content = $image
                    ->thumbnail(new Box($settings['width'], $settings['height']), $this->mode)
                    ->get($format, array('quality' => $settings['quality']));

        $out->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function getBox(MediaInterface $media, array $settings)
    {
        $size = $media->getBox();

        if ($settings['width'] == null && $settings['height'] == null) {
            $settings['width']  = $size->getWidth();
            $settings['height'] = $size->getHeight();            
        }

        if ($settings['height'] == null) {
            $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());
        }

        if ($settings['width'] == null) {
            $settings['width'] = (int) ($settings['height'] * $size->getWidth() / $size->getHeight());
        }

        return new Box($settings['width'], $settings['height']);
    }
}