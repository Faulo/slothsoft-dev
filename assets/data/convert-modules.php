<?php

use Slothsoft\Core\DOMHelper;
use Slothsoft\Core\FileSystem;
use Slothsoft\Farah\HTTPFile;
use Slothsoft\Farah\Module\Module;
use Slothsoft\Farah\Module\AssetUses\DOMWriterFromFileTrait;
use Slothsoft\Farah\Module\AssetUses\FileWriterInterface;
use Slothsoft\Farah\Module\AssetUses\FileWriterStringFromFileTrait;
use Slothsoft\Farah\Module\Assets\AssetInterface;
use Slothsoft\Farah\Exception\ExceptionContext;

$ret = [];

$moduleList = FileSystem::scanDir(SERVER_ROOT . 'vendor/slothsoft', FileSystem::SCANDIR_REALPATH);

foreach ($moduleList as $modulePath) {
    $moduleName = basename($modulePath);
    $moduleFile = $modulePath . DIRECTORY_SEPARATOR . 'module.xml';
    if (file_exists($moduleFile)) {
        $ret[] = $this->createClosure(
            ['path' => "/$moduleName"],
            function(AssetInterface $asset) use ($moduleName, $moduleFile) {
                $backupFile = $moduleFile . '.backup';
                if (!file_exists($backupFile)) {
                    copy($moduleFile, $moduleFile . '.backup');
                }
                return new class($asset, $moduleName, $backupFile, $moduleFile) implements FileWriterInterface {
                    use DOMWriterFromFileTrait;
                    use FileWriterStringFromFileTrait;
                    
                    private $contextAsset;
                    private $moduleName;
                    private $sourceFile;
                    private $targetFile;
                    public function __construct(AssetInterface $contextAsset, string $moduleName, string $sourceFile, string $targetFile) {
                        $this->contextAsset = $contextAsset;
                        $this->moduleName = $moduleName;
                        $this->sourceFile = $sourceFile;
                        $this->targetFile = $targetFile;
                    }
                    public function toFile() : HTTPFile {
                        $templateAsset = $this->contextAsset->getOwnerModule()->getAsset('/xsl/convert-modules');
                        $dom = new DOMHelper();
                        try {
                            return $dom->transformToFile(
                                $this->sourceFile,
                                $templateAsset,
                                ['module' => $this->moduleName],
                                HTTPFile::createFromPath($this->targetFile)
                            );
                        } catch(Throwable $e) {
                            throw ExceptionContext::append(
                                $e, [
                                    
                                    'asset' => $this->contextAsset,
                                    'module' => new Module('slothsoft', $this->moduleName)
                                   
                                ]
                            );
                        }
                    }
                };
            }
        );
    }
}

return $ret;