<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\ContentBlockBundle\Media\Resizer;

use Gaufrette\File;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Resizer\ResizerInterface;

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
        $this->mode = $mode;
    }

    /**
     * {@inheritdoc}
     */
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings)
    {
        $image = $this->adapter->load($in->getContent());
        $size = $image->getSize();
        $originalRatio = $size->getWidth() / $size->getHeight();

        if (($settings['width'] === null && $settings['height'] === null) || ($settings['width'] === false && $settings['height'] === false)) {
            $settings['width'] = $size->getWidth();
            $settings['height'] = $size->getHeight();
        }

        if (!$settings['width']) {
            $settings['width'] = $size->getWidth();
        }

        if (!$settings['height']) {
            $settings['height'] = $settings['width'] * $originalRatio;
        }

        // Scale Image up if too small
        if ($size->getHeight() > $size->getWidth()) {
            if ($settings['height'] > $size->getHeight()) {
                $image->resize(new Box($settings['height'] * $originalRatio, $settings['height']));
            }
        } else {
            if ($settings['width'] > $size->getWidth()) {
                $image->resize(new Box($settings['width'], $settings['width'] / $originalRatio));
            }
        }

        // Re calculate size and aspect ratio
        $size = $image->getSize();
        $originalRatio = $size->getWidth() / $size->getHeight();

        // Resize and on center
        if ($size->getHeight() > $size->getWidth()) {
            $thRatio = $settings['width'] / $settings['height'];
            $higher = $size->getHeight();
            $lower = $size->getWidth();
            $newWidth = $thRatio * $higher;
            $newHeight = $lower / $thRatio;

            if ($newWidth < $settings['width']) {
                $newWidth = $settings['width'];
            }

            if ($newHeight < $settings['height']) {
                $newHeight = $settings['height'];
            }

            $crop = ($higher - $newHeight) / 2;

            if ($crop > 0) {
                $point = new Point(0, $crop);
                $image->crop($point, new Box($lower, $newHeight));
            } else {
                $cropHigher = -($crop * $thRatio);
                $point = new Point($cropHigher, 0);
                $image->crop($point, new Box($newWidth, $higher));
            }
        } else {
            $thRatio = $settings['width'] / $settings['height'];
            $higher = $size->getWidth();
            $lower = $size->getHeight();
            $newWidth = $thRatio * $lower;
            $newHeight = $higher / $thRatio;

            if ($newWidth < $settings['width']) {
                $newWidth = $settings['width'];
            }

            if ($newHeight < $settings['height']) {
                $newHeight = $settings['height'];
            }

            $crop = ($higher - $newWidth) / 2;

            if ($crop > 0) {
                $point = new Point($crop, 0);
                $image->crop($point, new Box($newWidth, $lower));
            } elseif ($crop < 0) {
                $cropLower = -($crop / $thRatio);
                $point = new Point(0, $cropLower);
                $image->crop($point, new Box($higher, $newHeight));
            }
        }

        // Generate thumbnail
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

        if (($settings['width'] === null && $settings['height'] === null) || ($settings['width'] === false && $settings['height'] === false)) {
            $settings['width'] = $size->getWidth();
            $settings['height'] = $size->getHeight();
        }

        if (!$settings['width']) {
            $settings['width'] = (int) ($settings['height'] * $size->getWidth() / $size->getHeight());
        }

        if (!$settings['height']) {
            $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());
        }

        return new Box($settings['width'], $settings['height']);
    }
}
