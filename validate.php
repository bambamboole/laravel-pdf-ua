#!/usr/bin/env php
<?php

/**
 * Basic validation script for PDF/UA package
 * This validates the code structure without running full tests
 */

echo "=== PDF/UA Package Validation ===\n\n";

$errors = [];
$warnings = [];

// Check if main files exist
$requiredFiles = [
    'src/PdfUaGenerator.php',
    'src/PdfUaServiceProvider.php',
    'src/Commands/GeneratePdfUaCommand.php',
    'src/Facades/PdfUa.php',
    'config/laravel-pdf-ua.php',
    'README.md',
    'composer.json',
];

echo "1. Checking required files...\n";
foreach ($requiredFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "   ✓ {$file}\n";
    } else {
        echo "   ✗ {$file} - MISSING\n";
        $errors[] = "Missing required file: {$file}";
    }
}
echo "\n";

// Check namespace consistency
echo "2. Checking namespace consistency...\n";
$namespaceFiles = [
    'src/PdfUaGenerator.php' => 'Bambamboole\\LaravelPdfUa',
    'src/PdfUaServiceProvider.php' => 'Bambamboole\\LaravelPdfUa',
    'src/Commands/GeneratePdfUaCommand.php' => 'Bambamboole\\LaravelPdfUa\\Commands',
    'src/Facades/PdfUa.php' => 'Bambamboole\\LaravelPdfUa\\Facades',
];

foreach ($namespaceFiles as $file => $expectedNamespace) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        if (strpos($content, "namespace {$expectedNamespace}") !== false) {
            echo "   ✓ {$file} has correct namespace\n";
        } else {
            echo "   ✗ {$file} has incorrect namespace\n";
            $errors[] = "Incorrect namespace in {$file}";
        }
    }
}
echo "\n";

// Check composer.json structure
echo "3. Checking composer.json...\n";
$composerJson = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
if ($composerJson) {
    if (isset($composerJson['name']) && $composerJson['name'] === 'bambamboole/laravel-pdf-ua') {
        echo "   ✓ Package name is correct\n";
    } else {
        $errors[] = "Incorrect package name in composer.json";
    }

    if (isset($composerJson['require']['tecnickcom/tcpdf'])) {
        echo "   ✓ TCPDF dependency is present\n";
    } else {
        $errors[] = "TCPDF dependency missing";
    }

    if (isset($composerJson['autoload']['psr-4']['Bambamboole\\LaravelPdfUa\\'])) {
        echo "   ✓ PSR-4 autoload is configured\n";
    } else {
        $errors[] = "PSR-4 autoload not configured correctly";
    }
} else {
    $errors[] = "composer.json is invalid JSON";
}
echo "\n";

// Check PdfUaGenerator class methods
echo "4. Checking PdfUaGenerator class structure...\n";
$generatorContent = file_get_contents(__DIR__ . '/src/PdfUaGenerator.php');
$requiredMethods = [
    'generate',
    'generateStructuredHtml',
    'renderElement',
    'setConfig',
    'getConfig',
];

foreach ($requiredMethods as $method) {
    if (strpos($generatorContent, "function {$method}") !== false || 
        strpos($generatorContent, "public function {$method}") !== false) {
        echo "   ✓ Method {$method}() exists\n";
    } else {
        $errors[] = "Missing method {$method}() in PdfUaGenerator";
    }
}
echo "\n";

// Check config file structure
echo "5. Checking configuration file...\n";

// Mock env() function for validation
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $default;
    }
}

$config = include __DIR__ . '/config/laravel-pdf-ua.php';
if (is_array($config)) {
    $requiredKeys = ['title', 'author', 'subject', 'keywords', 'language', 'pdf_version'];
    foreach ($requiredKeys as $key) {
        if (array_key_exists($key, $config)) {
            echo "   ✓ Config key '{$key}' exists\n";
        } else {
            $warnings[] = "Config key '{$key}' is missing";
        }
    }
} else {
    $errors[] = "Config file does not return an array";
}
echo "\n";

// Summary
echo "=== Validation Summary ===\n\n";

if (count($errors) === 0 && count($warnings) === 0) {
    echo "✓ All validations passed successfully!\n";
    echo "✓ The PDF/UA package structure is correct.\n\n";
    echo "Note: Full functionality testing requires installing dependencies with 'composer install'.\n";
    exit(0);
} else {
    if (count($errors) > 0) {
        echo "✗ Errors found:\n";
        foreach ($errors as $error) {
            echo "  - {$error}\n";
        }
        echo "\n";
    }

    if (count($warnings) > 0) {
        echo "⚠ Warnings:\n";
        foreach ($warnings as $warning) {
            echo "  - {$warning}\n";
        }
        echo "\n";
    }

    exit(count($errors) > 0 ? 1 : 0);
}
