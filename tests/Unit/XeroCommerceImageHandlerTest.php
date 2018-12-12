<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 2:26 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use App\Providers\StorageServiceProvider;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Xpressengine\Media\Thumbnailer;
use Xpressengine\Plugins\XeroCommerce\Handlers\XeroCommerceImageHandler;
use Xpressengine\Storage\File;
use Xpressengine\Storage\Storage;

class XeroCommerceImageHandlerTest extends DefaultSet
{

    public function setUp()
    {
        parent::setUp();
        app()->bind('xe.storage', function () {
            $storage = \Mockery::mock(Storage::class);
            $storage->shouldReceive('create')->andReturn(new File());
            $storage->shouldReceive('find')->andReturn(new File());
            $storage->shouldReceive('delete')->andReturn(new File());
            return $storage;
        });
        Thumbnailer::setManager(new ImageManager());
    }

    public function test__construct()
    {
        $handler = new XeroCommerceImageHandler();
        $this->assertNotNull($handler);
    }

    public function testResize()
    {
        $handler = new XeroCommerceImageHandler();
        $file = UploadedFile::fake()->image('../../assets/sample/tmp_cross.jpg');
        $img = $handler->resize($file, 100, 100);
        $this->assertNotNull($img);
    }

    public function testSaveImage()
    {
        $handler = new XeroCommerceImageHandler();
        $file = UploadedFile::fake()->image('../../assets/sample/tmp_cross.jpg');
        $image = $handler->saveImage($file, './testImage');
        $this->assertInstanceOf(File::class, $image);
    }

    public function testRemoveFile()
    {
        $handler = new XeroCommerceImageHandler();
        $result = $handler->removeFile('file_id');
        $this->assertNull($result);
    }

    public function getImageUrlByFileId()
    {
        $handler = new XeroCommerceImageHandler();
        $result = $handler->getImageUrlByFileId('none_file_id');
        $this->assertEquals('',$result);

    }
}
