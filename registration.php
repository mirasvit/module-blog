<?php
$registration = dirname(__DIR__, 4) . '/vendor/mirasvit/module-blog/registration.php';
if (file_exists($registration)) {
    # module was already installed via composer
    return;
}

\Magento\Framework\Component\ComponentRegistrar::register(
        \Magento\Framework\Component\ComponentRegistrar::MODULE,
        'Mirasvit_Blog',
        __DIR__
    );