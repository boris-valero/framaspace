<?php
/**
 * Template pour les paramètres administrateur Framaspace
 *
 * @var array $_
 * @var array $apps Liste des applications installées
 */

script('framaspace', 'admin-settings');
style('framaspace', 'admin-settings');

// CSS inline pour le masquage des apps
if (!empty($_['apps'])) {
    $hiddenApps = array_filter($_['apps'], fn($app) => $app['hidden']);
    if (!empty($hiddenApps)) {
        echo '<style>';
        foreach ($hiddenApps as $app) {
            echo "#appmenu li[data-id=\"{$app['id']}\"] { display: none !important; }\n";
            echo "#app-navigation-vue li[data-id=\"{$app['id']}\"] { display: none !important; }\n";
            echo "a[href*=\"/apps/{$app['id']}/\"] { display: none !important; }\n";
        }
        echo "#appmenu { display: flex !important; }\n";
        echo "#appmenu li:not([style*=\"display: none\"]) { display: inline-block !important; }\n";
        echo '</style>';
    }
}
?>

<div id="framaspace-admin-settings">
    <h1>Applications installées sur cette instance Nextcloud</h1>
    <p>Liste des applications actuellement installées :</p>
    
    <form id="hidden-apps-form">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>État</th>
                    <th>Cachée dans le menu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_['apps'] as $app): ?>
                <tr>
                    <td><?php p($app['name']); ?></td>
                    <td><?php p($app['enabled'] ? 'Activée' : 'Désactivée'); ?></td>
                    <td>
                        <input type="checkbox" 
                               id="app-<?php p($app['id']); ?>" 
                               name="hidden_apps[]" 
                               value="<?php p($app['id']); ?>"
                               <?php if ($app['hidden']): ?>checked<?php endif; ?>>
                        <label for="app-<?php p($app['id']); ?>">Cacher</label>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="form-actions">
            <button type="submit" class="button primary">Sauvegarder</button>
            <span id="save-status" class="save-status"></span>
        </div>
    </form>
    
    <p><strong>Total :</strong> <?php p(count($_['apps'])); ?> applications installées</p>
    
    <div class="info-box">
        <p><strong>Information :</strong> Les applications cochées comme "cachées" ne seront pas visibles dans le menu supérieur pour tous les utilisateurs de cette instance Nextcloud.</p>
    </div>
</div>