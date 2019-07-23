<?php
namespace Tinyga\ImageOptimizer\OptimizationRequest;

use Tinyga\ImageOptimizer\Image\ImageInterface;

class OutputParameters implements \JsonSerializable
{
    const LOSSLESS_QUALITY = 100;
    const DEFAULT_LOSSY_QUALITY = 95;

    const MIN_QUALITY = 1;
    const MAX_QUALITY = self::LOSSLESS_QUALITY;

    // keep all metadata (not only listed below)
    const META_ALL = 'all';

    const META_PROFILE = 'profile';
    const META_DATE = 'date';
    const META_COPYRIGHT = 'copyright';
    const META_GEOTAG = 'geotag';
    const META_ORIENTATION = 'orientation';

    const SUBSAMPLING_AUTO = 'auto';
    const SUBSAMPLING_ORIGINAL = 'original';
    const SUBSAMPLING_420 = '4:2:0';
    const SUBSAMPLING_422 = '4:2:2';
    const SUBSAMPLING_444 = '4:4:4';

    protected static $allowed_keep_metadata = [
        self::META_ALL,
        self::META_PROFILE,
        self::META_DATE,
        self::META_COPYRIGHT,
        self::META_GEOTAG,
        self::META_ORIENTATION,
    ];

    protected static $allowed_output_types = [
        ImageInterface::TYPE_JPEG,
        ImageInterface::TYPE_PNG,
        ImageInterface::TYPE_GIF,
    ];

    protected static $allowed_chroma_subsampling = [
        self::SUBSAMPLING_AUTO,
        self::SUBSAMPLING_ORIGINAL,
        self::SUBSAMPLING_420,
        self::SUBSAMPLING_422,
        self::SUBSAMPLING_444,
    ];

    const DEFAULT_BACKGROUND_COLOR = '000000';

    /**
     * Convert image to given format
     *
     * @var string|null
     */
    protected $image_type;

    /**
     * Optimization quality
     *
     * @see OutputParameters::LOSSLESS_QUALITY (100) = pixel perfect lossless
     * @see OutputParameters::DEFAULT_LOSSY_QUALITY (95) = perceptional lossless (but not pixel perfect)
     *
     * @var int
     */
    protected $quality = self::LOSSLESS_QUALITY;

    /**
     * List of metadata to keep in image
     *
     * - empty array = remove all metadata
     * @see OutputParameters::META_ALL = do not remove any metadata
     * @see OutputParameters::META_*  = keep only listed metadata
     *
     * @var array
     */
    protected $keep_metadata = [self::META_ALL];

    /**
     * Chroma subsampling encoding for JPEGs
     *
     * @see OutputParameters::SUBSAMPLING_AUTO = let Tinyga to decide (may be wrong in very rare cases)
     * @see OutputParameters::SUBSAMPLING_ORIGINAL = keep original encoding
     * @see OutputParameters::SUBSAMPLING_* = other methods
     *
     * @var string
     */
    protected $jpeg_chroma_subsampling = self::SUBSAMPLING_AUTO;

    /**
     * RGB color of background when converting image with alpha channel to JPEG
     *
     * @var string
     */
    protected $background_color = self::DEFAULT_BACKGROUND_COLOR;

    /**
     * @param int|null $quality
     * @param array|null $keep_metadata
     * @param string|null $mime_type
     */
    public function __construct($quality = null, array $keep_metadata = null, $mime_type = null)
    {
        if($quality !== null){
            $this->setQuality($quality);
        }

        if($keep_metadata !== null){
            $this->setKeepMetadata($keep_metadata);
        }

        if($mime_type !== null){
            $this->setImageType($mime_type);
        }
    }


    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @return bool
     */
    public function isLossLess()
    {
        return $this->quality === self::LOSSLESS_QUALITY;
    }

    /**
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $quality = (int)$quality;
        if($quality < static::MIN_QUALITY || $quality > static::MAX_QUALITY){
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid quality (must be %d - %d)",
                    static::MIN_QUALITY,
                    static::MAX_QUALITY
                )
            );
        }
        $this->quality = $quality;
    }

    /**
     * @return array
     */
    public function getKeepMetadata()
    {
        return $this->keep_metadata;
    }

    /**
     * @param array $keep_metadata
     */
    public function setKeepMetadata(array $keep_metadata)
    {
        foreach($keep_metadata as $meta){
            if(!in_array($meta, self::$allowed_keep_metadata, true)){
                throw new \InvalidArgumentException("Metadata value '{$meta}' is not supported");
            }
        }
        $this->keep_metadata = array_values(array_unique($keep_metadata));
    }

    /**
     * @return string|null
     */
    public function getImageType()
    {
        return $this->image_type;
    }

    /**
     * @param string|null $image_type
     */
    public function setImageType($image_type)
    {
        if(
            $image_type !== null &&
            !in_array($image_type, self::$allowed_output_types, true)
        ){
            throw new \InvalidArgumentException("Unsupported output mime type '{$image_type}'");
        }
        $this->image_type = $image_type;
    }


    /**
     * @return string
     */
    public function getJpegChromaSubsampling()
    {
        return $this->jpeg_chroma_subsampling;
    }

    /**
     * @param string $jpeg_chroma_subsampling
     */
    public function setJpegChromaSubsampling($jpeg_chroma_subsampling)
    {
        if(!in_array($jpeg_chroma_subsampling, self::$allowed_chroma_subsampling, true)){
            throw new \InvalidArgumentException("Invalid JPEG chroma subsampling '{$jpeg_chroma_subsampling}'");
        }
        $this->jpeg_chroma_subsampling = $jpeg_chroma_subsampling;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->background_color;
    }

    /**
     * @param string $background_color
     */
    public function setBackgroundColor($background_color)
    {
        if(!preg_match('~^[0-9a-f]{6}$~', (string)$background_color)){
            throw new \InvalidArgumentException("Invalid transparent color");
        }
        $this->background_color = (string)$background_color;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function($value){
            // skip not defined
            return $value !== null;
        });
    }
}
