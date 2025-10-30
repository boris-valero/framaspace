<?php
/**
 * Template pour les paramètres administrateur Framaspace
 * 
 * @var array $_ 
 * @var array $apps Liste des applications installées
 */
?>

<div id="framaspace-admin-settings">
    <h1>Applications installées sur cette instance Nextcloud</h1>
    <p>Liste des applications actuellement installées :</p>
    
    <table>
        <thead>
            <tr>
                <th>ID de l'application</th>
                <th>Nom</th>
                <th>Version</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_['apps'] as $app): ?>
            <tr>
                <td><?php p($app['id']); ?></td>
                <td><?php p($app['name']); ?></td>
                <td><?php p($app['version']); ?></td>
                <td><?php p($app['enabled'] ? 'Activée' : 'Désactivée'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><strong>Total :</strong> <?php p(count($_['apps'])); ?> applications installées</p>
</div>