<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version $Id: Font_Table_glyf.php 46 2012-04-02 20:22:38Z fabien.menager $
 */

namespace FontLib\Glyph;

use FontLib\BinaryStream;
use FontLib\Table\Type\glyf;
use FontLib\TrueType\File;

/**
 * `glyf` font table.
 *
 * @package php-font-lib
 */
class Outline extends BinaryStream
{
    public $numberOfContours;
    public $xMin;
    public $yMin;

    // Data
    public $xMax;
    public $yMax;
    /**
     * @var string|null
     */
    public $raw;
    /**
     * @var \FontLib\Table\Type\glyf
     */
    protected $table;
    protected $offset;
    protected $size;

    function __construct(glyf $table, $offset = null, $size = null)
    {
        $this->table = $table;
        $this->offset = $offset;
        $this->size = $size;
    }

    /**
     * @param glyf $table
     * @param                 $offset
     * @param                 $size
     *
     * @return Outline
     */
    static function init(glyf $table, $offset, $size, BinaryStream $font)
    {
        $font->seek($offset);

        if ($font->readInt16() > -1) {
            /** @var OutlineSimple $glyph */
            $glyph = new OutlineSimple($table, $offset, $size);
        } else {
            /** @var OutlineComposite $glyph */
            $glyph = new OutlineComposite($table, $offset, $size);
        }

        $glyph->parse($font);

        return $glyph;
    }

    function parse(BinaryStream $font)
    {
        $font->seek($this->offset);

        $this->raw = $font->read($this->size);
    }

    function parseData()
    {
        $font = $this->getFont();
        $font->seek($this->offset);

        $this->numberOfContours = $font->readInt16();
        $this->xMin = $font->readFWord();
        $this->yMin = $font->readFWord();
        $this->xMax = $font->readFWord();
        $this->yMax = $font->readFWord();
    }

    /**
     * @return File
     */
    function getFont()
    {
        return $this->table->getFont();
    }

    function encode()
    {
        $font = $this->getFont();

        return $font->write($this->raw, mb_strlen((string)$this->raw, '8bit'));
    }

    function getSVGContours()
    {
        // Inherit
    }

    function getGlyphIDs()
    {
        return array();
    }
}

