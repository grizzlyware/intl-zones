#!/usr/bin/env php
<?php

/**
 * Inserts a release section into CHANGELOG.md, directly above the newest
 * existing one, keeping the format the file already uses:
 *
 *   ## V1.2.1 - 2023-03-17
 *
 * The body is read from stdin.
 *
 * Usage: prepend-changelog.php <changelog> <version> <date>
 */

if ($argc !== 4) {
    fwrite(STDERR, "Usage: {$argv[0]} <changelog> <version> <date>" . PHP_EOL);

    exit(2);
}

[, $path, $version, $date] = $argv;

if (! is_file($path)) {
    fwrite(STDERR, "Not a file: {$path}" . PHP_EOL);

    exit(2);
}

$changelog = file_get_contents($path);

// The existing file titles its sections with a capital V.
$heading = sprintf('## V%s - %s', ltrim($version, 'vV'), $date);

if (str_contains($changelog, $heading)) {
    fwrite(STDERR, "Already present: {$heading}" . PHP_EOL);

    exit(0);
}

$body = trim(stream_get_contents(STDIN));

if ('' === $body) {
    fwrite(STDERR, "Refusing to write an empty changelog entry." . PHP_EOL);

    exit(2);
}

$entry = $heading . PHP_EOL . PHP_EOL . $body . PHP_EOL;

$firstSection = strpos($changelog, '## ');

if (false === $firstSection) {
    // No releases documented yet, so append after the preamble.
    $changelog = rtrim($changelog) . PHP_EOL . PHP_EOL . $entry;
} else {
    $changelog = substr($changelog, 0, $firstSection)
        . $entry
        . PHP_EOL
        . substr($changelog, $firstSection);
}

file_put_contents($path, $changelog);

echo "Added {$heading}" . PHP_EOL;
