<?php

namespace Resources\Utils;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class Builder
{
    /**
     * Resizes and optimizes an image for the web.
     * @param string $sourcePath Path of the original image.
     * @param string $destPath Destination path for the optimized image.
     * @param int $maxWidth Desired max width for the image.
     * @param int $quality Image quality (for JPEG).
     * @return true If the image optimization is successful.
     * @throws \Exception If the image optimization fails.
     */
    public static function image($sourcePath, $destPath, $widths = [32, 64, 128, 256, 512, 1024, 2048, 4096], $quality = 70)
    {
        if (!file_exists($sourcePath)) {
            throw new \Exception("File not found: $sourcePath\n");
        }

        $imageInfo = getimagesize($sourcePath);
        if ($imageInfo === false) {
            throw new \Exception("Not a valid image file: $sourcePath\n");
        }

        list($originalWidth, $originalHeight, $imageType) = $imageInfo;

        $targetWidth = $originalWidth;
        $targetHeight = $originalHeight;

        $newImage = imagecreatetruecolor($targetWidth, $targetHeight);

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        if (in_array($extension, ['svg', "ico"])) {
            if (!copy($sourcePath, $destPath)) {
                throw new \Exception("Failed to copy SVG image from $sourcePath to $destPath\n");
            }
            return true;
        }

        $sourceImage = imagecreatefromstring(file_get_contents($sourcePath));

        if ($sourceImage === false) {
            if (copy($sourcePath, $destPath)) {
                throw new \Exception("Failed to create image from file, but file was copied successfully: $sourcePath\n");
            } else {
                throw new \Exception("Failed to create image from file and failed to copy file: $sourcePath\n");
            }
        }

        // Processamento da imagem
        $newImage = imagecreatetruecolor($targetWidth, $targetHeight);

        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        imagefilledrectangle($newImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);



        // Salvar a imagem
        if (!imagewebp($newImage, $destPath)) {
            // If failed to save image, copy the original image to the destination path
            if (!copy($sourcePath, $destPath)) {
                throw new \Exception("Failed to copy image from $sourcePath to $destPath\n");
            }
            return true;
        }

        imagedestroy($newImage);

        foreach ($widths as $targetWidth) {
            $targetHeight = round($originalHeight / $originalWidth * $targetWidth);

            $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefilledrectangle($newImage, 0, 0, $targetWidth, $targetHeight, $transparent);

            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);

            $destPathWidth = preg_replace('/\.webp$/', "_{$targetWidth}.webp", $destPath);

            if (!imagewebp($newImage, $destPathWidth, $quality)) {
                throw new \Exception("Failed to save image to $destPathWidth\n");
            }

            imagedestroy($newImage);
        }

