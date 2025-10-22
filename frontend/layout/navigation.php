<?php
$menu = $settings['navigation_pages'];
?>

<?php foreach ($menu as $item) : ?>
    <?php if (!($item['adminOnly'] ?? false)): ?>
        <li>
            <a href="<?= $item['link'] ?>"><?= $item['name'] ?></a>
            <?php if (!empty($item['submenu'])): ?>
                <ul>
                    <?php foreach ($item['submenu'] as $submenuItem): ?>
                        <li><a href="<?= $submenuItem['link'] ?>"><?= $submenuItem['name'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endif; ?>
<?php endforeach; ?>