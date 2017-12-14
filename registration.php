<?php
$registration = dirname(dirname(dirname(__DIR__))) . '/vendor/mirasvit/module-blog/registration.php';
if (file_exists($registration)) {
    # module was already installed via composer
    return;
}

\Magento\Framework\Component\ComponentRegistrar::register(
        \Magento\Framework\Component\ComponentRegistrar::MODULE,
        'Mirasvit_Blog',
        __DIR__
    );