        imagedestroy($sourceImage);
        return true;
    }

    /**
     * Optimizes a video using ffmpeg.
     * @param string $sourcePath Path of the original video.
     * @param string $destPath Destination path for the optimized video.
     * @return true If the video optimization is successful.
     * @throws \Exception If the video optimization fails.
     */
    public static function video($sourcePath, $destPath)
    {
        // Comando ffmpeg com sobrescrita automática e execução silenciosa
        $cmd = "ffmpeg -y -loglevel error -i {$sourcePath} -c:v libx264 -crf 28 -preset slow -c:a aac -b:a 128k {$destPath}";
        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Failed to optimize video: $sourcePath\n");
        } else {
            return true;
        }
    }

    /**
     * Minifica um arquivo CSS e adiciona a extensão .min ao nome do arquivo minificado.
     *
     * @param string $sourcePath Caminho do arquivo CSS original.
     * @param string $destPath Caminho de destino para o arquivo minificado.
     * @throws \Exception Se a minificação falhar.
     */
    public static function minifyCSS($sourcePath, $destPath)
    {
        $cssContent = file_get_contents($sourcePath);
        $minifier = new CSS();
        $minifier->add($cssContent);
        $minifiedCSS = $minifier->minify();
        file_put_contents($destPath, $minifiedCSS);
    }

    /**
     * Minifica um arquivo JS e adiciona a extensão .min ao nome do arquivo minificado.
     *
     * @param string $sourcePath Caminho do arquivo JS original.
     * @param string $destPath Caminho de destino para o arquivo minificado.
     * @throws \Exception Se a minificação falhar.
     */
    public static function minifyJS($sourcePath, $destPath)
    {
        $jsContent = file_get_contents($sourcePath);
        $minifier = new JS();
        $minifier->add($jsContent);
        $minifiedJS = $minifier->minify();
        file_put_contents($destPath, $minifiedJS);
    }

    /**
     * Agrupa vários arquivos CSS em um único arquivo minificado.
     *
     * @param array $sourcePaths Array de caminhos dos arquivos CSS originais.
     * @param string $destPath Caminho de destino para o arquivo CSS agrupado e minificado.
     * @throws \Exception Se a minificação ou agrupamento falhar.
     */
    public static function bundleCSS($sourcePaths, $destPath)
    {
        $minifier = new CSS();

        foreach ($sourcePaths as $sourcePath) {
            $cssContent = file_get_contents($sourcePath);
            $minifier->add($cssContent);
        }

        $minifiedCSS = $minifier->minify();

        if ($minifiedCSS) {
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            if (is_dir($destPath)) {
                $destPath = rtrim($destPath, '/') . '/bundle.css';
            }
            file_put_contents($destPath, $minifiedCSS);
        }
    }

    /**
     * Agrupa vários arquivos JS em um único arquivo minificado.
     *
     * @param array $sourcePaths Array de caminhos dos arquivos JS originais.
     * @param string $destPath Caminho de destino para o arquivo JS agrupado e minificado.
     * @throws \Exception Se a minificação ou agrupamento falhar.
     */
    public static function bundleJS($sourcePaths, $destPath)
    {
        $minifier = new JS();

        foreach ($sourcePaths as $sourcePath) {
            $jsContent = file_get_contents($sourcePath);
            $minifier->add($jsContent);
        }

        $minifiedJS = $minifier->minify();
        if ($minifiedJS) {
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            if (is_dir($destPath)) {
                $destPath = rtrim($destPath, '/') . '/bundle.js';
            }
            file_put_contents($destPath, $minifiedJS);
        }
    }

    public static function assets()
    {
        $datetime = new \DateTime("now");
        $timestamp = $datetime->format('ymdHis');
        self::createOrUpdateBuildEnv($timestamp);

        // Obtém todos os diretórios de níveis de usuário dentro do diretório de ativos base
        $userLevels = glob(APP_FOLDER. "*/assets", GLOB_ONLYDIR);

        foreach ($userLevels as $levelPath) {
            // Obtém o nome do usuário a partir do caminho do diretório
            $userName = basename(dirname($levelPath));
            // Define o caminho dos diretórios de origem e destino para o usuário
            $srcPath = "$levelPath/src";
            $destPath = APP_FOLDER."$userName/assets/build/$timestamp";

            // Processa os arquivos no diretório de origem e os move para o diretório de destino
            self::filesInDirectory($srcPath, $destPath);
        }
    }

    public static function filesInDirectory($sourcePath, $destPath)
    {
        $cssFiles = [];
        $jsFiles = [];

        self::processRecursively($sourcePath, $destPath, $cssFiles, $jsFiles);

        $minifiedJsPath = $destPath . "/bundle.js";
        $minifiedCssPath = $destPath . "/bundle.css";

        self::bundleJS($jsFiles, $minifiedJsPath);
        self::bundleCSS($cssFiles, $minifiedCssPath);

    }

    private static function processRecursively($sourcePath, $destPath, &$cssFiles, &$jsFiles, $sourceBasePath = null, $destBasePath = null)
    {
        if ($sourceBasePath === null) {
            $sourceBasePath = $sourcePath;
        }
        if ($destBasePath === null) {
            $destBasePath = $destPath;
        }

        if ($sourcePath == $destBasePath) {
            return;
        }

        $files = scandir($sourcePath);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $destPath = str_replace($sourceBasePath, $destBasePath, $sourcePath);
            $filePath = $sourcePath . DIRECTORY_SEPARATOR . $file;
            $destFilePath = $destPath . DIRECTORY_SEPARATOR . $file;
            $relativeFilePath = str_replace($sourceBasePath, '', $filePath);
            if (is_dir($filePath)) {
                self::processRecursively(
                    $filePath,
                    $destFilePath,
                    $cssFiles,
                    $jsFiles,
                    $sourceBasePath,
                    $destBasePath
                );
            } else {
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0777, true);
                }
                $imageTypes = array(
                    IMAGETYPE_GIF,
                    IMAGETYPE_JPEG,
                    IMAGETYPE_PNG,
                    IMAGETYPE_WEBP,
                    IMAGETYPE_BMP,
                    IMAGETYPE_ICO,
                    IMAGETYPE_TIFF_II,
                    IMAGETYPE_TIFF_MM,
                    IMAGETYPE_JPC,
                    IMAGETYPE_JP2,
                    IMAGETYPE_JPX,
                    IMAGETYPE_JB2,
                    IMAGETYPE_SWC,
                    IMAGETYPE_IFF,
                    IMAGETYPE_WBMP,
                    IMAGETYPE_XBM
                );
                if (in_array(exif_imagetype($filePath), $imageTypes)) {
                    $destFilePath = preg_replace('/\.\w+$/i', '.webp', $destFilePath);
                    $relativeDestFilePath = str_replace($destBasePath, '', $destFilePath);
                    try {
                        Builder::image($filePath, $destFilePath); // Adjust width and quality as needed
                        Shell::printSuccess("Image optimized: " . $relativeDestFilePath);
                    } catch (\Exception $e) {
                        Shell::printError("Error optimizing image {$relativeFilePath}: " . $e->getMessage());
                    }
                } elseif (in_array(mime_content_type($filePath), array('video/mp4', 'video/quicktime', 'video/x-msvideo'))) {
                    $destFilePath = preg_replace('/\.\w+$/i', '.mp4', $destFilePath);
                    $relativeDestFilePath = str_replace($destBasePath, '', $destFilePath);
                    try {
                        // Mensagem indicando o início da conversão
                        Shell::printInfo("Starting video conversion: $relativeFilePath");
                        Builder::video($filePath, $destFilePath);

                        Shell::printSuccess("Video optimized: {$relativeDestFilePath}");
                    } catch (\Exception $e) {
                        Shell::printError("Error optimizing video {$relativeFilePath}: " . $e->getMessage());
                    }
                } elseif (preg_match('/\.(css|js|scss|sass)$/i', $file)) {
                    $relativeDestFilePath = str_replace($destBasePath, '', $destFilePath);
                    try {
                        if (preg_match('/\.css$/i', $file)) {
                            Builder::minifyCss($filePath, $destFilePath);
                            $cssFiles[] = $destFilePath;
                            Shell::printSuccess("File minified: " . $relativeDestFilePath);
                        } elseif (preg_match('/\.js$/i', $file)) {
                            Builder::minifyJs($filePath, $destFilePath);
                            $jsFiles[] = $destFilePath;
                            Shell::printSuccess("File minified: " . $relativeDestFilePath);
                        } elseif (preg_match('/\.(scss|sass)$/i', $file)) {
                            Builder::minifyCss($filePath, $destFilePath);
                            $cssFiles[] = $destFilePath;
                            Shell::printSuccess("SCSS file compiled and minified: " . $relativeDestFilePath);
                        }
                    } catch (\Exception $e) {
                        Shell::printError("Error processing file {$relativeFilePath}: " . $e->getMessage());
                    }
                }

            }
        }
    }
    public static function createOrUpdateBuildEnv($timestamp)
    {
        $envFilePath = CONFIG_FOLDER . 'build.env';
        $content = "[Build configurations]\nTIMESTAMP=$timestamp\n";
        file_put_contents($envFilePath, $content);
        Shell::printInfo("build.env updated at $timestamp");
    }
}

// Usage example:
// Optimizer::image('path/to/original.jpg', 'path/to/optimized.jpg', 800);
// Optimizer::video('path/to/original.mp4', 'path/to/optimized.mp4');