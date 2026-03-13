<?php

namespace malkusch\bav;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../bootstrap.php";

/**
 * Tests FileValidator.
 *
 * @license WTFPL
 * @author Markus Malkusch <markus@malkusch.de>
 * @see BAV
 */
class FileValidatorTest extends TestCase
{

    public function testValidate()
    {
        $backend = new FileDataBackend();
        $file = $backend->getFile();

        $validator = new FileValidator();
        $validator->validate($file);
    }

    /**
     */
    public function testInvalidFileSize()
    {
        $this->expectException(InvalidFilesizeException::class);
        $validator = new FileValidator();
        $validator->validate(__FILE__);
    }

    /**
     */
    public function testInvalidLineLength()
    {
        $this->expectException(InvalidLineLengthException::class);
        $backend = new FileDataBackend();
        $file = $backend->getFile();

        $invalidFile = __DIR__ . "/../data/invalidLength.txt";
        copy($file, $invalidFile);

        $fp = fopen($invalidFile, "c");
        fputs($fp, "invalid line\n");

        $validator = new FileValidator();
        $validator->validate($invalidFile);
    }

    /**
     */
    public function testNotConstantLineLength()
    {
        $this->expectException(InvalidLineLengthException::class);
        $backend = new FileDataBackend();
        $file = $backend->getFile();

        $invalidFile = __DIR__ . "/../data/notConstantLength.txt";
        copy($file, $invalidFile);

        $fp = fopen($invalidFile, "a");
        fputs($fp, "X\n");

        $validator = new FileValidator();
        $validator->validate($invalidFile);
    }

    /**
     */
    public function testInvalidFirstLineContent()
    {
        $this->expectException(FieldException::class);
        $backend = new FileDataBackend();
        $file = $backend->getFile();

        $invalidFile = __DIR__ . "/../data/invalidFirstLineContent.txt";
        copy($file, $invalidFile);

        $fp = fopen($invalidFile, "c");
        fputs($fp, "XXX");

        $validator = new FileValidator();
        $validator->validate($invalidFile);
    }
}
